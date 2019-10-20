<?php

declare(strict_types=1);

namespace App\EventSubscriber\Admin;

use KevinPapst\AdminLTEBundle\Event\SidebarMenuEvent;
use KevinPapst\AdminLTEBundle\Model\MenuItemModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SidebarNavigationSubscriber implements EventSubscriberInterface
{
    /**
     * @see onSetupMenu
     */
    public static function getSubscribedEvents(): array
    {
        return [
            SidebarMenuEvent::class => ['onSetupMenu', 100],
        ];
    }

    public function onSetupMenu(SidebarMenuEvent $event): void
    {
        $menu = new MenuItemModel('admin_users', 'Users', 'admin_users', [], 'fas fa-tachometer-alt');
        $event->addItem($menu);
    }
}
