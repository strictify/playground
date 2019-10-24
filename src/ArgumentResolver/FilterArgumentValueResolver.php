<?php

declare(strict_types=1);

namespace App\ArgumentResolver;

use App\Annotation\FilterConfig;
use App\Filter\FilterInterface;
use LogicException;
use Symfony\Component\Form\{Extension\Core\Type\FormType, FormFactoryInterface};
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\{Controller\ArgumentValueResolverInterface, ControllerMetadata\ArgumentMetadata};
use Generator;
use function is_a;

class FilterArgumentValueResolver implements ArgumentValueResolverInterface
{
    private FormFactoryInterface $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        $type = $argument->getType();
        if (!$type) {
            return false;
        }

        return is_a($type, FilterInterface::class, true);
    }

    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        /** @psalm-var class-string<FilterInterface>|null $type */
        $type = $argument->getType();
        if (!$type) {
            throw new LogicException('Type is not set.');
        }

        /** @var FilterConfig|null $filterConfig */
        $filterConfig = $request->attributes->get('_filter_name');
        $name = $filterConfig ? $filterConfig->name : '';

        $formBuilder = $this->formFactory->createNamedBuilder($name, FormType::class, null, [
            'csrf_protection' => false,
            'method' => 'GET',
        ]);
        /** @var FilterInterface $filter */
        $filter = new $type($formBuilder, $request);

        yield $filter;
    }
}
