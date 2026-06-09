<?php

namespace App\Infrastructure\Helpers;

class MenuItem
{
    private $name;
    private $title;
    private $link;
    private $icon;
    private $weight;
    private $badge;
    private $access = true;
    private $children = [];

    public static function name(string $name): MenuItem
    {
        $obj = new static;
        $obj->name = $name;
        $obj->title = $name;
        return $obj;
    }

    public function title(string $title): MenuItem
    {
        $this->title = $title;
        return $this;
    }

    public function link(string $link): MenuItem
    {
        $this->link = $link;
        return $this;
    }

    public function icon(string $icon): MenuItem
    {
        $this->icon = $icon;
        return $this;
    }

    public function badge(string|int $badge): MenuItem
    {
        $this->badge = $badge;
        return $this;
    }

    public function weight(int $weight): MenuItem
    {
        $this->weight = $weight;
        return $this;
    }

    public function access(bool $access): MenuItem
    {
        $this->access = $access;
        return $this;
    }

    public function children(... $children): MenuItem
    {
        foreach ($children as $child) {
            $this->children[] = $child;
        }
        return $this;
    }

    public function getAccess(): bool
    {
        // Check if children has access
        if ($this->children) {
            foreach ($this->children as $child) {
                if ($child->getAccess()) {
                    return true;
                }
            }
            return false;
        } else {
            return $this->access;
        }
    }

    private function getChildren()
    {
        $children = [];
        foreach ($this->children as $child) {
            if ($child->getAccess()) {
                $children[] = $child->get();
            }
        }
        return $children;
    }

    public function get(): array
    {
        return [
            'name' => $this->name,
            'title' => $this->title,
            'link' => $this->link,
            'icon' => $this->icon,
            'weight' => $this->weight,
            'badge' => $this->badge,
            'access' => $this->access,
            'children' => $this->getChildren()
        ];
    }
}
