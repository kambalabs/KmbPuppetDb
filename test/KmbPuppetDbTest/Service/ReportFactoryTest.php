<?php
namespace KmbPuppetDbTest\Service;

use KmbPuppetDb;
use KmbPuppetDb\Service\ReportFactory;
use KmbPuppetDbTest\Bootstrap;

class ReportFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canCreateService()
    {
        $factory = new ReportFactory();

        $service = $factory->createService(Bootstrap::getServiceManager());

        $this->assertInstanceOf('KmbPuppetDb\Service\Report', $service);
        $this->assertInstanceOf('KmbPuppetDb\Options\ModuleOptions', $service->getOptions());
        $this->assertInstanceOf('KmbPuppetDb\Client', $service->getPuppetDbClient());
        $this->assertInstanceOf('KmbCore\DateTimeFactory', $service->getDateTimeFactory());
    }
}
