<?php

declare(strict_types=1);

namespace App\Annotation;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;

/**
 * @Annotation
 */
class FilterConfig implements ConfigurationInterface
{
    /** @psalm-suppress PropertyNotSetInConstructor */
    public string $name;

    public function getAliasName(): string
    {
        return 'filter_name';
    }

    public function allowArray(): bool
    {
        return false;
    }
}
