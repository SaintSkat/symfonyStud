<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Persistence\ObjectManager;

class TaskFixture extends BaseFixture
{
    protected function loadData(ObjectManager $manager): void
    {
        $this->createMany(Task::class, 20, function(Task $task, $count) {
            $task->setName($this->faker->sentence)
                ->setDescription($this->faker->text(1000));
        });

        $manager->flush();
    }
}
