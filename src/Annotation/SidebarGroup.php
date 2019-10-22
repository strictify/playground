<?php

declare(strict_types=1);

namespace App\Annotation;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;

/**
 * @Annotation
 */
class SidebarGroup implements ConfigurationInterface
{
    /** @psalm-suppress PropertyNotSetInConstructor */
    public string $name;

    public function getAliasName(): string
    {
        return 'sidebar_group';
    }

    public function allowArray(): bool
    {
        return false;
    }
}
