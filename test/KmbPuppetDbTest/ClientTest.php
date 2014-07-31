<?php
namespace KmbPuppetDbTest;

use KmbPuppetDb\Exception\InvalidArgumentException;
use KmbPuppetDb;
use KmbPuppetDb\Exception\RuntimeException;
use Zend\Http\Request;
use Zend\Log\Logger;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    const FAKE_DATETIME = '2014-03-31';

    /**
     * @var FakeHttpClient
     */
    private $fakeHttpClient;

    /**
     * @var KmbPuppetDb\Client
     */
    private $puppetDbClient;

    protected function setUp()
    {
        $this->fakeHttpClient = new FakeHttpClient(static::FAKE_DATETIME);

        $options = $this->getMock('KmbPuppetDb\Options\ClientOptionsInterface');
        $options->expects($this->any())
            ->method('getBaseUri')
            ->will($this->returnValue('https://localhost:8081/'));

        $this->puppetDbClient = new KmbPuppetDb\Client();
        $this->puppetDbClient->setHttpClient($this->fakeHttpClient);
        $this->puppetDbClient->setOptions($options);
        $this->puppetDbClient->setLogger(new Logger(array('writers' => array('null' => array('name' => 'null')))));
    }

    /** @test */
    public function canSendRequest()
    {
        $response = $this->puppetDbClient->send(new KmbPuppetDb\Request('/nodes'));

        $this->assertInstanceOf('KmbPuppetDb\Response', $response);
        $nodes = $this->convertToArray($response->getData());
        $this->assertEquals(
            array(
                'name' => 'node1.local',
                'deactivated' => null,
                'catalog_timestamp' => '2014-01-31T16:01:16.683Z',
                'facts_timestamp' => '2014-01-31T16:01:15.628Z',
                'report_timestamp' => '2014-01-31T16:01:15.507Z',
            ),
            $nodes[0]
        );
    }

    /** @test */
    public function canSendRequestWithQuery()
    {
        $response = $this->puppetDbClient->send(new KmbPuppetDb\Request('/nodes', array('=', array('fact', 'operatingsystem'), 'Windows')));

        $this->assertEquals(array(
            array(
                'name' => 'node6.local',
                'deactivated' => null,
                'catalog_timestamp' => '2014-01-31T16:00:24.713Z',
                'facts_timestamp' => '2014-01-31T16:00:23.604Z',
                'report_timestamp' => '2014-01-31T16:00:22.815Z',
            )
        ), $this->convertToArray($response->getData()));
    }

    /** @test */
    public function canSendRequestWithPaging()
    {
        $request = new KmbPuppetDb\Request('/nodes');
        $request->setPaging(3, 2);
        $response = $this->puppetDbClient->send($request);

        $this->assertEquals(array(
            array(
                'name' => 'node4.local',
                'deactivated' => null,
                'catalog_timestamp' => '2014-01-31T16:00:24.713Z',
                'facts_timestamp' => '2014-01-31T16:00:23.604Z',
                'report_timestamp' => '2014-01-31T16:00:22.815Z'
            ),
            array(
                'name' => 'node5.local',
                'deactivated' => null,
                'catalog_timestamp' => '2014-01-31T16:00:24.713Z',
                'facts_timestamp' => '2014-01-31T16:00:23.604Z',
                'report_timestamp' => '2014-01-31T16:00:22.815Z'
            )
        ), $this->convertToArray($response->getData()));
        $this->assertEquals(9, $response->getTotal());
    }

    /**
     * @test
     * @expectedException RuntimeException
     * @expectedExceptionMessage Unexpected PuppetDB Response: HTTP/1.0 500 Internal Server Error
     */
    public function cannotSendRequestOnFailure()
    {
        $this->fakeHttpClient->fails();

        $this->puppetDbClient->send(new KmbPuppetDb\Request('/nodes'));
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage No information is known about unknown.local
     */
    public function cannotGetRequestInError()
    {
        $this->puppetDbClient->send(new KmbPuppetDb\Request('/nodes/unknown.local'));
    }

    /** @test */
    public function canSendPost()
    {
        $request = new KmbPuppetDb\Request('/commands');
        $request->setMethod(Request::METHOD_POST);
        $request->setData([]);

        $response = $this->puppetDbClient->send($request);

        $this->assertInstanceOf('KmbPuppetDb\Response', $response);
        $this->assertEquals('f37d4e5e-b31b-49df-b89e-5762387d867b', $response->getData()->uuid);
    }

    /**
     * @param $data
     * @return array
     */
    protected function convertToArray($data)
    {
        return array_map(function ($elt) {
            return get_object_vars($elt);
        }, $data);
    }
}
