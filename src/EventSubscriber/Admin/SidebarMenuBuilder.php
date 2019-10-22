<?php

declare(strict_types=1);

namespace App\EventSubscriber\Admin;

use App\Annotation\SidebarGroup;
use App\Annotation\SidebarMenu;
use Doctrine\Common\Annotations\Reader;
use KevinPapst\AdminLTEBundle\Event\SidebarMenuEvent;
use KevinPapst\AdminLTEBundle\Model\MenuItemModel;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouterInterface;
use function explode;
use function preg_replace;
use function strpos;
use function strtolower;
use function trim;
use function ucfirst;

class SidebarMenuBuilder implements EventSubscriberInterface
{
    private RouterInterface $router;
    private Reader $reader;
    private RequestStack $requestStack;

    public function __construct(RouterInterface $router, Reader $reader, RequestStack $requestStack)
    {
        $this->router = $router;
        $this->reader = $reader;
        $this->requestStack = $requestStack;
    }

    /** @see buildSidebarMenu */
    public static function getSubscribedEvents(): array
    {
        return [
            SidebarMenuEvent::class => ['buildSidebarMenu', 100],
        ];
    }

    public function buildSidebarMenu(SidebarMenuEvent $event): void
    {
        $request = $this->requestStack->getMasterRequest();
        if (!$request) {
            return;
        }
        // only for /admin part of application
        $pathInfo = $request->getPathInfo();
        if (strpos($pathInfo, '/admin') !== 0) {
            return;
        }
        $routes = $this->getAdminRoutes();

        $groups = $this->getSidebarGroups($routes);
        foreach ($groups as $group) {
            dump($group);
        }

        foreach ($groups as $group) {

        }


        foreach ($routes as $route) {
            $annotation = $this->getSidebarAnnotation($route);
            if (!$annotation) {
                continue;
            }
            $label = $annotation->getLabel();
            $menu = new MenuItemModel(
                $label,
                $this->humanize($label),
                $route->getPath(),
                [],
                $annotation->getIcon(),
            );
            $event->addItem($menu);
        }
    }

    /**
     * @return Route[]
     */
    private function getAdminRoutes(): array
    {
        $routes = [];
        $collection = $this->router->getRouteCollection();
        foreach ($collection->all() as $route) {
            $path = $route->getPath();
            if (strpos($path, '/admin') === 0) {
                $routes[] = $route;
            }
        }

        return $routes;
    }

    /**
     * @param Route[] $routes
     *
     * @return SidebarGroup[]|array<int, Route>
     *
     *
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress ArgumentTypeCoercion
     * @psalm-suppress LessSpecificReturnStatement
     */
    private function getSidebarGroups(array $routes): array
    {
        $groups = [];
        foreach ($routes as $route) {
            $controller = (string)$route->getDefault('_controller');
            [$controller] = explode('::', $controller, 2);
            $rc = new ReflectionClass($controller);
            if ($annotation = $this->reader->getClassAnnotation($rc, SidebarGroup::class)) {
                $groups[] = $annotation;
            }
        }

        return $groups;
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

    private function humanize(string $text): string
    {
        return ucfirst(strtolower(trim(preg_replace(['/([A-Z])/', '/[_\s]+/'], ['_$1', ' '], $text))));
    }
}
