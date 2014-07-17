<?php
namespace KmbPuppetDbTest;

use KmbPuppetDb\ClientFactory;

class ClientFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canCreateService()
    {
        $factory = new ClientFactory();

        $puppetDbClient = $factory->createService(Bootstrap::getServiceManager());
        $httpClient = $puppetDbClient->getHttpClient();
        $logger = $puppetDbClient->getLogger();

        $this->assertInstanceOf('KmbPuppetDb\Client', $puppetDbClient);
        $this->assertInstanceOf('Zend\Http\Client', $httpClient);
        $this->assertInstanceOf('Zend\Log\Logger', $logger);
        $this->assertEquals('https://localhost:8080/', $puppetDbClient->getOptions()->getBaseUri());
        $this->assertEquals(array('adapter' => 'Zend\Http\Client\Adapter\Curl'), $puppetDbClient->getOptions()->getHttpOptions());
        $this->assertInstanceOf('Zend\Http\Client\Adapter\Curl', $httpClient->getAdapter());
    }
}
