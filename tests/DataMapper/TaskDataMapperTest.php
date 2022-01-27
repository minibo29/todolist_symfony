<?php

namespace App\Test\DataMapper;

use App\DataMapper\TaskDataMapper;
use App\Entity\Task;
use App\Service\TaskService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

class TaskDataMapperTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->taskDataMapper = new TaskDataMapper();
    }

    /** @test */
    public function it_has_to_convert_to_task_entity()
    {
        // Set Up
        $taskContent = [
            'id' => '2',
            'title' => 'title',
            'desc' => '',
            'scheduleTime' => '',
            'priority' =>[],
            'type' =>[],
            'status' =>[],
        ];

        // Do something
        $taskEntity = $this->taskDataMapper->mapRequestContentToEntity($taskContent);

        // Make assertions
        $this->assertEquals($taskContent, $taskEntity->jsonSerialize());
    }



}