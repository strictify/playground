<?php

declare(strict_types=1);

namespace App\Admin\Registry;

use App\Repository\UserRepository;

class UsersAdmin implements AdminInterface
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function supports(string $segment): bool
    {
        return $segment === 'users';
    }

    public function getPaginator(): iterable
    {
        return $this->repository->findAll();
    }
}
