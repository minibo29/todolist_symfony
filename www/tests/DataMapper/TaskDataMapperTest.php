<?php

namespace App\Test\DataMapper;

use App\DataMapper\TaskDataMapper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

class TaskDataMapperTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->taskDataMapper = new TaskDataMapper($this->entityManager);
    }

    /** @test */
    public function it_has_to_convert_request_to_task_entity()
    {
        $request = new Request();
        // Set Up
        $taskContent = [
            'id' => '2',
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
        $this->assertEquals(json_encode($taskContent), $taskEntity->jsonSerialize());
    }

}