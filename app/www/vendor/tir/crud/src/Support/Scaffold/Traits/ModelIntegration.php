<?php
namespace Tir\Crud\Support\Scaffold\Traits;

trait ModelIntegration {

    protected mixed $currentModel = null;

    // Magic method to access current model properties
    public function __get($property)
    {
        if ($this->currentModel && property_exists($this->currentModel, $property)) {
            return $this->currentModel->$property;
        }
        return null;
    }

    public function __isset($property)
    {
        return $this->currentModel && isset($this->currentModel->$property);
    }

    // Helper methods for better readability
    protected function hasValue($property): bool
    {
        return $this->currentModel && isset($this->currentModel->$property);
    }

    protected function getValue($property, $default = null)
    {
        return $this->currentModel->$property ?? $default;
    }

    protected function currentModel()
    {
        return $this->currentModel;
    }
}
