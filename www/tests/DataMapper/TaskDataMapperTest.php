<?php

namespace App\Tests\DataMapper;

use App\DataMapper\TaskDataMapper;
use App\Entity\Task;
use App\Entity\Task\TaskPriority;
use App\Entity\Task\TaskStatus;
use App\Entity\Task\TaskType;
use App\Repository\Task\TaskPriorityRepository;
use App\Repository\Task\TaskStatusRepository;
use App\Repository\Task\TaskTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

class TaskDataMapperTest extends KernelTestCase
{
    /**
     * @var EntityManagerInterface|MockObject|null
     */
    private $entityManager;

    /**
     * @var TaskDataMapper|null
     */
    private $taskDataMapper;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $taskPriorityRepository = $this->createMock(TaskPriorityRepository::class);
        $taskPriority = $this->createMock(TaskPriority::class);
        $taskPriority
            ->expects($this->any())
            ->method('getId')
            ->willReturn(1)
        ;
        $taskPriorityRepository
            ->expects($this->any())
            ->method('find')
            ->willReturnMap([
                [1, null, null, $taskPriority],
                [4, null, null, null],
            ])
        ;

        $taskTypeRepository = $this->createMock(TaskTypeRepository::class);
        $taskType = $this->createMock(TaskType::class);
        $taskType->expects($this->any())
            ->method('getId')
            ->willReturn(1)
        ;
        $taskTypeRepository
            ->expects($this->any())
            ->method('find')
            ->willReturnMap([
                [1, null, null, $taskType],
                [4, null, null, null],
            ])
        ;

        $taskStatusRepository = $this->createMock(TaskStatusRepository::class);
        $taskStatus = $this->createMock(TaskStatus::class);
        $taskStatus
            ->expects($this->any())
            ->method('getId')
            ->willReturn(1)
        ;
        $taskStatusRepository
            ->expects($this->any())
            ->method('find')
            ->willReturnMap([
                [1, null, null, $taskStatus],
                [4, null, null, null],
            ])
        ;
        $this->entityManager
            ->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValueMap([
                [TaskPriority::class, $taskPriorityRepository],
                [TaskType::class, $taskTypeRepository],
                [TaskStatus::class, $taskStatusRepository],
            ]))
        ;

        $this->taskDataMapper = new TaskDataMapper($this->entityManager);
    }

    /**
     * @test
     * @throws Exception
     */
    public function it_has_to_convert_request_to_task_entity(): void
    {
        $request = new Request();
        // Set Up
        $taskContent = [
            'id' => 3,
            'title' => 'title',
            'desc' => 'title',
            'scheduleTime' => '2022-01-27T15:35:25.505Z',
            'priority' => [
                "id" => 1,
                "name" => "string",
                "label" => "string",
            ],
            'type' => [
                "id" => 1,
                "name" => "string",
                "label" => "string",
            ],
            'status' => [
                "id" => 1,
                "name" => "string",
                "label" => "string",
            ],
        ];

        $request->request->replace($taskContent);

        // Do something
        $taskEntity = $this->taskDataMapper->mapRequestContentToEntity($request);

        // Make assertions
        $this->assertInstanceOf(Task::class, $taskEntity);
        $this->assertNull($taskEntity->getId());

        $this->assertInstanceOf(TaskPriority::class, $taskEntity->getPriority());
        $this->assertInstanceOf(TaskStatus::class, $taskEntity->getStatus());
        $this->assertInstanceOf(TaskType::class, $taskEntity->getType());
    }

    /**
     * @test
     * @throws Exception
     */
    public function it_has_to_trow_exception(): void
    {
        $this->expectException(Exception::class);

        // Set Up
        $request = new Request();
        $taskContent = [
            'id' => '2',
            'title' => 'title',
            'desc' => 'desc',
            'scheduleTime' => 'wrong date',
            'priority' => [
                "id" => 1,
            ],
            'type' => [
                "id" => 1,
            ],
            'status' => [
                "id" => 1,
            ],
        ];

        $request->request->replace($taskContent);

        // Do something
        $taskEntity = $this->taskDataMapper->mapRequestContentToEntity($request);
    }

    /** @test */
    public function it_expect_to_has_empty_status_priority_type(): void
    {
        $request = new Request();
        // Set Up
        $taskContent = [
            'id' => 1,
            'title' => 'title',
            'desc' => 'desc',
            'scheduleTime' => '2022-01-27T15:35:25.505Z',
            'priority' => [
                "id" => 4,
                "name" => "string",
                "label" => "string",
            ],
            'type' => [
                "id" => 4,
                "name" => "string",
                "label" => "string",
            ],
            'status' => [
                "id" => 4,
                "name" => "string",
                "label" => "string",
            ],
        ];

        $request->request->replace($taskContent);

        // Do something
        $taskEntity = $this->taskDataMapper->mapRequestContentToEntity($request);

        // Make assertions
        $this->assertInstanceOf(Task::class, $taskEntity);
        $this->assertNull($taskEntity->getId());

        $this->assertEquals(null, $taskEntity->getPriority());
        $this->assertEquals(null, $taskEntity->getStatus());
        $this->assertEquals(null, $taskEntity->getType());
    }

    /** @test */
    public function it_expects_to_update_task_entity(): void
    {
        // Set Up
        $request = new Request();
        // Set Up
        $taskContent = [
            'id' => 1,
            'title' => 'New Title',
            'desc' => 'New Desc',
            'scheduleTime' => '2022-01-27T15:35:25.505Z',
            'priority' => [
                "id" => 1,
                "name" => "string",
                "label" => "string",
            ],
            'type' => [
                "id" => 1,
                "name" => "string",
                "label" => "string",
            ],
            'status' => [
                "id" => 1,
                "name" => "string",
                "label" => "string",
            ],
        ];
        $request->request->replace($taskContent);

        $task = new Task();
        $task
            ->setTitle('Title')
            ->setDesc('Desc')
            ->setPriority(null)
            ->setStatus(null)
            ->setType(null)
            ;

        // Do something
        $taskEntity = $this->taskDataMapper->mapRequestContentToEntity($request, $task);

        // Make assertions
        $this->assertEquals($taskEntity, $task);
        $this->assertEquals($taskContent['title'], $taskEntity->getTitle());
        $this->assertEquals($taskContent['desc'], $taskEntity->getDesc());

        $this->assertInstanceOf(TaskPriority::class, $taskEntity->getPriority());
        $this->assertInstanceOf(TaskStatus::class, $taskEntity->getStatus());
        $this->assertInstanceOf(TaskType::class, $taskEntity->getType());
    }

}