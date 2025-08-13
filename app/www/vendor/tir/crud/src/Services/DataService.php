<?php

namespace Tir\Crud\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Tir\Crud\Support\Enums\FilterType;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\Builder;
use Tir\Crud\Support\Hooks\IndexDataHooks;
use Tir\Crud\Support\Database\DatabaseAdapterFactory;

class DataService
{
    use IndexDataHooks;
    private $scaffolder;
    private $model;
    private $query;
    private $onlyTrashed = false;
    private array $selectFields = [];



    public function __construct($scaffolder, $model)
    {
        $this->scaffolder = $scaffolder;
        $this->model = $model;

    }

    /**
     * Set hooks from controller
     */
    public function setHooks(array $hooks): void
    {
        $this->crudHookCallbacks = $hooks;
    }

    public function getData($onlyTrashed = false)
    {
        // Store the trash mode for use in initQuery
        $this->onlyTrashed = $onlyTrashed;
        $this->query =  $this->dataQuery();
        return $this->query;

    }

    private function model()
    {
        return $this->model;
    }

    private function scaffolder()
    {
        return $this->scaffolder;
    }

    /**
     * Build the main data query with all modifications
     */
    private function dataQuery(): mixed
    {
                // Define the default behavior as a closure
            $this->query = $this->initQuery();
            $this->query  = $this->select($this->query);
            $this->query  = $this->getRelations($this->query);
            $this->query  = $this->applySearch($this->query);
            $this->query  = $this->applyFilters($this->query);
            $this->query  = $this->applySort($this->query);
            $this->query = $this->applyModifiedQuery($this->query);
            Log::debug('Data query initialized', [
                'query' => $this->query->toSql(),
            ]);
            $this->query = $this->applyPaginate($this->query);


            return $this->query;
    }


    private function initQuery(): mixed
    {

        // Define the default behavior as a closure
        $defaultInitQuery = function() {
            $query = $this->model()->query();
            if ($this->onlyTrashed) {
                $query = $query->onlyTrashed();
            }
            return $query;
        };

        // Pass the closure to the hook
        $customQuery = $this->callHook('onInitQuery', $defaultInitQuery);
        if($customQuery === null) {
            return $defaultInitQuery();
        }


        // Otherwise, return the result directly
        return $customQuery;
    }



    private function select($query): mixed
    {
        // Define the default behavior as a closure
        $defaultSelect = function($q = null) use ($query) {
            if ($q !== null) {
                $query = $q;
            }
            $columns = $this->selectColumns();
            return $query->select($columns);
        };

        // Pass the closure to the hook
        $customSelect = $this->callHook('onSelect', $defaultSelect, $query);
        if($customSelect !== null) {
            return $customSelect;
        }

        // Otherwise, return the result directly
        return $defaultSelect();
    }


    private function getRelations($query)
    {
        // Define the default behavior as a closure
        $defaultRelations = function($q = null) use ($query) {
            if ($q !== null) {
                $query = $q;
            }

            // Use database adapter for relation handling
            $adapter = DatabaseAdapterFactory::create($this->model()->getConnection());

            foreach ($this->scaffolder()->getIndexFields() as $field) {
                if (isset($field->relation)) {
                    $query = $adapter->configureRelations($query, $field);
                }
            }

            return $query;
        };

        // Pass the closure to the hook
        $customRelations = $this->callHook('onRelation', $defaultRelations, $query);
        if($customRelations !== null) {
            return $customRelations;
        }



        // Otherwise, return the result directly
        return $defaultRelations();
    }



    private function applySearch($query): mixed
    {

        $defaultSearch = function($q = null) use ($query) {
            if ($q !== null) {
                $query = $q;
            }
            $req = request()->input('search');
            if ($req == null) {
                return $query;
            }
            $searchableFields = $this->scaffolder()->getSearchableFields();
            if (empty($searchableFields)) {
                return $query;
            }

            foreach ($searchableFields as $field) {
                $query->orWhere($field->name, 'like', "%$req%");
            }
            return $query;
        };

        // Pass the closure to the hook
        $customSearch = $this->callHook('onSearch', $defaultSearch, $query);
        if($customSearch !== null) {
            return $customSearch;
        }


        // Otherwise, return the result directly
        return $defaultSearch();
    }


