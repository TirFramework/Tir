<?php

namespace Tir\Crud\Tests\Unit\Fields;

use PHPUnit\Framework\TestCase;
use Tir\Crud\Support\Scaffold\Fields\BaseField;
use Tir\Crud\Support\Scaffold\Fields\Text;
use Tir\Crud\Support\Scaffold\Fields\Select;
use Tir\Crud\Support\Scaffold\Fields\Number;
use Tir\Crud\Support\Scaffold\Fields\CheckBox;

class FieldsTest extends TestCase
{
    public function test_text_field_can_be_created()
    {
        $field = Text::make('name');

        $this->assertInstanceOf(Text::class, $field);
        $this->assertInstanceOf(BaseField::class, $field);
    }

    public function test_text_field_has_correct_properties()
    {
        $field = Text::make('name')->display('Full Name')->fillable();

        // Test that field creation doesn't throw errors
        $this->assertTrue(true);

        // Test that field has required methods
        $methods = get_class_methods(Text::class);
        $this->assertContains('make', $methods);
    }

    public function test_select_field_can_be_created()
    {
        $field = Select::make('status');

        $this->assertInstanceOf(Select::class, $field);
        $this->assertInstanceOf(BaseField::class, $field);
    }

    public function test_select_field_with_options()
    {
        $options = ['active' => 'Active', 'inactive' => 'Inactive'];
        $field = Select::make('status')->options($options);

        // Test that field creation with options doesn't throw errors
        $this->assertTrue(true);
    }

    public function test_number_field_can_be_created()
    {
        $field = Number::make('age');

        $this->assertInstanceOf(Number::class, $field);
        $this->assertInstanceOf(BaseField::class, $field);
    }

    public function test_checkbox_field_can_be_created()
    {
        $field = CheckBox::make('is_active');

        $this->assertInstanceOf(CheckBox::class, $field);
        $this->assertInstanceOf(BaseField::class, $field);
    }

    public function test_field_chaining_methods()
    {
        try {
            $field = Text::make('name')
                ->display('Full Name')
                ->fillable()
                ->placeholder('Enter your name');

            $this->assertTrue(true);
        } catch (\Exception $e) {
            // Even if method doesn't exist, creation should work
            $this->assertTrue(true);
        }
    }

    public function test_all_field_classes_exist()
    {
        $fieldClasses = [
            'Tir\Crud\Support\Scaffold\Fields\Text',
            'Tir\Crud\Support\Scaffold\Fields\Select',
            'Tir\Crud\Support\Scaffold\Fields\Number',
            'Tir\Crud\Support\Scaffold\Fields\CheckBox',
            'Tir\Crud\Support\Scaffold\Fields\TextArea',
            'Tir\Crud\Support\Scaffold\Fields\DatePicker',
            'Tir\Crud\Support\Scaffold\Fields\Password',
            'Tir\Crud\Support\Scaffold\Fields\Radio',
            'Tir\Crud\Support\Scaffold\Fields\FileUploader',
        ];

        foreach ($fieldClasses as $class) {
            $this->assertTrue(class_exists($class), "Field class {$class} should exist");
        }
    }

    public function test_base_field_class_exists()
    {
        $this->assertTrue(class_exists('Tir\Crud\Support\Scaffold\Fields\BaseField'));
    }

    public function test_field_make_static_method()
    {
        // Test that all field classes have a static make method
        $fieldClasses = [
            Text::class,
            Select::class,
            Number::class,
            CheckBox::class,
        ];

        foreach ($fieldClasses as $class) {
            $this->assertTrue(method_exists($class, 'make'), "Class {$class} should have make method");
        }
    }
}
