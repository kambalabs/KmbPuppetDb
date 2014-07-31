<?php
namespace KmbPuppetDbTest\Service;

use KmbPuppetDb\Model;
use KmbPuppetDb\Model\NodeInterface;
use KmbPuppetDb\Options\ModuleOptions;
use KmbPuppetDb\Request;
use KmbPuppetDb\Response;
use KmbPuppetDb\Service;
use KmbPuppetDbTest\Bootstrap;
use Zend\Log\Logger;

class NodeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $puppetDbClientMock;

    /**
     * @var Service\Node
     */
    private $nodeService;

    protected function setUp()
    {
        $this->puppetDbClientMock = $this->createPuppetDbClientMock();
        $this->nodeService = new Service\Node();
        $this->nodeService->setOptions(new ModuleOptions());
        $this->nodeService->setPuppetDbClient($this->puppetDbClientMock);
        /** @var Logger $logger */
        $logger = Bootstrap::getServiceManager()->get('Logger');
        $this->nodeService->setLogger($logger);
    }

    /** @test */
    public function canGetNodeWithUnchangedStatus()
    {
        $node = $this->nodeService->getByName('node1.local');

        $this->assertInstanceOf('KmbPuppetDb\Model\Node', $node);
        $this->assertEquals('node1.local', $node->getName());
        $this->assertEquals(NodeInterface::UNCHANGED, $node->getStatus());
        $this->assertEquals(array('operatingsystem' => 'Debian'), $node->getFacts());
    }

    /** @test */
    public function canGetNodeWithChangedStatus()
    {
        $node = $this->nodeService->getByName('node2.local');

        $this->assertEquals(NodeInterface::CHANGED, $node->getStatus());
    }

    /** @test */
    public function canGetNodeFailedStatus()
    {
        $node = $this->nodeService->getByName('node3.local');

        $this->assertEquals(NodeInterface::FAILED, $node->getStatus());
    }

    /** @test */
    public function canGetNodes()
    {
        $nodes = $this->nodeService->getAll();

        $this->assertEquals(3, count($nodes));
        /** @var NodeInterface $node */
        $node = $nodes->getNodeByName('node1.local');
        $this->assertInstanceOf('KmbPuppetDb\Model\NodeInterface', $node);
        $this->assertEquals(new \DateTime('2014-01-31T16:01:15.628Z'), $node->getReportedAt());
        $this->assertEquals(NodeInterface::UNCHANGED, $node->getStatus());
        $this->assertEquals(array('operatingsystem' => 'Debian'), $node->getFacts());
    }

    /** @test */
    public function canGetNodesFilteredByFacts()
    {
        $nodes = $this->nodeService->getAll(array(
            '=',
            array('fact', 'kernelversion'),
            '3.2.0'
        ));

        $this->assertEquals(1, count($nodes));
        $this->assertEquals('node1.local', $nodes->getNodeByIndex(0)->getName());
    }

    /** @test */
    public function canGetNodesWithPaging()
    {
        $nodes = $this->nodeService->getAll(1, 1);

        $this->assertEquals(1, count($nodes));
        $this->assertEquals(3, $nodes->getTotal());
        $this->assertEquals('node2.local', $nodes->getNodeByIndex(0)->getName());
    }

    /** @test */
    public function canGetNodesWithOrdering()
    {
        $nodes = $this->nodeService->getAll(0, 10, array('field' => 'name', 'order' => 'desc'));

        $this->assertEquals(3, count($nodes));
        $this->assertEquals('node3.local', $nodes->getNodeByIndex(0)->getName());
    }

    /** @test */
    public function canReplaceFacts()
    {
        $node = new Model\Node('node1.local', NodeInterface::UNCHANGED, new \DateTime(), ['kmbenv' => 'STABLE_PF1']);

        $this->nodeService->replaceFacts($node);
    }

    /**
     * @param array $data
     * @param int   $total
     * @return Response
     */
    public function response(array $data, $total = null)
    {
        $response = new Response();
        $response->setData(json_decode(json_encode(($data))));
        $response->setTotal($total);
        return $response;
    }

    /**
     * @param Model\Node $node
     * @return Request
     */
    public function nodeStatusRequest(Model\Node $node)
    {
        $expectedRequest = new Request('/event-counts', array(
            'and',
            array('=', 'certname', $node->getName()),
            array('=', 'latest-report?', true),
        ));
        return $expectedRequest->setSummarizeBy('certname');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function createPuppetDbClientMock()
    {
        $self = $this;
        $mock = $this->getMock('KmbPuppetDb\ClientInterface');
        $mock->expects($this->any())
            ->method('send')
            ->will($this->returnCallback(function (Request $request) use ($self) {
                $query = $request->getQuery();
                $offset = $request->getOffset();
                $limit = $request->getLimit();
                $orderBy = $request->getOrderBy();
                switch ($request->getUri()) {
                    case '/commands':
                        return $self->response(['uuid' => '784a5c9a-aaee-4466-b607-bb4262248803']);
                    case '/nodes':
                        if (!empty($query)) {
                            return $self->response(array(
                                array(
                                    'name' => 'node1.local',
                                    'deactivated' => null,
                                    'catalog_timestamp' => '2014-01-31T16:01:16.683Z',
                                    'facts_timestamp' => '2014-01-31T16:01:15.628Z',
                                    'report_timestamp' => '2014-01-31T16:01:15.507Z',
                                ),
                            ));
                        } elseif (!empty($orderBy)) {
                            return $self->response(array(
                                array(
                                    'name' => 'node3.local',
                                    'deactivated' => null,
                                    'catalog_timestamp' => '2014-01-31T16:00:24.713Z',
                                    'facts_timestamp' => '2014-01-31T16:00:23.604Z',
                                    'report_timestamp' => '2014-01-31T16:00:22.815Z',
                                ),
                                array(
                                    'name' => 'node2.local',
                                    'deactivated' => null,
                                    'catalog_timestamp' => '2014-01-31T16:00:24.713Z',
                                    'facts_timestamp' => '2014-01-31T16:00:23.604Z',
                                    'report_timestamp' => '2014-01-31T16:00:22.815Z',
                                ),
                                array(
                                    'name' => 'node1.local',
                                    'deactivated' => null,
                                    'catalog_timestamp' => '2014-01-31T16:01:16.683Z',
                                    'facts_timestamp' => '2014-01-31T16:01:15.628Z',
                                    'report_timestamp' => '2014-01-31T16:01:15.507Z',
                                ),
                            ));
                        } elseif (isset($offset) && isset($limit)) {
                            return $self->response(
                                array(array(
                                    'name' => 'node2.local',
                                    'deactivated' => null,
                                    'catalog_timestamp' => '2014-01-31T16:00:24.713Z',
                                    'facts_timestamp' => '2014-01-31T16:00:23.604Z',
                                    'report_timestamp' => '2014-01-31T16:00:22.815Z',
                                )),
                                3
                            );
                        } else {
                            return $self->response(array(
                                array(
                                    'name' => 'node1.local',
                                    'deactivated' => null,
                                    'catalog_timestamp' => '2014-01-31T16:01:16.683Z',
                                    'facts_timestamp' => '2014-01-31T16:01:15.628Z',
                                    'report_timestamp' => '2014-01-31T16:01:15.507Z',
                                ),
                                array(
                                    'name' => 'node2.local',
                                    'deactivated' => null,
                                    'catalog_timestamp' => '2014-01-31T16:00:24.713Z',
                                    'facts_timestamp' => '2014-01-31T16:00:23.604Z',
                                    'report_timestamp' => '2014-01-31T16:00:22.815Z',
                                ),
                                array(
                                    'name' => 'node3.local',
                                    'deactivated' => null,
                                    'catalog_timestamp' => '2014-01-31T16:00:24.713Z',
                                    'facts_timestamp' => '2014-01-31T16:00:23.604Z',
                                    'report_timestamp' => '2014-01-31T16:00:22.815Z',
                                ),
                            ));
                        }
                    case '/nodes/node1.local':
                        return $self->response(array(
                            'name' => 'node1.local',
                            'deactivated' => null,
                            'catalog_timestamp' => '2014-01-31T16:01:16.683Z',
                            'facts_timestamp' => '2014-01-31T16:01:15.628Z',
                            'report_timestamp' => '2014-01-31T16:01:15.507Z',
                        ));
                    case '/nodes/node2.local':
                        return $self->response(array(
                            'name' => 'node2.local',
                            'deactivated' => null,
                            'catalog_timestamp' => '2014-01-31T16:01:16.683Z',
                            'facts_timestamp' => '2014-01-31T16:01:15.628Z',
                            'report_timestamp' => '2014-01-31T16:01:15.507Z',
                        ));
                    case '/nodes/node3.local':
                        return $self->response(array(
                            'name' => 'node3.local',
                            'deactivated' => null,
                            'catalog_timestamp' => '2014-01-31T16:01:16.683Z',
                            'facts_timestamp' => '2014-01-31T16:01:15.628Z',
                            'report_timestamp' => '2014-01-31T16:01:15.507Z',
                        ));
                    case '/nodes/node1.local/facts':
                        return $self->response(array(array(
                            'certname' => 'node1.local',
                            'name' => 'operatingsystem',
                            'value' => 'Debian',
                        )));
                    case '/nodes/node2.local/facts':
                        return $self->response(array(array(
                            'certname' => 'node2.local',
                            'name' => 'operatingsystem',
                            'value' => 'Redhat',
                        )));
                    case '/nodes/node3.local/facts':
                        return $self->response(array(array(
                            'certname' => 'node2.local',
                            'name' => 'operatingsystem',
                            'value' => 'Debian',
                        )));
                    case '/event-counts':
                        $nodeName = $query[1][2];
                        switch ($nodeName) {
                            case 'node1.local':
                                return $self->response(array());
                            case 'node2.local':
                                return $self->response(array(array(
                                    'subject' => array('title' => 'node2.local'),
                                    'subject-type' => 'certname',
                                    'failures' => 0,
                                    'successes' => 1,
                                    'noops' => 0,
                                    'skips' => 0
                                )));
                            case 'node3.local':
                                return $self->response(array(array(
                                    'subject' => array('title' => 'node3.local'),
                                    'subject-type' => 'certname',
                                    'failures' => 3,
                                    'successes' => 0,
                                    'noops' => 0,
                                    'skips' => 0
                                )));
                        }
                }
                return $self->response(array('error' => $request->getFullUri() . ' : Not managed by PuppetDb Client Mock'));
            }));
        return $mock;
    }
}
