<?php
namespace App\Helpers;

class MenuGenerator {
    private $menuItems = [];

    public function __construct(array $menuData) {
        $this->menuItems = $menuData;
    }

    public function generateMenu() {
        $menu = [];
        foreach ($this->menuItems as $menuItem) {
            if ($menuItem['parent_id'] == 0) {
                $menu[] = $this->generateMenuItem($menuItem);
            }
        }
        return $menu;
    }

    private function generateMenuItem($menuItem) {
        $item = [
            'title' => $menuItem['title'],
            'url' => $this->generateUrl($menuItem),
            'children' => []
        ];
        $children = $this->getChildren($menuItem['id']);
        if ($children) {
            foreach ($children as $child) {
                $item['children'][] = $this->generateMenuItem($child);
            }
        }
        return $item;
    }

    private function generateUrl($menuItem) {
        $url = $menuItem['slug'];
        $parent = $this->getParent($menuItem['parent_id']);
        while ($parent) {
            $url = $parent['slug'] . "/" . $url;
            $parent = $this->getParent($parent['parent_id']);
        }
        return $url;
    }

    private function getChildren($parentId) {
        $children = [];
        foreach ($this->menuItems as $menuItem) {
            if ($menuItem['parent_id'] == $parentId) {
                $children[] = $menuItem;
            }
        }
        return $children;
    }

    private function getParent($parentId) {
        foreach ($this->menuItems as $menuItem) {
            if ($menuItem['id'] == $parentId) {
                return $menuItem;
            }
        }
        return null;
    }
}

