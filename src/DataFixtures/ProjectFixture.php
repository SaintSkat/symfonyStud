<?php

namespace App\DataFixtures;

use App\Entity\Project;
use Doctrine\Persistence\ObjectManager;

class ProjectFixture extends BaseFixture
{
    protected function loadData(ObjectManager $manager): void
    {
        $this->createMany(Project::class, 10, function(Project $project, $count) {
            $project->setName($this->faker->company);
        });

        $manager->flush();
    }
}
