<?php

namespace App\Tests\Controller\API;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use LogicException;
use Symfony\Component\HttpKernel\KernelInterface;

class TaskControllerTest extends KernelTestCase
{
    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        if ('test' !== $kernel->getEnvironment()) {
            throw new LogicException('Execution only in Test environment possible!');
        }

        $this->initDatabase($kernel);

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    private function initDatabase(KernelInterface $kernel): void
    {
        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $metaData = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->updateSchema($metaData);
    }

    private function insertDefaultSchedule(): void
    {
        $default = Schedule::defaultSchedule();

        $this->entityManager->persist($default);
        $this->entityManager->flush();
    }


//    public function testSomething(): void
//    {
//        $kernel = self::bootKernel();
//
//        $this->assertSame('test', $kernel->getEnvironment());
//        //$routerService = static::getContainer()->get('router');
//        //$myCustomService = static::getContainer()->get(CustomService::class);
//    }

    /** @test */
    public function it_should_create_task()
    {
        // Set Up

        // Do something

        // Make assertions
    }

    /** @test */
    public function it_should_update_task()
    {
        // Set Up

        // Do something

        // Make assertions
    }

    /** @test */
    public function it_should_delete_task()
    {
        // Set Up

        // Do something

        // Make assertions
    }

    /** @test */
    public function it_should_throw_not_found_exception()
    {
        // Set Up

        // Do something

        // Make assertions
    }



}
