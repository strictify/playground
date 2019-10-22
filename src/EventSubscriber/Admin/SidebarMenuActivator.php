<?php

declare(strict_types=1);

namespace App\EventSubscriber\Admin;

use App\Annotation\SidebarMenu;
use Doctrine\Common\Annotations\Reader;
use KevinPapst\AdminLTEBundle\Event\SidebarMenuEvent;
use KevinPapst\AdminLTEBundle\Model\MenuItemModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class SidebarMenuActivator implements EventSubscriberInterface
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

        $this->activateTarget($sidebarMenuAnnotation->getLabel(), $event->getItems());
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
