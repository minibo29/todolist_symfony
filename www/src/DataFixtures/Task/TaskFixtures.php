<?php

namespace App\DataFixtures\Task;

use App\Entity\Task\TaskPriority;
use App\Entity\Task\TaskStatus;
use App\Entity\Task\TaskType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TaskFixtures extends Fixture
{

    public function load(ObjectManager $manager
    ): void
    {
        $priorities = $manager->getRepository(TaskPriority::class)->findAll();
        $statuses = $manager->getRepository(TaskStatus::class)->findAll();
        $types = $manager->getRepository(TaskType::class)->findAll();

        for ($i = 1; $i < 5 ;$i++) {
            $task = new \App\Entity\Task();
            $task->setTitle('Test Title ' . $i)
                ->setDesc('Test Desc ' . $i)
                ->setScheduleTime(new \DateTime('now'))
                ->setPriority($priorities[array_rand($priorities)])
                ->setStatus($statuses[array_rand($statuses)])
                ->setType($types[array_rand($types)])
            ;
            $manager->persist($task);
        }
        $manager->flush();
    }
}