<?php

namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\ProjectGroup;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;


class ProjectFixture extends BaseFixture implements DependentFixtureInterface
{
    protected function loadData(ObjectManager $manager): void
    {
        $groups = $manager->getRepository(ProjectGroup::class)->findAll();
        $this->createMany(Project::class, 10, function(Project $project, $count) use ($groups) {
            $project->setName($this->faker->company);
            $project->setGroup($this->faker->randomElement($groups));
        });

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProjectGroupFixture::class,
        ];
    }
}
