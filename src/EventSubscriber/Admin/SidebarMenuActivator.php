<?php

declare(strict_types=1);

namespace App\EventSubscriber\Admin;

use App\Annotation\SidebarMenu;
use KevinPapst\AdminLTEBundle\Event\SidebarMenuEvent;
use KevinPapst\AdminLTEBundle\Model\MenuItemModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SidebarMenuActivator implements EventSubscriberInterface
{
    /** @see buildSidebarMenu */
    public static function getSubscribedEvents(): array
    {
        return [
            SidebarMenuEvent::class => ['buildSidebarMenu', 95],
        ];
    }

    /**
     * @psalm-suppress UndefinedDocblockClass
     * @psalm-suppress ArgumentTypeCoercion
     */
    public function buildSidebarMenu(SidebarMenuEvent $event): void
    {
        $request = $event->getRequest();
        if (!$request) {
            return;
        }
        /** @var SidebarMenu|null $sidebarMenuAnnotation */
        $sidebarMenuAnnotation = $request->attributes->get('_sidebar_menu');
        if (!$sidebarMenuAnnotation) {
            return;
        }

        $this->activateTarget($sidebarMenuAnnotation->label, $event->getItems());
    }

    /**
     * @param MenuItemModel[] $items
     *
     * @psalm-suppress MixedArgumentTypeCoercion
     */
    private function activateTarget(string $target, array $items): void
    {
        foreach ($items as $item) {
            if ($item->hasChildren()) {
                $this->activateTarget($target, $item->getChildren());
            } elseif ($item->getIdentifier() === $target) {
                $item->setIsActive(true);
            }
        }
    }
}
