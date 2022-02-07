<?php

namespace App\Tests\Controller\API;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use \Symfony\Bundle\FrameworkBundle\KernelBrowser;



class TaskControllerTest extends WebTestCase
{
    /**
     * @var AbstractDatabaseTool
     */
    protected $databaseTool;

    /**
     * @var KernelBrowser
     */
    private KernelBrowser $client;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadFixtures([
            \App\DataFixtures\Task\TaskPriorityFixtures::class,
            \App\DataFixtures\Task\TaskStatusFixtures::class,
            \App\DataFixtures\Task\TaskTypeFixtures::class,
            \App\DataFixtures\Task\TaskFixtures::class,
        ]);

    }

    /** @test */
    public function it_has_to_return_all_task(): void
    {
        // Set Up
        $this->client->request('GET', '/api/task/');

        // Do something
        $content = $this->client->getResponse()->getContent();
        $content = json_decode($content, true);

        // Make assertions
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals(4, count($content));
    }

    /** @test */
    public function it_has_to_create_task(): void
    {
        // Set Up
        $taskContent = [
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
        $this->client->request('POST', '/api/task/', $taskContent);

        // Do something
        $content = $this->client->getResponse()->getContent();
        $content = json_decode($content, true);
        $tasks = $this->entityManager->getRepository(Task::class)->findAll();

        // Make assertions
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals(5, count($tasks));
        $this->assertArrayHasKey('task', $content);
        $this->assertArrayHasKey('status', $content);
    }

    /** @test */
    public function it_has_to_return_single_task(): void
    {
        // Set Up
        $taskId = 1;
        $this->client->request('GET', '/api/task/' . $taskId);

        // Do something
        $content = $this->client->getResponse()->getContent();
        $content = json_decode($content, true);
        $tasks = $this->entityManager->getRepository(Task::class)->find($taskId);

        // Make assertions
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertArrayHasKey('priority', $content);
        $this->assertArrayHasKey('status', $content);
        $this->assertArrayHasKey('type', $content);
        $this->assertEquals($tasks->getId(), $content['id']);
        $this->assertEquals($tasks->getTitle(), $content['title']);
        $this->assertEquals($tasks->getDesc(), $content['desc']);
        $this->assertEquals($tasks->getScheduleTime()->format('Y-m-d'), $content['schedule-time']);
    }



    /** @test */
    public function it_has_to_update_task(): void
    {
        // Set Up
        $taskId = 1;
        $taskContent = [
            'title' => 'Updated Title',
            'desc' => 'Updated Desc',
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
        $this->client->request('PUT', '/api/task/' . $taskId, $taskContent);

        // Do something
        $content = $this->client->getResponse()->getContent();
        $content = json_decode($content, true);
        $tasks = $this->entityManager->getRepository(Task::class)->findAll();

        // Make assertions
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals(4, count($tasks));
        $this->assertArrayHasKey('task', $content);
        $this->assertArrayHasKey('status', $content);
        $this->assertEquals($taskContent['title'], $content['task']['title']);
        $this->assertEquals($taskContent['desc'], $content['task']['desc']);
        $this->assertEquals($taskId, $content['task']['id']);
    }

    /** @test */
    public function it_has_to_delete_task(): void
    {
        // Set Up
        $this->client->request('DELETE', '/api/task/1');

        // Do something
        $content = $this->client->getResponse()->getContent();
        $content = json_decode($content, true);
        $tasks = $this->entityManager->getRepository(Task::class)->findAll();

        // Make assertions
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals(3, count($tasks));
        $this->assertArrayHasKey('task', $content);
        $this->assertArrayHasKey('status', $content);
    }

}
