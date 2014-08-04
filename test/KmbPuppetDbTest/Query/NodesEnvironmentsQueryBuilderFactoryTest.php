<?php
namespace KmbPuppetDbTest\Query;

use KmbPuppetDb\Exception\InvalidArgumentException;
use KmbPuppetDb\Query\NodesEnvironmentsQueryBuilderFactory;
use KmbPuppetDbTest\Bootstrap;

class NodesEnvironmentsQueryBuilderFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canCreateServiceV3()
    {
        $factory = new NodesEnvironmentsQueryBuilderFactory();

        $service = $factory->createService(Bootstrap::getServiceManager());

        $this->assertInstanceOf('KmbPuppetDb\Query\NodesV3EnvironmentsQueryBuilder', $service);
    }

    /** @test */
    public function canCreateServiceV4()
    {
        $serviceLocator = $this->getServiceLocator('v4');
        $factory = new NodesEnvironmentsQueryBuilderFactory();

        $service = $factory->createService($serviceLocator);

        $this->assertInstanceOf('KmbPuppetDb\Query\NodesV4EnvironmentsQueryBuilder', $service);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid API version : unknown
     */
    public function cannotCreateServiceWithUnknownVersion()
    {
        $serviceLocator = $this->getServiceLocator('unknown');
        $factory = new NodesEnvironmentsQueryBuilderFactory();

        $factory->createService($serviceLocator);
    }

    /**
     * @param $apiVersion
     * @return \Zend\ServiceManager\ServiceManager
     */
    protected function getServiceLocator($apiVersion)
    {
        $options = $this->getMock('KmbPuppetDb\Options\ModuleOptions');
        $options->expects($this->any())
            ->method('getApiVersion')
            ->will($this->returnValue($apiVersion));
        $serviceLocator = clone Bootstrap::getServiceManager();
        $serviceLocator->setAllowOverride(true);
        $serviceLocator->setService('KmbPuppetDb\Options\ModuleOptions', $options);
        return $serviceLocator;
    }
}
