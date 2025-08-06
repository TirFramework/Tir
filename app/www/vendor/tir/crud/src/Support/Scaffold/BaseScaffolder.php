<?php

namespace Tir\Crud\Support\Scaffold;

use Tir\Crud\Support\Scaffold\Fields\Button;

abstract class BaseScaffolder
{
    use FieldsHelper;
    use ButtonsHelper;
    use RulesHelper;
    use FieldImports; // Added for easier field access

    private string $moduleTitle;
    public bool $isScaffolded = false;
    private string $moduleName;
    private array $fields = [];
    private array $buttons = [];
    private array $actions = [];
    protected mixed $currentModel = null;


    protected abstract function setModuleName(): string;

    protected abstract function setFields(): array;

    protected abstract function setModel(): string;

    protected function setButtons(): array
    {
        return [
            Button::make('back')->action('Cancel'),
            Button::make('submit')->display('panel.submit')->action('Submit')->hideFromDetail(),
        ];
    }

    protected function scaffoldBoot(): void
    {
        //
    }

    protected function setAcl(): bool
    {
        return true;
    }

    protected function setModuleTitle(): string
    {
        return $this->moduleName;
    }

    protected function appendSelectableColumns(): array
    {
        return [];
    }


    protected function setActions(): array
    {
        return [];
    }

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

    public function scaffold($page = '', $model = null): static
    {
        if ($this->isScaffolded) {
            return $this;
        }
        $this->scaffoldBoot();

        // Set current model for magic method access
        $this->currentModel = $model;

        $this->moduleName = $this->setModuleName();
        $this->moduleTitle = $this->setModuleTitle();
        $this->actions = $this->setActions();
        $this->initActions();
        $this->addFieldsToScaffold($page, $model);
        $this->addButtonsToScaffold();
        $this->isScaffolded = true;
        return $this;
    }

    private function addFieldsToScaffold($page, $model): void
    {
        foreach ($this->setFields() as $field) {
            $field->page($page);
            if ($page === 'detail') {
                $field->readonly();
            }
            //here $this is the Model with data
            $this->fields[] = $field->get($model);
        }
    }

    public function getAccessLevelStatus(): bool
    {
        return $this->setAcl();
    }

    public function getAppendedSelectableColumns()
    {
        return $this->appendSelectableColumns();
    }

    private function getConfigs(): array
    {
        $m =  $this->model();
        $model = new $m;
        return [
            'actions'      => $this->getActions(),
            'module_title' => $this->moduleTitle,
            'primary_key'  => $model->getKeyName(),
        ];
    }

    private function initActions(): void
    {
        $baseActions = [
            'index'       => true,
            'create'      => true,
            'show'        => true,
            'edit'        => true,
            'destroy'     => true,
            'fullDestroy' => true,
        ];

        $this->actions = array_merge($baseActions, $this->actions);
        if($this->getAccessLevelStatus() && config('crud.accessLevelControl') != 'off') {
            $checkerClass = config('crud.aclCheckerClass') ?? \Tir\Crud\Support\Acl\Access::Class;

            if ($this->actions['index']){
                $this->actions['index'] = ($checkerClass::check($this->moduleName, 'index') !== 'deny');
            }
            if ($this->actions['create']){
                $this->actions['create'] = ($checkerClass::check($this->moduleName, 'create') !== 'deny');
            }
            if ($this->actions['show']){
                $this->actions['show'] = ($checkerClass::check($this->moduleName, 'show') !== 'deny');
            }
            if ($this->actions['edit']){
                $this->actions['edit'] = ($checkerClass::check($this->moduleName, 'edit') !== 'deny');
            }
            if ($this->actions['destroy']){
                $this->actions['destroy'] = ($checkerClass::check($this->moduleName, 'destroy') !== 'deny');
            }
            if ($this->actions['fullDestroy']){
                $this->actions['fullDestroy'] = ($checkerClass::check($this->moduleName, 'fullDestroy') !== 'deny');
            }
        }
    }

    final function model(): string
    {
        return $this->setModel();
    }


    final function getActions(): array
    {
        return $this->actions;
    }

    final function moduleName(): string
    {
        if (isset($this->moduleName)) {
            return $this->moduleName;
        }
        return $this->setModuleName();
    }

    final function getRouteName(): string
    {
        return $this->routeName;
    }

    final function getIndexScaffold(): array
    {
        $this->scaffold('index');
        return [
            'fields'  => $this->getIndexFields(),
            'buttons' => $this->getIndexButtons(),
            'configs' => $this->getConfigs()
        ];
    }

    final function getCreateScaffold(): array
    {
        $this->scaffold('create');
        return [
            'fields'        => $this->getCreateFields(),
            'buttons'       => $this->getCreateButtons(),
            'validationMsg' => $this->getValidationMsg(),
            'configs'       => $this->getConfigs()
        ];
    }

    final function getEditScaffold($model): array
    {
        $this->scaffold('edit', $model);

        return [
            'fields'        => $this->getEditFields(),
            'buttons'       => $this->getEditButtons(),
            'validationMsg' => $this->getValidationMsg(),
            'configs'       => $this->getConfigs()
        ];
    }

    final function getDetailScaffold(): array
    {
        $this->scaffold('detail');
        return [
            'fields'        => $this->getDetailFields(),
            'buttons'       => $this->getDetailButtons(),
            'validationMsg' => $this->getValidationMsg(),
            'configs'       => $this->getConfigs()
        ];
    }

}
