<?php

namespace App\Service;

use App\Entity\Task;
use App\Event\TaskCreatedEvent;
use App\Event\TaskDeletedEvent;
use App\Event\TaskUpdatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class TaskService
{
    public function __construct(
        private EntityManagerInterface   $entityManager,
        private EventDispatcherInterface $eventDispatcher,
    )
    {
    }

    public function createTask(Task $task): Task
    {
        $this->entityManager->persist($task);
        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(new TaskCreatedEvent($task));
        return $task;
    }

    public function updateTask(Task $task): Task
    {
        $this->entityManager->persist($task);
        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(new TaskUpdatedEvent($task));
        return $task;
    }

    public function deleteTask(Task $task): Task
    {
        $this->entityManager->remove($task);
        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(new TaskDeletedEvent($task));
        return $task;
    }
}
