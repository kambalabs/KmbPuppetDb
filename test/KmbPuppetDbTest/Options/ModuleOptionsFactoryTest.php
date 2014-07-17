<?php
namespace KmbPuppetDbTest\Options;

use KmbPuppetDb\Options\ModuleOptionsFactory;
use KmbPuppetDbTest\Bootstrap;

class ModuleOptionsFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canCreateService()
    {
        $factory = new ModuleOptionsFactory();

        $options = $factory->createService(Bootstrap::getServiceManager());

        $this->assertInstanceOf('KmbPuppetDb\Options\ModuleOptions', $options);
    }
}
