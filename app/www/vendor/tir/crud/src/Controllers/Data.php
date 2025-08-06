<?php

namespace Tir\Crud\Controllers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Response;
use Tir\Crud\Support\Enums\FilterType;


trait Data
{
    private array $selectFields = [];
    private mixed $query;

    public final function data()
    {
        // $relations = $this->getRelationFields($this->model());
        $query = $this->dataQuery();
        $paginatedItems = $this->applyPagination($query);

        return Response::Json($paginatedItems, 200);
    }

    private function applyPagination($query)
    {
        // Check if there's a custom pagination hook
        if (isset($this->crudHookCallbacks['modifyPaginate'])) {
            $customPagination = call_user_func($this->crudHookCallbacks['modifyPaginate'], $query);
            if ($customPagination !== null) {
                return $customPagination;
            }
        }

        // Default pagination behavior
        return $query->paginate(request()->input('result'));
    }




    private function getRelations($query)
    {
        // Check if there's a custom modify method
        if (isset($this->crudHookCallbacks['modifyRelations'])) {
            $customResult = call_user_func($this->crudHookCallbacks['modifyRelations'], $query);
            if ($customResult !== null) {
                return $customResult;
            }
        }

        // Default behavior
        foreach ($this->scaffolder()->getIndexFields() as $field) {
            if (isset($field->relation)) {
                if ($this->model()->getConnection()->getName() == 'mongodb') {
                    if ($field->multiple) {
                        // mongoDB need foreign key in many-to-many relation
                        $foreignKey = $this->model()->{$field->relation->name}()->getForeignKey();
                        $otherKey = $this->model()->{$field->relation->name}()->getRelated()->getKeyName();
                        $query = $query->with($field->relation->name, function ($q) use ($field, $foreignKey, $otherKey) {
                            $q->select($foreignKey, $otherKey, $field->relation->field);
                        });
                    } else {
                        $otherKey = $this->model()->{$field->relation->name}()->getRelated()->getKeyName();
                        $query = $query->with($field->relation->name, function ($q) use ($field, $otherKey) {
                            $q->select($otherKey, $field->relation->field);
                        });
                    }
                } else {
                    $relationTable = $this->model()->{$field->relation->name}()->getRelated()->getTable();
                    $relationKey = $relationTable . '.' . $field->relation->key;
                    $query = $query->with($field->relation->name, function ($q) use ($field, $relationKey) {
                        $q->select($relationKey, $field->relation->field);
                    });
                }
            }
        }
        return $query;
    }

    /**
     * Build the main data query with all modifications
     */
    private function dataQuery(): Builder
    {
        $columns = $this->selectColumns();

        return $this->initQuery()
            ->select($columns)
            ->tap(fn($query) => $this->getRelations($query))
            ->tap(fn($query) => $this->applySearch($query))
            ->tap(fn($query) => $this->applyFilters($query))
            ->tap(fn($query) => $this->applySort($query));
    }

    private function selectColumns()
    {
        // Check if there's a custom modify method
        if (isset($this->crudHookCallbacks['modifySelect'])) {
            $customColumns = call_user_func($this->crudHookCallbacks['modifySelect']);
            if ($customColumns !== null) {
                return $customColumns;
            }
        }

        // Default behavior
        if ($this->model()->getConnection()->getName() == 'mongodb') {
            $this->selectFields = array_merge($this->selectFields, collect($this->model()->getIndexFields())->pluck('name')->toArray());
        } else {
            $this->selectFields[] = $this->model()->getTable() . '.' . $this->model()->getKeyName();
            foreach ($this->scaffolder()->getIndexFields() as $field) {
                //Check if field is many to many relation or not
                if (!$field->virtual) {
                    if (!isset($field->relation) || !$field->multiple) {
                        $this->selectFields[] = $this->model()->getTable() . '.' . $field->name;
                    }
                }
            }
        }

        $selecable = array_merge($this->scaffolder()->getAppendedSelectableColumns(), $this->selectFields);
        return $selecable;

    }

    private function initQuery()
    {
        // Check if there's a custom modify method
        if (isset($this->crudHookCallbacks['modifyInitQuery'])) {
            $customQuery = call_user_func($this->crudHookCallbacks['modifyInitQuery']);
            if ($customQuery !== null) {
                return $customQuery;
            }
        }

        // Default behavior
        return $this->model()->query();
    }



    private function selectQuery($query, $columns): Builder
    {
        return $this->model()->select($columns);
    }



