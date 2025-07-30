<?php

namespace Tir\Crud\Support\Module;

use Illuminate\Support\Arr;

class AdminMenu
{
    public $nav = [];
    public $item = [];
    public $slug;


    public function title(string $name)
    {
        $this->clear();
        $this->item['title'] = trans($name);
        return $this;
    }

    private function clear()
    {
        $this->item = [];
    }

    public function link($link)
    {
        $this->item['link'] = $link;
        return $this;
    }

    public function route($route)
    {
        $this->item['link'] = str_replace(request()->root() . '/', '', route($route));
        return $this;
    }

    public function weight($weight)
    {
        $this->item['weight'] = $weight;
        return $this;
    }

    public function icon($icon)
    {
        $this->item['icon'] = $icon;
        return $this;
    }

    public function item($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    public function add()
    {
        $this->slug = str_replace('.', '.sub.', $this->slug);

        if (!Arr::has($this->nav, $this->slug)) {
            Arr::set($this->nav, $this->slug, $this->item);
        }
        return $this;

    }


}