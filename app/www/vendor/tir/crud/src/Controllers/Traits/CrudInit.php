<?php

namespace Tir\Crud\Controllers\Traits;



trait CrudInit
{
    use AccessControl;

    private mixed $model;
    private mixed $scaffolder;

    protected abstract function setScaffolder(): string;

    public function __construct()
    {
        $this->scaffolderInit();

        // Auto setup crud hooks if method exists
        if (method_exists($this, 'setup')) {
            $this->setup();
        }
    }

    protected final function model()
    {
        return $this->model;
    }

    protected final function scaffolder()
    {
        return $this->scaffolder;
    }

    private function scaffolderInit(): void
    {
        $s = $this->setScaffolder();
        $this->scaffolder = new $s;

        $m = $this->scaffolder->model();
        $this->model = new $m;
    }

    public function callAction($method, $parameters)
    {
        // Auto-check access before calling ANY method
        $this->enforceAccess($method);

        // Check if parent has callAction method (Laravel's routing controller)
        if (method_exists(parent::class, 'callAction')) {
            return parent::callAction($method, $parameters);
        }

        // Fallback: manually call the method with proper parameter handling
        // Convert associative array to positional parameters if needed
        if (!empty($parameters) && array_keys($parameters) !== range(0, count($parameters) - 1)) {
            // Has named parameters, convert to positional
            $parameters = array_values($parameters);
        }

        return $this->$method(...$parameters);
    }

}
