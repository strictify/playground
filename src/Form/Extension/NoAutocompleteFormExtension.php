<?php

declare(strict_types=1);

namespace App\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoAutocompleteFormExtension extends AbstractTypeExtension
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('attr', [
            'autocomplete' => 'off',
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        yield FormType::class;
    }
}
