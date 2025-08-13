<?php

namespace Tir\Crud\Support\Scaffold;

use Tir\Crud\Support\Scaffold\Actions;
use Tir\Crud\Support\Scaffold\Fields\Button;
use Tir\Crud\Support\Scaffold\Traits\Scaffolder;
use Tir\Crud\Support\Scaffold\Traits\RulesHelper;
use Tir\Crud\Support\Scaffold\Traits\FieldImports;
use Tir\Crud\Support\Scaffold\Traits\FieldsHelper;
use Tir\Crud\Support\Scaffold\Traits\ButtonsHelper;
use Tir\Crud\Support\Scaffold\Traits\ModelIntegration;

abstract class BaseScaffolder
{
    use FieldsHelper;
    use ButtonsHelper;
    use RulesHelper;
    use FieldImports;
    use ModelIntegration;

    private string $moduleTitle;
    private string $moduleName;

    public bool $isScaffolded = false;
    private $actions = [];
    private $fieldsHandler = null;

    protected abstract function setModuleName(): string;

    protected abstract function setFields(): array;

    protected abstract function setModel(): string;



    public function __construct()
    {
        // Initialize the scaffolder with the model and module name

        $this->moduleName = $this->setModuleName();
        $this->moduleTitle = $this->setModuleTitle();
        $this->actions = $this->setActions();

        $this->currentModel = $this->model();

    }

    protected function setButtons(): array
    {
        return [
            Button::make('back')->action('Cancel'),
            Button::make('submit')->display('panel.submit')->action('Submit')->hideFromDetail(),
        ];
    }

    protected function setModuleTitle(): string
    {
        return $this->moduleName;
    }

    /**
     * Configure which actions are available for this resource
     *
     * Override this method to customize available actions using the type-safe ActionType enum.
     *
     * @return array<string, bool> Actions configuration
     *
     * @example return Actions::all();                                    // All actions enabled
     * @example return Actions::basic();                                  // Basic CRUD without soft deletes
     * @example return Actions::readOnly();                               // Only index and show
     * @example return Actions::only(ActionType::INDEX, ActionType::SHOW); // Specific actions only
     * @example return Actions::except(ActionType::DESTROY);              // All except specific actions
     */
    protected function setActions(): array
    {
        // Default: all actions enabled
        return Actions::all();
    }




    public function scaffold($page = '', $model = null): static
    {
        if ($this->isScaffolded) {
            return $this;
        }

        $this->currentModel = $model;

        $this->fieldsHandler = new FieldsHandler($this->setFields(), $page, $model);


        $this->addButtonsToScaffold();
        $this->isScaffolded = true;
        return $this;
    }



    //Getters functions:
    public final function getModuleName(): mixed
    {
        // Return the current model instance
        if (isset($this->moduleName)) {
            return $this->moduleName;
        }else{
            return $this->setModuleName();
        }
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


    final function getIndexFields(): array
    {
        $this->scaffold('index');
        return $this->fieldsHandler->getIndexFields();
    }
    final function getCreateFields(): array
    {
        $this->scaffold('create');
        return $this->fieldsHandler->getCreateFields();
    }
    final function getEditFields(): array
    {
        $this->scaffold('edit');
        return $this->fieldsHandler->getEditFields();
    }

    final function getDetailFields(): array
    {
        $this->scaffold('detail');
        return $this->fieldsHandler->getDetailFields();
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

    final function getDetailScaffold($model): array
    {
        $this->scaffold('detail', $model);
        return [
            'fields'        => $this->getDetailFields(),
            'buttons'       => $this->getDetailButtons(),
            'validationMsg' => $this->getValidationMsg(),
            'configs'       => $this->getConfigs()
        ];
    }


}
