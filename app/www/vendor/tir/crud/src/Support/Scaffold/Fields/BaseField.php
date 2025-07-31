<?php

namespace Tir\Crud\Support\Scaffold\Fields;

use Illuminate\Support\Arr;
use Tir\Crud\Support\Enums\FilterType;

abstract class BaseField
{
    protected string $type;
    protected string $originalName;
    protected string $name;
    protected mixed $request;
    protected string $page;
    protected string $valueType = 'string';
    protected mixed $value;
    protected string $display;
    protected string $placeholder = '';
    protected string $class = '';
    protected string $group = 'all';
    protected int $col = 24;
    protected bool $disable = false;
    protected bool $readonly = false;
    protected string $defaultValue;
    protected bool $showOnIndex = true;
    protected bool $showOnDetail = true;
    protected bool $showOnCreating = true;
    protected bool $showOnEditing = true;
    protected bool $sortable = false;
    protected bool $searchable = false;
    protected array $rules = [];
    protected array $creationRules = [];
    protected array $updateRules = [];
    protected array $options = [];
    protected array $data = [];
    protected array $filter = [];
    protected FilterType | string $filterType = FilterType::Select;
    protected bool $filterable = false;
    protected bool $multiple = false;
    protected array $comment = [];
    protected $dataSet = [];
    protected bool $additional = false;
    protected bool $fillable = true;
    protected bool $requestable = true;
    protected bool $virtual = false;
    protected mixed $filterQuery;



    public static function make(string $name): static
    {
        $obj = new static;
        $obj->init();
        $obj->originalName = $obj->name = $obj->request = $obj->className = $name;
        $obj->className = str_replace('.', '-', $name);
        $obj->display = ucwords(str_replace('_', ' ', $name));
        return $obj;
    }

    protected function init(): void
    {
    }
    public function page(string $page): BaseField
    {
        $this->page = $page;
        return $this;
    }
    public function display(string $value): BaseField
    {
        $this->display = $value;
        return $this;
    }

    public function class(string $name): BaseField
    {
        $this->class = $this->class . ' ' . $name;
        return $this;
    }

    public function placeholder(string $text): BaseField
    {
        $this->placeholder = $text;
        return $this;
    }

    public function col(string $number): BaseField
    {
        $this->col = $number;
        return $this;
    }

    public function comment(string $content, string $title = ''): BaseField
    {
        $this->comment = [
            'title' => $title,
            'content' => $content,
        ];
        return $this;
    }

    public function disable(bool $option = true): BaseField
    {
        $this->disable = $option;
        return $this;
    }

    public function readonly(bool $option = true): BaseField
    {
        $this->readonly = $option;
        return $this;
    }

    public function fillable(bool $option = true): BaseField
    {
        $this->fillable = $option;
        return $this;
    }

    public function options($options = []): BaseField
    {
        $this->options = $this->options + $options;
        return $this;
    }

    public function default(mixed $value): BaseField
    {
        $this->defaultValue = $value;
        return $this;
    }


    public function virtual(bool $value = true): BaseField
    {
        $this->virtual = $value;
        return $this;
    }

    public function showOnIndex($callback = true): BaseField
    {
        $this->showOnIndex = is_callable($callback) ? !call_user_func_array($callback, func_get_args())
            : $callback;
        return $this;
    }

    public function showOnCreating($callback = true): BaseField
    {
        $this->showOnCreating = is_callable($callback) ? !call_user_func_array($callback, func_get_args())
            : $callback;
        return $this;
    }

    public function showOnEditing($callback = true): BaseField
    {
        $this->showOnEditing = is_callable($callback) ? !call_user_func_array($callback, func_get_args())
            : $callback;
        return $this;
    }

    public function showOnDetail(bool $callback = true): BaseField
    {
        $this->showOnEditing = is_callable($callback) ? !call_user_func_array($callback, func_get_args())
            : $callback;
        return $this;
    }

    public function hideFromIndex(bool $callback = true): BaseField
    {
        $this->showOnIndex = is_callable($callback) ? !call_user_func_array($callback, func_get_args())
            : !$callback;
        return $this;
    }

    public function hideWhenCreating(bool $callback = true): BaseField
    {
        $this->showOnCreating = is_callable($callback) ? !call_user_func_array($callback, func_get_args())
            : !$callback;
        return $this;
    }

