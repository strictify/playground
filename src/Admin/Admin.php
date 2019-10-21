<?php

declare(strict_types=1);

namespace App\Admin;

use App\Admin\Registry\AdminInterface;
use InvalidArgumentException;
use function sprintf;

class Admin
{
    /** @var AdminInterface[]|iterable<array-key, AdminInterface> */
    private iterable $admins;

    /** @param iterable<array-key, AdminInterface> $admins */
    public function __construct(iterable $admins)
    {
        $this->admins = $admins;
    }

    public function getPaginator(string $segment): iterable
    {
        $admin = $this->getAdmin($segment);

        return $admin->getPaginator();
    }

    private function getAdmin(string $segment): AdminInterface
    {
        foreach ($this->admins as $admin) {
            if ($admin->supports($segment)) {
                return $admin;
            }
        }
        throw new InvalidArgumentException(sprintf('Segment "%s" not found.', $segment));
    }
}
