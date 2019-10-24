<?php

declare(strict_types=1);

namespace App\Filter;

use Symfony\Component\Form\{FormBuilderInterface, FormInterface, FormView};
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractFilter implements FilterInterface
{
    private FormInterface $form;
    private int $page;

    public function __construct(FormBuilderInterface $formBuilder, Request $request)
    {
        $this->configureForm($formBuilder);
        $this->form = $formBuilder->getForm();
        $this->form->handleRequest($request);
        $this->page = $request->query->getInt('page', 1);
    }

    abstract protected function configureForm(FormBuilderInterface $formBuilder): void;

    public function get(string $name)
    {
        return $this->form->get($name)->getData();
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getFormView(): FormView
    {
        return $this->getForm()->createView();
    }

    protected function getForm(): FormInterface
    {
        return $this->form;
    }
}
