<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends BaseFixture
{
    protected function loadData(ObjectManager $manager): void
    {
        $this->createMany(User::class, 3, function(User $user, $count) {
            $user->setName($this->faker->name)
                ->setEmail($this->faker->email)
                ->setRoles(['ROLE_USER'])
                ->setPassword($this->faker->password)
                ->setIsVerified(true);
        });

        $manager->flush();
    }
}
