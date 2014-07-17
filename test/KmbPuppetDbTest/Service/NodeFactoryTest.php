<?php
namespace KmbPuppetDbTest\Service;

use KmbPuppetDb;
use KmbPuppetDb\Service\NodeFactory;
use KmbPuppetDbTest\Bootstrap;

class NodeFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canCreateService()
    {
        $factory = new NodeFactory();

        $nodeService = $factory->createService(Bootstrap::getServiceManager());

        $this->assertInstanceOf('KmbPuppetDb\Service\Node', $nodeService);
        $this->assertInstanceOf('KmbPuppetDb\Options\ModuleOptions', $nodeService->getOptions());
        $this->assertInstanceOf('KmbPuppetDb\Client', $nodeService->getPuppetDbClient());
    }
}
