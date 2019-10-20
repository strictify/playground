<?php

declare(strict_types=1);

namespace App\Admin\Registry;

interface AdminInterface
{
    public function supports(string $segment): bool;

    public function getPaginator(): iterable;
}
