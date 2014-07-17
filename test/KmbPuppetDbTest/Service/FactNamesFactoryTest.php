<?php
namespace KmbPuppetDbTest\Service;

use KmbPuppetDb\Service\FactNamesFactory;
use KmbPuppetDbTest\Bootstrap;

class FactNamesFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canCreateService()
    {
        $factory = new FactNamesFactory();

        $service = $factory->createService(Bootstrap::getServiceManager());

        $this->assertInstanceOf('KmbPuppetDb\Service\FactNames', $service);
        $this->assertInstanceOf('KmbPuppetDb\Client', $service->getPuppetDbClient());
    }
}
