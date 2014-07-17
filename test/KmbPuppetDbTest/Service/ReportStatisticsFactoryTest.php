<?php
namespace KmbPuppetDbTest\Service;

use KmbPuppetDb\Service;
use KmbPuppetDb\Service\ReportStatisticsFactory;
use KmbPuppetDbTest\Bootstrap;

class ReportStatisticsFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canCreateService()
    {
        $factory = new ReportStatisticsFactory();

        $service = $factory->createService(Bootstrap::getServiceManager());

        $this->assertInstanceOf('KmbPuppetDb\Service\ReportStatistics', $service);
        $this->assertInstanceOf('KmbPuppetDb\Service\Report', $service->getReportService());
    }
}
