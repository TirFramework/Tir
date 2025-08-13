<?php

namespace Tir\Crud\Support\Scaffold;

class FieldsHandler
{

    private array $fields = [];

    private array $indexFields = [];
    private array $editFields = [];
    private array $detailFields = [];
    private array $createFields = [];

    /**
     * Constructor initializes fields for a specific page and model
     *
     * @param array $fields Array of field objects
     * @param string $page Page type (index, create, edit, detail)
     * @param mixed $model Model instance with data
     */
    final public function __construct(array $fields, string $page, $model = null)
    {
        foreach ($fields as $field) {
            $field->page($page);
            if ($page === 'detail') {
                $field->readonly();
            }
            // Process field with model data
            $this->fields[] = $field->get($model);
        }
    }


    final function getFields(): array
    {

        return $this->fields;

    }

    final function getAllFields()
    {
        $fields = $this->getFields();
        $allFields = $this->getAllChildren($fields);
        return collect($allFields)->values()->toArray();
    }

    final function getAllDataFields()
    {
        $fields = $this->getFields();
        $allFields = $this->getAllChildren($fields);
        return collect($allFields)->where('requestable', true)->values()->toArray();
    }


    /**
     * Get fillable columns by merging scaffold and model fillable arrays
     *
     * @param array $modelFillable Fillable fields from model
     * @param array $modelGuarded Guarded fields from model
     * @return array Final fillable fields
     */
    final function getFillableColumns(array $modelFillable = [], array $modelGuarded = []): array
    {
        $scaffoldFillable = collect($this->getAllDataFields())
            ->where('fillable', true)
            ->pluck('request')
            ->flatten()
            ->unique()
            ->toArray();

        $fillables = array_merge($scaffoldFillable, $modelFillable);
        $finalFillables = array_diff($fillables, $modelGuarded);

        return $finalFillables;
    }




    /**
     * Get fields for index page with caching
     *
     * @return array Array of fields to display on index page
     */
    final function getIndexFields(): array
    {
        if (count($this->indexFields) > 0) {
            return $this->indexFields;
        }

        // Calculate and cache the result
        $allFields = $this->getAllChildren($this->getFields());
        $this->indexFields = collect($allFields)->where('showOnIndex')->values()->toArray();

        return $this->indexFields;
    }

    final function getEditFields(): array
    {
        if (count($this->editFields) > 0) {
            return $this->editFields;
        }

        $fields = $this->getFields();
        $this->editFields = $this->getChildren($fields, 'showOnEditing');

        return $this->editFields;
    }

    final function getDetailFields(): array
    {
        if (count($this->detailFields) > 0) {
            return $this->detailFields;
        }

        $fields = $this->getFields();
        $this->detailFields = $this->getChildren($fields, 'showOnDetail');

        return $this->detailFields;
    }

    final function getCreateFields(): array
    {
        if (count($this->createFields) > 0) {
            return $this->createFields;
        }

        $fields = $this->getFields();
        $this->createFields = $this->getChildren($fields, 'showOnCreating');

        return $this->createFields;
    }

    /**
     * Find a field by its name
     *
     * @param string $name Field name to search for
     * @return object|null Field object if found, null otherwise
     */
    final function getFieldByName(string $name): ?object
    {
        foreach ($this->getAllFields() as $field) {
            if ($field->name === $name) {
                return $field;
            }
        }

        return null;
    }

    final function getSearchableFields(): array
    {
        $fields = [];
        foreach ($this->getIndexFields() as $field) {
            if ($field->searchable) {
                $fields[] = $field;
            }
        }
        return $fields;
    }


    private function getSubFields($fields, $allFields)
    {
        foreach ($fields as $field) {
            if (isset($field->children) && !$field->requestable) {
                $allFields = $this->getSubFields($field->subFields, $allFields);
            } elseif ($field->requestable) {
                $allFields[] = $field;
            }
        }
        return $allFields;
    }


    private function getChildren($fields, $page): array
    {
        $allFields = [];
        foreach ($fields as $field) {
            if ($field->{$page}) {
                if (isset($field->children) && $field->type != 'Additional') {
                    $field->children = collect($field->children)->where($page, true)->values()->toArray();
                    $field->children = $this->getChildren($field->children, $page);
                }
                $allFields[] = $field;
            }
        }

        return $allFields;
    }

    /**
     * Get all children fields recursively, flattening the structure
     *
     * @param array $fields Fields to process
     * @return array Flattened array of all child fields
     */
    private function getAllChildren(array $fields): array
    {
        $allFields = [];
        foreach ($fields as $field) {
            if (isset($field->children) && $field->type !== 'Additional') {
                $children = $field->children;
                $allFields[] = $field;
                $allFields = array_merge($allFields, $this->getAllChildren($children));
            } else {
                $allFields[] = $field;
            }
        }

        return $allFields;
    }
}
