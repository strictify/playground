<?php

declare(strict_types=1);

namespace App\Service\AdminAnnotationReader\Struct;

use App\Service\AdminAnnotationReader\Struct\Branch;
use App\Service\AdminAnnotationReader\Struct\Leaf;

class Tree
{
    /** @var Leaf[] */
    private array $leaves = [];

    public function addLeaf(Leaf $leaf): void
    {
        $this->leaves[] = $leaf;
    }

    /** @return Branch[] | iterable<string, Branch> */
    public function getGrouped(): iterable
    {
        /** @var Branch[] $branches */
        $branches = [];
        foreach ($this->leaves as $leaf) {
            $group = $leaf->group;
            if (!$group) {
                continue;
            }
            $name = $group->name;
            if (!isset($branches[$name])) {
                $branches[$name] = new Branch($group->name, $group->icon);
            }
            $branches[$name]->addLeaf($leaf);
        }

        return $branches;
    }

}
