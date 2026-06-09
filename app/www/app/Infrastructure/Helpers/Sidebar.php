<?php

namespace App\Infrastructure\Helpers;

class Sidebar
{
    public static Sidebar $obj;
    public static array $menus = [];

    public static function init(): Sidebar
    {
        if (!isset(self::$obj)) {
            self::$obj = new Sidebar();
        }
        return self::$obj;
    }

    public static function add(...$menuItem): Sidebar
    {
        foreach ($menuItem as $item) {
            if ($item->getAccess()) {
                Sidebar::$menus[] = $item->get();
            }
        }
        return new static;
    }

    public static function get(): array
    {
        return Sidebar::$menus;
    }
}
