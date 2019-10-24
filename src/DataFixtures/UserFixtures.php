<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $admin = new User('John', 'Smith', 'super_admin@example.com', '123123123');
        $admin->addRole('ROLE_SUPER_ADMIN');
        $admin->setEnabled(true);
        $manager->persist($admin);

        $factory = Factory::create();
        for ($i = 0; $i < 20; ++$i) {
            $user = new User((string) $factory->firstName, (string) $factory->lastName, (string) $factory->email, '123123123');
            $manager->persist($user);
        }

        $manager->flush();
    }
}
