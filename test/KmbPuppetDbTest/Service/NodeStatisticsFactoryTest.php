<?php
namespace KmbPuppetDbTest\Service;

use KmbPuppetDb\Service;
use KmbPuppetDb\Service\NodeStatisticsFactory;
use KmbPuppetDbTest\Bootstrap;

class NodeStatisticsFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canCreateService()
    {
        $factory = new NodeStatisticsFactory();

        $nodeStatistics = $factory->createService(Bootstrap::getServiceManager());

        $this->assertInstanceOf('KmbPuppetDb\Service\NodeStatistics', $nodeStatistics);
        $this->assertInstanceOf('KmbPuppetDb\Service\Node', $nodeStatistics->getNodeService());
    }
}
