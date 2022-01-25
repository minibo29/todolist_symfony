<?php

namespace App\Service;

use App\Entity\Task;
use App\Entity\Task\TaskPriority;
use App\Entity\Task\TaskStatus;
use App\Entity\Task\TaskType;
use App\Event\TaskCreatedEvent;
use App\Event\TaskDeletedEvent;
use App\Event\TaskUpdatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaskService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private EventDispatcherInterface $eventDispatcher,
    )
    {
    }

    /**
     * @throws \Exception
     */
    public function createTask($task)
    {
        try {
            $this->entityManager->persist($task);
            $this->entityManager->flush();

            $this->eventDispatcher->dispatch(new TaskCreatedEvent($task));

            return $task;
        } catch (\Exception $exception) {
            throw new \Exception($exception);
        }
    }

    /**
     * @throws \Exception
     */
    public function updateTask($task)
    {
        try {
            $this->entityManager->persist($task);
            $this->entityManager->flush();

            $this->eventDispatcher->dispatch(new TaskUpdatedEvent($task));

            return $task;
        } catch (\Exception $exception) {
            throw new \Exception($exception);
        }
    }

    /**
     * @throws \Exception
     */
    public function deleteTask($task)
    {
        try {
            $this->entityManager->remove($task);
            $this->entityManager->flush();

            $this->eventDispatcher->dispatch(new TaskDeletedEvent($task));

            return $task;
        } catch (\Exception $exception) {
            throw new \Exception($exception);
        }
    }



}