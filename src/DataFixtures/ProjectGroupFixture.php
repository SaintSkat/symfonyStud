<?php

namespace App\DataFixtures;

use App\Entity\ProjectGroup;
use Doctrine\Persistence\ObjectManager;

class ProjectGroupFixture extends BaseFixture
{
    protected function loadData(ObjectManager $manager): void
    {
        $this->createMany(ProjectGroup::class, 2, function(ProjectGroup $group, $count) {
            $group->setName('Group ' . $this->faker->name);
        });

        $manager->flush();
    }
}
