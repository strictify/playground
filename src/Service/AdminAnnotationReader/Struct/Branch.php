<?php

declare(strict_types=1);

namespace App\Service\AdminAnnotationReader\Struct;

class Branch
{
    /** @var Leaf[] */
    public array $leaves = [];
    public string $name;
    public string $icon;

    public function __construct(string $name, string $icon)
    {
        $this->name = $name;
        $this->icon = $icon;
    }


    public function addLeaf(Leaf $leaf): void
    {
        $this->leaves[] = $leaf;
    }
}
