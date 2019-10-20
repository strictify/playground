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
        $admin = new User('John', 'Smith');
        $admin->setPlainPassword('123123123');
        $admin->setEnabled(true);
        $manager->persist($admin);

        $manager->flush();
    }
}
