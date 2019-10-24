<?php

declare(strict_types=1);

namespace App\Filter;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UsersFilter extends AbstractFilter
{
    protected function configureForm(FormBuilderInterface $formBuilder): void
    {
        $formBuilder->add('search', TextType::class, [
            'label' => false,
        ]);
    }
}
