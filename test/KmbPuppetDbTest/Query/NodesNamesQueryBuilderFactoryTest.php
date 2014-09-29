<?php
namespace KmbPuppetDbTest\Query;

use KmbPuppetDb\Exception\InvalidArgumentException;
use KmbPuppetDb\Query\NodesNamesQueryBuilderFactory;
use KmbPuppetDbTest\Bootstrap;

class NodesNamesQueryBuilderFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canCreateServiceV3()
    {
        $service = Bootstrap::getServiceManager()->get('KmbPuppetDb\Query\NodesNamesQueryBuilder');

        $this->assertInstanceOf('KmbPuppetDb\Query\NodesV3NamesQueryBuilder', $service);
    }

    /** @test */
    public function canCreateServiceV4()
    {
        $serviceLocator = $this->getServiceLocator('v4');
        $factory = new NodesNamesQueryBuilderFactory();

        $service = $factory->createService($serviceLocator);

        $this->assertInstanceOf('KmbPuppetDb\Query\NodesV4NamesQueryBuilder', $service);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid API version : unknown
     */
    public function cannotCreateServiceWithUnknownVersion()
    {
        $serviceLocator = $this->getServiceLocator('unknown');
        $factory = new NodesNamesQueryBuilderFactory();

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
