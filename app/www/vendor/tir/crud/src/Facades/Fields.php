<?php

namespace Tir\Crud\Facades;

/**
 * Fields Facade - Centralized field access
 *
 * This facade provides a single point of access to all field types,
 * making it easier to manage namespace changes in the future.
 *
 * Usage:
 * use Tir\Crud\Facades\Fields;
 *
 * Fields::text('name')
 * Fields::password('password')
 * Fields::select('status')
 */
class Fields
{
    /**
     * Create a Text field
     */
    public static function text(string $name): \Tir\Crud\Support\Scaffold\Fields\Text
    {
        return \Tir\Crud\Support\Scaffold\Fields\Text::make($name);
    }

    /**
     * Create a TextArea field
     */
    public static function textArea(string $name): \Tir\Crud\Support\Scaffold\Fields\TextArea
    {
        return \Tir\Crud\Support\Scaffold\Fields\TextArea::make($name);
    }

    /**
     * Create a Password field
     */
    public static function password(string $name): \Tir\Crud\Support\Scaffold\Fields\Password
    {
        return \Tir\Crud\Support\Scaffold\Fields\Password::make($name);
    }

    /**
     * Create a Number field
     */
    public static function number(string $name): \Tir\Crud\Support\Scaffold\Fields\Number
    {
        return \Tir\Crud\Support\Scaffold\Fields\Number::make($name);
    }

    /**
     * Create a Select field
     */
    public static function select(string $name): \Tir\Crud\Support\Scaffold\Fields\Select
    {
        return \Tir\Crud\Support\Scaffold\Fields\Select::make($name);
    }

    /**
     * Create a CheckBox field
     */
    public static function checkBox(string $name): \Tir\Crud\Support\Scaffold\Fields\CheckBox
    {
        return \Tir\Crud\Support\Scaffold\Fields\CheckBox::make($name);
    }

    /**
     * Create a SwitchBox field
     */
    public static function switchBox(string $name): \Tir\Crud\Support\Scaffold\Fields\SwitchBox
    {
        return \Tir\Crud\Support\Scaffold\Fields\SwitchBox::make($name);
    }

    /**
     * Create a DatePicker field
     */
    public static function datePicker(string $name): \Tir\Crud\Support\Scaffold\Fields\DatePicker
    {
        return \Tir\Crud\Support\Scaffold\Fields\DatePicker::make($name);
    }

    /**
     * Create a FileUploader field
     */
    public static function fileUploader(string $name): \Tir\Crud\Support\Scaffold\Fields\FileUploader
    {
        return \Tir\Crud\Support\Scaffold\Fields\FileUploader::make($name);
    }

    /**
     * Create an Additional (JSON) field
     */
    public static function additional(string $name): \Tir\Crud\Support\Scaffold\Fields\Additional
    {
        return \Tir\Crud\Support\Scaffold\Fields\Additional::make($name);
    }

    /**
     * Create a ColorPicker field
     */
    public static function colorPicker(string $name): \Tir\Crud\Support\Scaffold\Fields\ColorPicker
    {
        return \Tir\Crud\Support\Scaffold\Fields\ColorPicker::make($name);
    }

    /**
     * Create an Editor field
     */
    public static function editor(string $name): \Tir\Crud\Support\Scaffold\Fields\Editor
    {
        return \Tir\Crud\Support\Scaffold\Fields\Editor::make($name);
    }

    /**
     * Create a Radio field
     */
    public static function radio(string $name): \Tir\Crud\Support\Scaffold\Fields\Radio
    {
        return \Tir\Crud\Support\Scaffold\Fields\Radio::make($name);
    }

    /**
     * Create a Slug field
     */
    public static function slug(string $name): \Tir\Crud\Support\Scaffold\Fields\Slug
    {
        return \Tir\Crud\Support\Scaffold\Fields\Slug::make($name);
    }

    /**
     * Create a Button field
     */
    public static function button(string $name): \Tir\Crud\Support\Scaffold\Fields\Button
    {
        return \Tir\Crud\Support\Scaffold\Fields\Button::make($name);
    }

    /**
     * Create a Link field
     */
    public static function link(string $name): \Tir\Crud\Support\Scaffold\Fields\Link
    {
        return \Tir\Crud\Support\Scaffold\Fields\Link::make($name);
    }

    /**
     * Create a Custom field
     */
    public static function custom(string $name): \Tir\Crud\Support\Scaffold\Fields\Custom
    {
        return \Tir\Crud\Support\Scaffold\Fields\Custom::make($name);
    }

    /**
     * Magic method to handle dynamic field creation
     *
     * This allows for future field types without updating the facade
     */
    public static function __callStatic(string $name, array $arguments)
    {
        $fieldName = ucfirst($name);
        $className = "\\Tir\\Crud\\Support\\Scaffold\\Fields\\{$fieldName}";

        if (class_exists($className)) {
            return $className::make(...$arguments);
        }

        throw new \BadMethodCallException("Field type '{$name}' not found.");
    }
}
