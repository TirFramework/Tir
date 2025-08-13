<?php

namespace Tir\Crud\Support\Scaffold\Traits;

trait ButtonsHelper
{
    private array $fields = [];
    private array $buttons = [];

    final function getIndexButtons(): array
    {
        $buttons = [];
        foreach ($this->getButtons() as $button) {
            if ($button->showOnIndex) {
                $buttons[] = $button;
            }
        }
        return $buttons;
    }

    final function getCreateButtons(): array
    {
        $buttons = [];
        foreach ($this->getButtons() as $button) {
            if ($button->showOnCreating) {
                $buttons[] = $button;
            }
        }
        return $buttons;
    }

    final function getDetailButtons(): array
    {
        $buttons = [];
        foreach ($this->getButtons() as $button) {
            if ($button->showOnDetail) {
                $buttons[] = $button;
            }
        }
        return $buttons;
    }

    final function getEditButtons(): array
    {
        $buttons = [];
        foreach ($this->getButtons() as $button) {
            if ($button->showOnEditing) {
                $buttons[] = $button;
            }
        }
        return $buttons;
    }

    private function addButtonsToScaffold(): void
    {
        foreach ($this->setButtons() as $button) {
            $this->buttons[] = $button->get(null);
        }
    }

    final function getButtons()
    {
        return $this->buttons;
    }

}
