<?php

declare(strict_types=1);

namespace App\EventSubscriber\Admin;

use App\Annotation\SidebarGroup;
use App\Annotation\SidebarMenu;
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

    /** @var Route[]|null  */
    private ?array $cachedRoutes = null;

    public function __construct(RouterInterface $router, Reader $reader)
    {
        $this->reader = $reader;
        $this->router = $router;
    }

    /**
     * @return Route[]
     */
    public function getAdminRoutes(): array
    {
        if (null === $this->cachedRoutes) {
            $routes = $this->router->getRouteCollection()->all();
            $this->cachedRoutes = array_filter($routes, fn (Route $route) => strpos($route->getPath(), '/admin') === 0);
        }

        return $this->cachedRoutes;
    }

    public function getSidebarAnnotation(Route $route): ?SidebarMenu
    {
        $controller = (string)$route->getDefault('_controller');
        [$controller, $method] = explode('::', $controller);
        $method = new ReflectionMethod($controller, $method);
        /** @var SidebarMenu|null $sidebarMenuAnnotation */
        $sidebarMenuAnnotation = $this->reader->getMethodAnnotation($method, SidebarMenu::class);

        return $sidebarMenuAnnotation;
    }

    /**
     * @return SidebarGroup[]|array<int, SidebarGroup>
     *
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress ArgumentTypeCoercion
     * @psalm-suppress LessSpecificReturnStatement
     */
    public function getSidebarGroups(): array
    {
        $groups = [];
        foreach ($this->getAdminRoutes() as $route) {
            $controller = (string)$route->getDefault('_controller');
            [$controller] = explode('::', $controller, 2);
            $rc = new ReflectionClass($controller);
            if ($annotation = $this->reader->getClassAnnotation($rc, SidebarGroup::class)) {
                $groups[] = $annotation;
            }
        }

        return $groups;
    }
}
