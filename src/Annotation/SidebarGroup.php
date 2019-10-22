<?php

declare(strict_types=1);

namespace App\Annotation;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationAnnotation;

/**
 * @Annotation
 */
class SidebarGroup extends ConfigurationAnnotation
{
    /** @psalm-suppress PropertyNotSetInConstructor */
    private string $name;

    public function getAliasName(): string
    {
        return 'sidebar_group';
    }

    public function allowArray(): bool
    {
        return false;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

}
