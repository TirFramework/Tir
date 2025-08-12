<?php

namespace Tir\Crud\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tir\Crud\Support\Hooks\IndexDataHooks;
use Tir\Crud\Support\Hooks\StoreHooks;
use Tir\Crud\Support\Hooks\ShowHooks;
use Tir\Crud\Support\Hooks\DestroyHooks;

class HooksTest extends TestCase
{
    public function test_index_hooks_trait_has_required_methods()
    {
        // Test that trait exists and can be used
        $this->assertTrue(trait_exists('Tir\Crud\Support\Hooks\IndexDataHooks'));

        $class = new class {
            use IndexDataHooks;
            public function getTraitMethods() {
                return get_class_methods($this);
            }
        };

        $methods = $class->getTraitMethods();
        $this->assertContains('onInitQuery', $methods);
        $this->assertContains('onIndexResponse', $methods);
        $this->assertContains('onSelect', $methods);
        $this->assertContains('onFilter', $methods);
        $this->assertContains('onSort', $methods);
    }

    public function test_store_hooks_trait_has_required_methods()
    {
        $this->assertTrue(trait_exists('Tir\Crud\Support\Hooks\StoreHooks'));

        $class = new class {
            use StoreHooks;
            public function getTraitMethods() {
                return get_class_methods($this);
            }
        };

        $methods = $class->getTraitMethods();
        $this->assertContains('onStore', $methods);
        $this->assertContains('onStoreResponse', $methods);
        $this->assertContains('onSaveModel', $methods);
        $this->assertContains('onFillModelForStore', $methods);
        $this->assertContains('onStoreCompleted', $methods);
    }

    public function test_show_hooks_trait_has_required_methods()
    {
        $this->assertTrue(trait_exists('Tir\Crud\Support\Hooks\ShowHooks'));

        $class = new class {
            use ShowHooks;
            public function getTraitMethods() {
                return get_class_methods($this);
            }
        };

        $methods = $class->getTraitMethods();
        $this->assertContains('onShow', $methods);
        $this->assertContains('onShowResponse', $methods);
    }

    public function test_destroy_hooks_trait_has_required_methods()
    {
        $this->assertTrue(trait_exists('Tir\Crud\Support\Hooks\DestroyHooks'));

        $class = new class {
            use DestroyHooks;
            public function getTraitMethods() {
                return get_class_methods($this);
            }
        };

        $methods = $class->getTraitMethods();
        $this->assertContains('onDestroy', $methods);
        $this->assertContains('onDestroyResponse', $methods);
    }

    public function test_hooks_methods_exist()
    {
        // Test that hook traits can be included in classes
        $this->assertTrue(trait_exists('Tir\Crud\Support\Hooks\IndexDataHooks'));
        $this->assertTrue(trait_exists('Tir\Crud\Support\Hooks\StoreHooks'));
        $this->assertTrue(trait_exists('Tir\Crud\Support\Hooks\ShowHooks'));
        $this->assertTrue(trait_exists('Tir\Crud\Support\Hooks\DestroyHooks'));
        $this->assertTrue(trait_exists('Tir\Crud\Support\Hooks\CreateHooks'));
        $this->assertTrue(trait_exists('Tir\Crud\Support\Hooks\EditHooks'));
        $this->assertTrue(trait_exists('Tir\Crud\Support\Hooks\UpdateHooks'));
        $this->assertTrue(trait_exists('Tir\Crud\Support\Hooks\RestoreHooks'));
        $this->assertTrue(trait_exists('Tir\Crud\Support\Hooks\ForceDeleteHooks'));
        $this->assertTrue(trait_exists('Tir\Crud\Support\Hooks\TrashHooks'));
    }
}
