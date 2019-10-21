<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $admin = new User('John', 'Smith', 'super_admin@example.com', '123123123');
        $admin->addRole('ROLE_SUPER_ADMIN');
        $admin->setEnabled(true);
        $manager->persist($admin);

        $manager->flush();
    }
}
