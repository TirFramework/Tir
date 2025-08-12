<?php

namespace Tir\Crud\Tests\Unit\Scaffold;

use PHPUnit\Framework\TestCase;
use Tir\Crud\Support\Scaffold\BaseScaffolder;
use Tir\Crud\Support\Scaffold\Fields\Text;
use Tir\Crud\Support\Scaffold\Fields\Button;

class BaseScaffolderTest extends TestCase
{
    public function test_base_scaffolder_class_exists()
    {
        $this->assertTrue(class_exists('Tir\Crud\Support\Scaffold\BaseScaffolder'));
    }

    public function test_base_scaffolder_is_abstract()
    {
        $reflection = new \ReflectionClass(BaseScaffolder::class);
        $this->assertTrue($reflection->isAbstract());
    }

    public function test_base_scaffolder_has_required_traits()
    {
        $reflection = new \ReflectionClass(BaseScaffolder::class);
        $traits = $reflection->getTraitNames();

        $expectedTraits = [
            'Tir\Crud\Support\Scaffold\FieldsHelper',
            'Tir\Crud\Support\Scaffold\ButtonsHelper',
            'Tir\Crud\Support\Scaffold\RulesHelper',
            'Tir\Crud\Support\Scaffold\FieldImports',
        ];

        foreach ($expectedTraits as $trait) {
            $this->assertContains($trait, $traits, "BaseScaffolder should use trait: {$trait}");
        }
    }

    public function test_base_scaffolder_has_abstract_methods()
    {
        $reflection = new \ReflectionClass(BaseScaffolder::class);
        $abstractMethods = [];

        foreach ($reflection->getMethods() as $method) {
            if ($method->isAbstract()) {
                $abstractMethods[] = $method->getName();
            }
        }

        $expectedAbstractMethods = ['setModuleName', 'setFields', 'setModel'];

        foreach ($expectedAbstractMethods as $method) {
            $this->assertContains($method, $abstractMethods, "BaseScaffolder should have abstract method: {$method}");
        }
    }

    public function test_concrete_scaffolder_can_be_created()
    {
        // Create a concrete implementation for testing
        $scaffolder = new class extends BaseScaffolder {
            protected function setModuleName(): string
            {
                return 'test';
            }

            protected function setFields(): array
            {
                return [
                    Text::make('name')->display('Name'),
                    Text::make('email')->display('Email'),
                ];
            }

            protected function setModel(): string
            {
                return 'App\Models\Test';
            }
        };

        $this->assertInstanceOf(BaseScaffolder::class, $scaffolder);
    }

    public function test_scaffolder_has_field_helper_methods()
    {
        $scaffolder = new class extends BaseScaffolder {
            protected function setModuleName(): string { return 'test'; }
            protected function setFields(): array { return []; }
            protected function setModel(): string { return 'App\Models\Test'; }

            public function testFieldHelper()
            {
                // Test that field helper methods are available
                return method_exists($this, 'text') &&
                       method_exists($this, 'select') &&
                       method_exists($this, 'number');
            }
        };

        $this->assertTrue($scaffolder->testFieldHelper());
    }

    public function test_scaffolder_button_methods()
    {
        $scaffolder = new class extends BaseScaffolder {
            protected function setModuleName(): string { return 'test'; }
            protected function setFields(): array { return []; }
            protected function setModel(): string { return 'App\Models\Test'; }

            public function testButtons()
            {
                return $this->setButtons();
            }
        };

        $buttons = $scaffolder->testButtons();
        $this->assertIsArray($buttons);
        $this->assertGreaterThan(0, count($buttons));
    }

    public function test_field_imports_trait_provides_shortcuts()
    {
        // Test that the FieldImports trait is used
        $reflection = new \ReflectionClass(BaseScaffolder::class);
        $traits = $reflection->getTraitNames();

        $this->assertContains('Tir\Crud\Support\Scaffold\FieldImports', $traits);
    }

    public function test_scaffolder_helper_traits_exist()
    {
        $helperTraits = [
            'Tir\Crud\Support\Scaffold\FieldsHelper',
            'Tir\Crud\Support\Scaffold\ButtonsHelper',
            'Tir\Crud\Support\Scaffold\RulesHelper',
            'Tir\Crud\Support\Scaffold\FieldImports',
        ];

        foreach ($helperTraits as $trait) {
            $this->assertTrue(trait_exists($trait), "Helper trait should exist: {$trait}");
        }
    }
}