    private function applyFilters($query): mixed
    {

        $defaultFilters = function($q = null)use ($query) {
            if ($q !== null) {
                $query = $q;
            }
            $req = json_decode(request()->input('filters'));
            if ($req == null) {
                return $query;
            }

            $filters = $this->getFilter($req);

        // Use database adapter for filtering
        $adapter = DatabaseAdapterFactory::create($this->model()->getConnection());

        foreach ($filters['original'] as $filter) {
            if ($filter['type'] == FilterType::Select) {
                $query->whereIn($filter['column'], $filter['value']);
            } elseif ($filter['type'] == FilterType::Slider) {
                $query->where($filter['column'], '>=', $filter['value'][0]);
                $query->where($filter['column'], '<=', $filter['value'][1]);
            } elseif ($filter['type'] == FilterType::DatePicker) {
                $query = $adapter->applyDateFilter($query, $filter['column'], $filter['value']);
            } elseif ($filter['type'] == FilterType::Search) {
                $query->where($filter['column'], 'like', "%" . $filter['value'] . "%");
            }
        }            foreach ($filters['relational'] as $filter) {
                $query->whereHas($filter['relation'], function (Builder  $q) use ($filter) {
                    $q->whereIn($filter['primaryKey'], $filter['value']);
                });
            }

            foreach ($filters['customQuery'] as $filter) {
                $query = $filter['query']($query, $filter['req']);
            }

            return $query;
        };

        // Pass the closure to the hook
        $customFilters = $this->callHook('onFilter', $defaultFilters, $query);
        if($customFilters !== null) {
            return $customFilters;
        }



       // Otherwise, return the result directly
        return $defaultFilters();
    }


    private function applySort($query): mixed
    {
        // Define the default behavior as a closure
        $defaultSort = function($q = null) use ($query) {

            if ($q !== null) {
                $query = $q;
            }

            $req = request()->input('sorter');
            if ($req == null) {
                return $query->orderBy('created_at', 'DESC');
            }

            $sort = json_decode($req);

            if (!isset($sort->field)) {
                return $query->orderBy('created_at', 'DESC');
            }
            $sort->order = $sort->order == 'ascend' ? 'ASC' : 'DESC';
            return $query->orderBy($sort->field, $sort->order);
        };

        // Pass the closure to the hook
        $customSort = $this->callHook('onSort', $defaultSort, $query);
        if($customSort !== null) {
            return $customSort;
        }


        return $defaultSort();
    }

    private function applyModifiedQuery($query): mixed
    {
        // Define the default behavior as a closure
        $defaultModifiedQuery = function($q = null) use ($query) {
            if ($q !== null) {
                $query = $q;
            }
            return $query;
        };

        $customModifiedQuery = $this->callHook('onModifyQuery', $defaultModifiedQuery, $query);
        if($customModifiedQuery !== null) {
            return $customModifiedQuery;
        }

        return $defaultModifiedQuery();
    }

    private function applyPaginate($query): mixed
    {
        $defaultPagination = function($q = null) use ($query) {
            if ($q !== null) {
                $query = $q;
            }
            $perPage = request()->input('result', 15); // Default to 15 if not provided
            return $query->paginate($perPage);
        };
        $customPagination = $this->callHook('onPaginate', $defaultPagination, $query);
        if ($customPagination !== null) {
            return $customPagination;
        }

        return $defaultPagination();
    }

    private function indexResponse($items): mixed
    {
        // Define the default behavior as a closure
        $defaultResponse = function($i = null) use ($items) {
            if ($i !== null) {
                $items = $i;
            }
            return Response::json($items, 200);
        };

        // Pass the closure to the hook
        $customResponse = $this->callHook('onIndexResponse', $defaultResponse, $items);

        if($customResponse !== null) {
            return $customResponse;
        }

        // Otherwise, return the result directly
        return $defaultResponse();
    }


    private function selectColumns(): array
    {
        // Use database adapter for column selection
        $adapter = DatabaseAdapterFactory::create($this->model()->getConnection());
        $this->selectFields = $adapter->getSelectColumns($this->model(), $this->scaffolder()->getIndexFields());

        return $this->selectFields;
    }

    private function getFilter($req): array
    {

        $filters = ['original' => [], 'relational' => [], 'customQuery' => []];

        $original = [];
        $relational = [];
        $customQuery = [];

        foreach ($req as $filter => $value) {
            $field = $this->scaffolder()->getFieldByName($filter);
            if (isset($field->filterQuery)) {
                $customQuery[] = ['query' => $field->filterQuery, 'req' => $value];
                //if it has filterQuery, we escape rest of the code
                continue;
            }
            //if filter is manyToMany relation
            if (isset($field->relation) && $field->multiple ?? null == true) {
                // Use database adapter for many-to-many filtering
                $adapter = DatabaseAdapterFactory::create($this->model()->getConnection());
                $primaryKey = $adapter->getRelationPrimaryKey($this->model(), $field);

                $relational[] = ['relation' => $field->relation->name, 'value' => $value, 'primaryKey' => $primaryKey];
            } else {
                $original[] = ['column' => $field->name, 'value' => $req->{$field->name}, 'type' => $field->filterType];
            }
        }

        $filters['original'] = $original;
        $filters['relational'] = $relational;
        $filters['customQuery'] = $customQuery;

        return $filters;
    }

    private function getRelationFields($model): array
    {
        $relations = [];
        $adapter = DatabaseAdapterFactory::create($model->getConnection());

        foreach ($this->scaffolder()->getIndexFields() as $field) {
            if (isset($field->relation)) {
                // Different databases handle relations differently
                if ($adapter->getDriverName() === 'mongodb') {
                    $relation = $field->relation->name;
                } else {
                    $relation = $field->relation->name . ':' . $field->relation->key;
                }
                $relations[] = $relation;
            }
        }

        return $relations;
    }
}