    public function hideWhenEditing($callback = true): BaseField
    {
        $this->showOnEditing = is_callable($callback) ? !call_user_func_array($callback, func_get_args())
            : !$callback;
        return $this;
    }

    public function hideFromDetail($callback = true): BaseField
    {
        $this->showOnDetail = is_callable($callback) ? !call_user_func_array($callback, func_get_args())
            : !$callback;
        return $this;
    }

    public function hideFromAll($callback = true): BaseField
    {
        $this->showOnCreating =
            $this->showOnEditing =
            $this->showOnIndex =
            $this->showOnDetail =
            is_callable($callback) ? !call_user_func_array($callback, func_get_args())
            : !$callback;
        return $this;
    }

    public function onlyOnIndex(): BaseField
    {
        $this->showOnCreating = $this->showOnEditing = $this->showOnDetail = false;
        $this->showOnIndex = true;
        return $this;
    }

    public function onlyOnCreating(): BaseField
    {
        $this->showOnIndex = $this->showOnEditing = $this->showOnDetail = false;
        $this->showOnCreating = true;
        return $this;
    }

    public function onlyOnEditing($callback = true): BaseField
    {
        $this->showOnCreating = $this->showOnIndex = $this->showOnDetail = is_callable($callback) ? !call_user_func_array($callback, func_get_args())
            : !$callback;

        $this->showOnEditing = is_callable($callback) ? !call_user_func_array($callback, func_get_args())
            : $callback;

        return $this;
    }

    public function onlyOnDetail($callback = true): BaseField
    {
        $this->showOnCreating = $this->showOnIndex = $this->showOnEditing = is_callable($callback) ? !call_user_func_array($callback, func_get_args())
            : !$callback;

        $this->showOnDetail = is_callable($callback) ? !call_user_func_array($callback, func_get_args())
            : $callback;

        return $this;
    }

    public function sortable(bool $check = true): BaseField
    {
        $this->sortable = $check;
        return $this;
    }

    public function searchable(bool $check = true): BaseField
    {
        $this->searchable = $check;
        return $this;
    }

    /**
     * Helper method to normalize variadic arguments
     * Handles both array and individual parameters
     */
    private function normalizeVariadicArgs(...$args): array
    {
        // Handle single array input: ->method(['item1', 'item2'])
        if (count($args) === 1 && is_array($args[0])) {
            return $args[0];
        }
        
        // Handle multiple parameters: ->method('item1', 'item2')
        return $args;
    }

    public function rules(...$rules): BaseField
    {
        $flattenedRules = $this->normalizeVariadicArgs(...$rules);
        
        $this->creationRules = array_merge($this->creationRules, $flattenedRules);
        $this->updateRules = array_merge($this->updateRules, $flattenedRules);
        $this->rules = $flattenedRules;
        return $this;
    }

    public function creationRules(...$rules): BaseField
    {
        $normalizedRules = $this->normalizeVariadicArgs(...$rules);
        $this->creationRules = array_merge($this->rules, $normalizedRules);
        return $this;
    }

    public function updateRules(...$rules): BaseField
    {
        $normalizedRules = $this->normalizeVariadicArgs(...$rules);
        $this->updateRules = array_merge($this->rules, $normalizedRules);
        return $this;
    }

    public function data(...$data): BaseField
    {
        $normalizedData = $this->normalizeVariadicArgs(...$data);
        $this->data = $normalizedData;
        $this->dataSet = collect($normalizedData)->pluck('label', 'value')->toArray();
        return $this;
    }


    public function filter(...$items): BaseField
    {
        $this->filterable = true;
        
        $normalizedItems = $this->normalizeVariadicArgs(...$items);

        if (count($normalizedItems)) {
            $this->filter = $normalizedItems;
            return $this;
        }

        if (isset($this->data)) {
            $this->filter = $this->data;
            return $this;
        }

        return $this;
    }


    public function filterType(FilterType | string $type): BaseField
    {
        $this->filterType = $type;
        return $this;
    }

    public function filterQuery($queryFunction)
    {
        $this->filterQuery = $queryFunction;
        return $this;
    }


    protected function setValue($model): void
    {
        if (isset($model)) {
            $value = Arr::get($model, $this->name);
            if (isset($value)) {
                $this->value = $value;
            }
        }
    }

    public function get($dataModel)
    {
        $this->setValue($dataModel);
        return (object) get_object_vars($this);
    }
}
