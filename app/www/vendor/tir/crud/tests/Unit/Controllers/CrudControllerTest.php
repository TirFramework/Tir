<?php

namespace Tir\Crud\Tests\Unit\Controllers;

use PHPUnit\Framework\TestCase;
use Tir\Crud\Controllers\Traits\Crud;

class CrudControllerTest extends TestCase
{
    public function test_crud_controller_class_exists()
    {
        $this->assertTrue(class_exists('Tir\Crud\Controllers\CrudController'));
    }

    public function test_crud_trait_exists()
    {
        $this->assertTrue(trait_exists('Tir\Crud\Controllers\Traits\Crud'));
    }

    public function test_crud_trait_has_required_methods()
    {
        $methods = get_class_methods(Crud::class);

        $expectedMethods = [
            'index',
            'create',
            'store',
            'show',
            'edit',
            'destroy'
        ];

        foreach ($expectedMethods as $method) {
            $this->assertContains($method, $methods, "Crud trait should have method: {$method}");
        }
    }

    public function test_all_crud_traits_exist()
    {
        $traitClasses = [
            'Tir\Crud\Controllers\Traits\Crud',
            'Tir\Crud\Controllers\Traits\CrudInit',
            'Tir\Crud\Controllers\Traits\Index',
            'Tir\Crud\Controllers\Traits\Create',
            'Tir\Crud\Controllers\Traits\Store',
            'Tir\Crud\Controllers\Traits\Show',
            'Tir\Crud\Controllers\Traits\Edit',
            'Tir\Crud\Controllers\Traits\Destroy',
        ];

        foreach ($traitClasses as $trait) {
            $this->assertTrue(trait_exists($trait), "CRUD trait should exist: {$trait}");
        }
    }

    public function test_crud_trait_uses_other_traits()
    {
        $reflection = new \ReflectionClass(Crud::class);
        $traits = $reflection->getTraitNames();

        $expectedTraits = [
            'Tir\Crud\Controllers\Traits\CrudInit',
            'Tir\Crud\Controllers\Traits\Index',
            'Tir\Crud\Controllers\Traits\Create',
            'Tir\Crud\Controllers\Traits\Store',
            'Tir\Crud\Controllers\Traits\Show',
            'Tir\Crud\Controllers\Traits\Edit',
            'Tir\Crud\Controllers\Traits\Destroy',
        ];

        foreach ($expectedTraits as $trait) {
            $this->assertContains($trait, $traits, "Crud trait should use: {$trait}");
        }
    }
}
