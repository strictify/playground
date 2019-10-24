<?php

declare(strict_types=1);

namespace App\Filter;

use Symfony\Component\Form\FormView;

interface FilterInterface
{
    /** @return mixed */
    public function get(string $name);

    public function getPage(): int;

    public function getFormView(): FormView;
}
