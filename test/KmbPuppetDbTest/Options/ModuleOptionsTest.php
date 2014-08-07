<?php
namespace KmbPuppetDbTest\Options;

use KmbPuppetDb\Options\ModuleOptions;

class ModuleOptionsTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canGetNodeV4HydratorClass()
    {
        $moduleOptions = new ModuleOptions(['api_version' => 'v4']);

        $this->assertEquals('KmbPuppetDb\Model\NodeV4Hydrator', $moduleOptions->getNodeHydratorClass());
    }
}
