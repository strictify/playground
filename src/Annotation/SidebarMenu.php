<?php

declare(strict_types=1);

namespace App\Annotation;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationAnnotation;

/**
 * @Annotation
 */
class SidebarMenu extends ConfigurationAnnotation
{
    /** @psalm-suppress PropertyNotSetInConstructor */
    private ?string $group;

    /** @psalm-suppress PropertyNotSetInConstructor */
    private string $label;

    /** @psalm-suppress PropertyNotSetInConstructor */
    private string $icon;

    public function getAliasName(): string
    {
        return 'sidebar_menu';
    }

    public function allowArray(): bool
    {
        return false;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): void
    {
        $this->icon = $icon;
    }

    public function getGroup(): ?string
    {
        return $this->group;
    }

    public function setGroup(?string $group): void
    {
        $this->group = $group;
    }
}
