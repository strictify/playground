<?php

declare(strict_types=1);

namespace App\EventSubscriber\Admin;

use App\Service\AdminAnnotationReader\AdminAnnotationsReader;
use App\Service\AdminAnnotationReader\Struct\Leaf;
use KevinPapst\AdminLTEBundle\Event\SidebarMenuEvent;
use KevinPapst\AdminLTEBundle\Model\MenuItemModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use function preg_replace;
use function strpos;
use function strtolower;
use function trim;
use function ucfirst;

class SidebarMenuBuilder implements EventSubscriberInterface
{
    private RequestStack $requestStack;
    private AdminAnnotationsReader $adminAnnotationsReader;

    public function __construct(RequestStack $requestStack, AdminAnnotationsReader $adminAnnotationsReader)
    {
        $this->requestStack = $requestStack;
        $this->adminAnnotationsReader = $adminAnnotationsReader;
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

        $tree = $this->adminAnnotationsReader->getTree();
        /** @var Leaf[] $nodes */
        foreach ($tree->getGrouped() as $group) {
            $menu = new MenuItemModel(
                $group->name,
                $this->humanize($group->name),
                '',
                [],
                $group->icon,
            );
            foreach ($group->leaves as $node) {
                $menu->addChild(
                    new MenuItemModel(
                        $node->menu->label,
                        $node->menu->label,
                        $node->route->getPath(),
                        [],
                        $node->menu->icon
                    )
                );
            }
            $event->addItem($menu);
        }
    }

    private function humanize(string $text): string
    {
        return ucfirst(strtolower(trim(preg_replace(['/([A-Z])/', '/[_\s]+/'], ['_$1', ' '], $text))));
    }
}
