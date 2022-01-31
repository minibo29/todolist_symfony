<?php

namespace App\Tests\Controller\API;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\KernelInterface;
//use Symfony\Flex\Response;
use Symfony\Component\HttpFoundation\Response;


class TaskControllerTest extends WebTestCase
{
    private $entityManager;
    private $client;

    protected function setUp(): void
    {
//        $kernel = self::bootKernel();
        $this->client = static::createClient();

//        if ('test' !== $kernel->getEnvironment()) {
//            throw new LogicException('Execution only in Test environment possible!');
//        }

//        $this->initDatabase($kernel);

//        $this->entityManager = $kernel->getContainer()
//            ->get('doctrine')
//            ->getManager();

//        $this->client = static::createClient();
    }
//
//    private function initDatabase(KernelInterface $kernel): void
//    {
//        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
//        $metaData = $entityManager->getMetadataFactory()->getAllMetadata();
//        $schemaTool = new SchemaTool($entityManager);
//        $schemaTool->updateSchema($metaData);
//    }
//
//    private function insertDefaultSchedule(): void
//    {
//        $default = Schedule::defaultSchedule();
//
//        $this->entityManager->persist($default);
//        $this->entityManager->flush();
//    }

    /** @test */
    public function it_has_to_return_all_task()
    {
        // Set Up

        // Do something
        $this->client->request('GET', '/api/task/');

        // Make assertions
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseIsSuccessful();
    }



    /** @test */
    public function it_has_to_create_task()
    {
        // Set Up

        // Do something

        // Make assertions
    }

    /** @test */
    public function it_has_to_update_task()
    {
        // Set Up

        // Do something

        // Make assertions
    }

    /** @test */
    public function it_has_to_delete_task()
    {
        // Set Up

        // Do something

        // Make assertions
    }

    /** @test */
    public function it_has_to_throw_not_found_exception()
    {
        // Set Up

        // Do something

        // Make assertions
    }



}
