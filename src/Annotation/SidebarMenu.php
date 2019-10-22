<?php

declare(strict_types=1);

namespace App\Annotation;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;

/**
 * @Annotation
 */
class SidebarMenu implements ConfigurationInterface
{
    /** @psalm-suppress PropertyNotSetInConstructor */
    public ?string $group = null;

    /** @psalm-suppress PropertyNotSetInConstructor */
    public string $label;

    /** @psalm-suppress PropertyNotSetInConstructor */
    public string $icon;

    public function getAliasName(): string
    {
        return 'sidebar_menu';
    }

    public function allowArray(): bool
    {
        return false;
    }
}
