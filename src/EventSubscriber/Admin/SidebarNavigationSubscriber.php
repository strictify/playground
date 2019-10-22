<?php

declare(strict_types=1);

namespace App\EventSubscriber\Admin;

use App\Admin\Admin;
use KevinPapst\AdminLTEBundle\Event\SidebarMenuEvent;
use KevinPapst\AdminLTEBundle\Model\MenuItemModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use function preg_replace;
use function strtolower;
use function trim;
use function ucfirst;

class SidebarNavigationSubscriber implements EventSubscriberInterface
{
    private Admin $admin;

    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
    }

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
        foreach ($this->admin->getDefinitions() as $admin) {
            $segmentName = $admin->getLabel();
            $menu = new MenuItemModel(
                'admin_'. $segmentName,
                $this->humanize($segmentName),
                'admin_segment',
                ['segment' => $segmentName],
                'fas fa-tachometer-alt',
            );
            $event->addItem($menu);
        }

    }

    private function humanize(string $text): string
    {
        return ucfirst(strtolower(trim(preg_replace(['/([A-Z])/', '/[_\s]+/'], ['_$1', ' '], $text))));
    }
}