    private function applySearch($query)
    {
        // Check if there's a custom modify method
        if (isset($this->crudHookCallbacks['modifySearch'])) {
            $customQuery = call_user_func($this->crudHookCallbacks['modifySearch'], $query);
            if ($customQuery !== null) {
                return $customQuery;
            }
        }

        // Default behavior
        $req = request()->input('search');
        if ($req == null) {
            return $query;
        }
        $searchableFields = $this->model()->getSearchableFields();

        foreach ($searchableFields as $field) {
            $query->orWhere($field->name, 'like', "%$req%");
        }

        return $query;
    }


    private function applyFilters($query)
    {
        // Check if there's a custom modify method
        if (isset($this->crudHookCallbacks['modifyFilters'])) {
            $customQuery = call_user_func($this->crudHookCallbacks['modifyFilters'], $query);
            if ($customQuery !== null) {
                return $customQuery;
            }
        }

        // Default behavior
        $req = json_decode(request()->input('filters'));
        if ($req == null) {
            return $query;
        }


        $filters = $this->getFilter($req);

        foreach ($filters['original'] as $filter) {

            if ($filter['type'] == FilterType::Select) {
                $query->whereIn($filter['column'], $filter['value']);
            } elseif ($filter['type'] == FilterType::Slider) {
                $query->where($filter['column'], '>=', $filter['value'][0]);
                $query->where($filter['column'], '<=', $filter['value'][1]);
            } elseif ($filter['type'] == FilterType::DatePicker) {
                if ($this->model()->getConnection()->getName() == 'mongodb') {

                    $query->where(function ($query) use ($filter) {
                        $query->where($filter['column'], '>=', new \MongoDB\BSON\UTCDateTime(Carbon::make($filter['value'][0])->startOfDay()));
                        $query->orWhere($filter['column'], '>=', Carbon::make($filter['value'][0])->startOfDay()->toDateString());
                    });
                    $query->where(function ($query) use ($filter) {
                        $query->where($filter['column'], '<=', new \MongoDB\BSON\UTCDateTime(Carbon::make($filter['value'][1])->endOfDay()));
                        $query->orWhere($filter['column'], '<=', Carbon::make($filter['value'][1])->endOfDay()->toDateString());
                    });
                } else {
                    $query->whereDate($filter['column'], '>=', Carbon::make($filter['value'][0])->startOfDay());
                    $query->whereDate($filter['column'], '<=', Carbon::make($filter['value'][1])->endOfDay());
                }
            } elseif ($filter['type'] == FilterType::Search) {
                $query->where($filter['column'], 'like', "%" . $filter['value'] . "%");
            }
        }

        foreach ($filters['relational'] as $filter) {
            $query->whereHas($filter['relation'], function (Builder  $q) use ($filter) {
                $q->whereIn($filter['primaryKey'], $filter['value']);
            });
        }

        foreach ($filters['customQuery'] as $filter) {

            //  Here we call the callback function from the field definition
            //  It's like this:
            //   $model->query(function($query, $value){
            //       return $query->where('column', $value);
            //   })

            // the filter value comes from the request and the query comes form field definition

            $query = $filter['query']($query, $filter['req']);
        }
        return $query;
    }


    private function applySort($query)
    {
        // Check if there's a custom modify method
        if (isset($this->crudHookCallbacks['modifySort'])) {
            $customQuery = call_user_func($this->crudHookCallbacks['modifySort'], $query);
            if ($customQuery !== null) {
                return $customQuery;
            }
        }

        // Default behavior
        $req = request()->input('sorter');
        if ($req == null) {
            return $query->orderBy('created_at', 'DESC');
        }

        $sort = json_decode($req);

        if (!isset($sort->field)) {
            return $query->orderBy('created_at', 'DESC');
        }
        $sort->order = $sort->order == 'ascend' ? 'ASC' : 'DESC';
        $query->orderBy($sort->field, $sort->order);
        return $query;
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
                //get table name from relation
                $table = $this->model()->{$field->relation->name}()->getRelated()->getTable();

                //get primary key from relation
                $primaryKey = $this->model()->{$field->relation->name}()->getRelated()->getKeyName();

                $primaryKey = $table . '.' . $primaryKey;

                if ($this->model()->getConnection()->getName() == 'mongodb') {
                    $primaryKey = $this->model()->{$field->relation->name}()->getRelated()->getKeyName();
                }

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
        foreach ($this->scaffolder()->getIndexFields() as $field) {
            if (isset($field->relation)) {
                //                $relation = $field->relation->name . ':' . $field->relation->key . ',' . $field->relation->field. ' as text';
                $relation = $field->relation->name . ':' . $field->relation->key;

                if ($model->getConnection()->getName() == 'mongodb') {
                    $relation = $field->relation->name;
                }
                $relations[] = $relation;
            }
        }

        return $relations;
    }
}
