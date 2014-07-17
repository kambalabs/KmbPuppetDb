<?php
namespace KmbPuppetDbTest\Service;

use KmbPuppetDb\Response;
use KmbPuppetDb\Service;

class FactNamesTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canGetAll()
    {
        $puppetDbClientMock = $this->getMock('KmbPuppetDb\ClientInterface');
        $puppetDbClientMock->expects($this->any())
            ->method('send')
            ->will($this->returnValue(new Response(array('operatingsystem', 'architecture'))));
        $factNamesService = new Service\FactNames();
        $factNamesService->setPuppetDbClient($puppetDbClientMock);

        $factNames = $factNamesService->getAll();

        $this->assertEquals(array('operatingsystem', 'architecture'), $factNames);
    }
}
