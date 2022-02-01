<?php

namespace App\Tests\Service;

use App\Entity\Task;
use App\Event\TaskCreatedEvent;
use App\Event\TaskDeletedEvent;
use App\Event\TaskUpdatedEvent;
use App\Service\TaskService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class TaskServiceTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;
    private ?EventDispatcherInterface $eventDispatcherInterface;
    private ?TaskService $taskService;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->eventDispatcherInterface = $this->createMock(EventDispatcherInterface::class);
        $this->taskService = new TaskService($this->entityManager, $this->eventDispatcherInterface);
    }

    /** @test */
    public function it_has_to_create_task(): void
    {
        // Set Up
        $taskEntity = $this->createMock(Task::class);

        $this->entityManager->expects($this->once())
            ->method('persist')
        ;
        $this->entityManager->expects($this->once())
            ->method('flush');
        $this->eventDispatcherInterface->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(function($subject) use ($taskEntity)
            {
                return is_callable([$subject, 'getTask']) &&
                    $subject::class == TaskCreatedEvent::class &&
                    $taskEntity === $subject->getTask()
                    ;
            }))
        ;

        // Do something
        $task = $this->taskService->createTask($taskEntity);
        // Make assertions

        $this->assertEquals($task::class, $task::class);
    }

    /** @test */
    public function it_has_to_update_task(): void
    {
        // Set Up
        $taskEntity = $this->createMock(Task::class);

        $this->entityManager->expects($this->once())
            ->method('persist')
        ;
        $this->entityManager->expects($this->once())
            ->method('flush');
        $this->eventDispatcherInterface->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(function($subject) use ($taskEntity)
            {
                return is_callable([$subject, 'getTask']) &&
                    $subject::class == TaskUpdatedEvent::class &&
                    $taskEntity === $subject->getTask()
                    ;
            }))
        ;

        // Do something
        $task = $this->taskService->updateTask($taskEntity);
        // Make assertions

        $this->assertEquals($task::class, $task::class);
    }


    /** @test */
    public function it_has_to_delete_task(): void
    {
        // Set Up
        $taskEntity = $this->createMock(Task::class);

        $this->entityManager->expects($this->once())
            ->method('remove')
        ;
        $this->entityManager->expects($this->once())
            ->method('flush');
        $this->eventDispatcherInterface->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(function($subject) use ($taskEntity)
            {
                return is_callable([$subject, 'getTask']) &&
                    $subject::class == TaskDeletedEvent::class &&
                    $taskEntity === $subject->getTask()
                    ;
            }))
        ;

        // Do something
        $task = $this->taskService->deleteTask($taskEntity);
        // Make assertions
        $this->assertEquals($task::class, $task::class);
    }

}