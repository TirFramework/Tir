<?php


namespace Tir\Crud\Support\Module;


class Modules
{
    public static Modules $obj;
    public static array $list = [];

    public static function init(): Modules
    {
        if (!isset(self::$obj)) {
            self::$obj = new Modules();
        }
        return self::$obj;
    }

    public static function register(Module $module)
    {
        static::init();
        array_push(Modules::$list, $module);
    }

    public static function list(): array
    {
        return Modules::$list;
    }

    public static function find(string $name)
    {
        foreach (Modules::$list as $module) {
            if ($module->name == $name) {
                return $module;
            }
        }
        return false;
    }

}