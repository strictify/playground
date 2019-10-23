<?php

declare(strict_types=1);

namespace App\Service\AdminAnnotationReader\Struct;

use App\Annotation\SidebarGroup;
use App\Annotation\SidebarMenu;
use Symfony\Component\Routing\Route;

class Leaf
{
    public Route $route;
    public SidebarMenu $menu;
    public ?SidebarGroup $group;

    public function __construct(Route $route, SidebarMenu $menu, ?SidebarGroup $group)
    {
        $this->route = $route;
        $this->menu = $menu;
        $this->group = $group;
    }
}
