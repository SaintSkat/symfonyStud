<?php

namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TaskFixture extends BaseFixture implements DependentFixtureInterface
{
    protected function loadData(ObjectManager $manager): void
    {
        $projects = $manager->getRepository(Project::class)->findAll();
        $users = $manager->getRepository(User::class)->findAll();
        $this->createMany(Task::class, 20, function(Task $task, $count) use ($projects, $users) {
            $task->setName($this->faker->sentence)
                ->setDescription($this->faker->text(1000))
                ->setProject($this->faker->randomElement($projects))
                ->setUser($this->faker->randomElement($users));
        });

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProjectFixture::class,
            UserFixture::class,
        ];
    }
}
