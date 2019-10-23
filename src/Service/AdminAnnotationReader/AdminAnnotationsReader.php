<?php

declare(strict_types=1);

namespace App\Service\AdminAnnotationReader;

use App\Annotation\SidebarGroup;
use App\Annotation\SidebarMenu;
use App\Service\AdminAnnotationReader\Struct\Leaf;
use App\Service\AdminAnnotationReader\Struct\Tree;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouterInterface;
use ReflectionClass;
use ReflectionMethod;
use function array_filter;
use function explode;
use function strpos;

class AdminAnnotationsReader
{
    private Reader $reader;
    private RouterInterface $router;

    /** @var Route[]|null */
    private ?array $cachedRoutes = null;

    public function __construct(RouterInterface $router, Reader $reader)
    {
        $this->reader = $reader;
        $this->router = $router;
    }

    public function getTree(): Tree
    {
        $tree = new Tree();
        foreach ($this->getAdminRoutes() as $route) {
            if ($sidebar = $this->getSidebarAnnotation($route)) {
                $group = $this->getSidebarGroup($route);
                $tree->addLeaf(new Leaf($route, $sidebar, $group));
            }
        }

        return $tree;
    }

    private function getSidebarAnnotation(Route $route): ?SidebarMenu
    {
        $controller = (string)$route->getDefault('_controller');
        [$controller, $method] = explode('::', $controller);
        $method = new ReflectionMethod($controller, $method);
        /** @var SidebarMenu|null $sidebarMenuAnnotation */
        $sidebarMenuAnnotation = $this->reader->getMethodAnnotation($method, SidebarMenu::class);

        return $sidebarMenuAnnotation;
    }

    /**
     * @return Route[]
     */
    private function getAdminRoutes(): array
    {
        if (null === $this->cachedRoutes) {
            $routes = $this->router->getRouteCollection()->all();
            $this->cachedRoutes = array_filter($routes, fn (Route $route) => strpos($route->getPath(), '/admin') === 0);
        }

        return $this->cachedRoutes;
    }

    private function getSidebarGroup(Route $route): ?SidebarGroup
    {
        $controller = (string)$route->getDefault('_controller');
        [$controller, $method] = explode('::', $controller);
        $method = new ReflectionMethod($controller, $method);
        /** @var SidebarGroup|null $annotation */
        $annotation = $this->reader->getMethodAnnotation($method, SidebarGroup::class);
        if ($annotation) {
            return $annotation;
        }
        /** @psalm-suppress ArgumentTypeCoercion $rc */
        $rc = new ReflectionClass($controller);
        /** @var SidebarGroup|null $annotation */
        $annotation = $this->reader->getClassAnnotation($rc, SidebarGroup::class);

        return $annotation;
    }
}
