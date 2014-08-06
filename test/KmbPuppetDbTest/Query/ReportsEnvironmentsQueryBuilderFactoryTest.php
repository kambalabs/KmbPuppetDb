<?php
namespace KmbPuppetDbTest\Query;

use KmbPuppetDb\Exception\InvalidArgumentException;
use KmbPuppetDb\Query\ReportsEnvironmentsQueryBuilderFactory;
use KmbPuppetDbTest\Bootstrap;

class ReportsEnvironmentsQueryBuilderFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canCreateServiceV3()
    {
        $factory = new ReportsEnvironmentsQueryBuilderFactory();

        $service = $factory->createService(Bootstrap::getServiceManager());

        $this->assertInstanceOf('KmbPuppetDb\Query\ReportsV3EnvironmentsQueryBuilder', $service);
    }

    /** @test */
    public function canCreateServiceV4()
    {
        $serviceLocator = $this->getServiceLocator('v4');
        $factory = new ReportsEnvironmentsQueryBuilderFactory();

        $service = $factory->createService($serviceLocator);

        $this->assertInstanceOf('KmbPuppetDb\Query\ReportsV4EnvironmentsQueryBuilder', $service);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid API version : unknown
     */
    public function cannotCreateServiceWithUnknownVersion()
    {
        $serviceLocator = $this->getServiceLocator('unknown');
        $factory = new ReportsEnvironmentsQueryBuilderFactory();

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
