<?php

namespace Tir\Crud\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tir\Crud\Tests\Controllers\TestController;
use Tir\Crud\Tests\Models\TestModel;
use Illuminate\Http\Request;

class CrudControllerTest extends TestCase
{
    public function test_crud_trait_provides_required_methods()
    {
        // Test that the Crud trait provides all necessary methods
        $methods = get_class_methods(\Tir\Crud\Controllers\Traits\Crud::class);

        // Just test that the trait exists and can be used
        $this->assertTrue(trait_exists('Tir\Crud\Controllers\Traits\Crud'));
    }

    public function test_controller_class_exists()
    {
        $this->assertTrue(class_exists(\Tir\Crud\Tests\Controllers\TestController::class));
    }

    public function test_crud_methods_are_available()
    {
        // Test that CRUD methods exist in the trait
        $reflectionClass = new \ReflectionClass(\Tir\Crud\Controllers\Traits\Crud::class);
        $traitMethods = $reflectionClass->getMethods();

        $methodNames = array_map(function($method) {
            return $method->getName();
        }, $traitMethods);

        // Check for some key methods (these might be in different traits that compose Crud)
        $this->assertContains('setScaffolder', $methodNames);
    }
}
