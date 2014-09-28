<?php
namespace Proxy;

use KmbPuppetDb\Model\Node;
use KmbPuppetDb\Model\NodeInterface;
use KmbPuppetDb\Proxy\NodeProxy;

class NodeProxyTest extends \PHPUnit_Framework_TestCase
{
    /** @var  NodeProxy */
    protected $proxy;

    /** @var  Node */
    protected $node;

    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    protected $nodeService;

    protected function setUp()
    {
        $this->nodeService = $this->getMock('KmbPuppetDb\Service\NodeInterface');
        $this->node = new Node('node1.local');
        $this->proxy = new NodeProxy();
        $this->proxy->setNode($this->node);
        $this->proxy->setNodeService($this->nodeService);
    }

    /** @test */
    public function canGetName()
    {
        $this->assertEquals('node1.local', $this->proxy->getName());
    }

    /** @test */
    public function canSetName()
    {
        $this->proxy->setName('node2.local');

        $this->assertEquals('node2.local', $this->node->getName());
    }

    /** @test */
    public function canGetFactsFromNodeService()
    {
        $facts = [
            'architecture' => 'amd64',
            NodeInterface::ENVIRONMENT_FACT => 'STABLE_PF1',
        ];
        $this->nodeService->expects($this->any())
            ->method('getNodeFacts')
            ->with('node1.local')
            ->will($this->returnValue($facts));

        $this->assertEquals($facts, $this->proxy->getFacts());
    }

    /** @test */
    public function canGetEnvironmentFromNodeService()
    {
        $this->nodeService->expects($this->any())
            ->method('getNodeFacts')
            ->with('node1.local')
            ->will($this->returnValue([NodeInterface::ENVIRONMENT_FACT => 'STABLE_PF1']));

        $this->assertEquals('STABLE_PF1', $this->proxy->getEnvironment());
    }
    /** @test */
    public function canAddFact()
    {
        $this->proxy->addFact('operatingsystem', 'Debian');

        $this->assertEquals(['operatingsystem' => 'Debian'], $this->proxy->getFacts());
    }

    /** @test */
    public function canGetFact()
    {
        $this->proxy->addFact('operatingsystem', 'Debian');

        $this->assertEquals('Debian', $this->proxy->getFact('operatingsystem'));
    }

    /** @test */
    public function canGetFactFromNodeService()
    {
        $this->nodeService->expects($this->any())
            ->method('getNodeFacts')
            ->with('node1.local')
            ->will($this->returnValue(['operatingsystem' => 'Debian']));

        $this->assertEquals('Debian', $this->proxy->getFact('operatingsystem'));
    }

    /** @test */
    public function cannotGetUnknownFact()
    {
        $this->proxy->addFact('operatingsystem', 'Debian');

        $this->assertNull($this->proxy->getFact('unknown'));
    }

    /** @test */
    public function canDetermineIfNodeHasNotSpecifiedFact()
    {
        $this->proxy->addFact('operatingsystem', 'Debian');

        $this->assertFalse($this->proxy->hasFact('unknown'));
    }

    /** @test */
    public function canDetermineIfNodeHasSpecifiedFact()
    {
        $this->proxy->addFact('operatingsystem', 'Debian');

        $this->assertTrue($this->proxy->hasFact('operatingsystem'));
    }

    /** @test */
    public function canGetStatusFromNodeService()
    {
        $this->nodeService->expects($this->any())
            ->method('getNodeStatus')
            ->with('node1.local')
            ->will($this->returnValue(NodeInterface::CHANGED));

        $this->assertEquals(NodeInterface::CHANGED, $this->proxy->getStatus());
    }

    /** @test */
    public function canHasStatusFromNodeService()
    {
        $this->nodeService->expects($this->any())
            ->method('getNodeStatus')
            ->with('node1.local')
            ->will($this->returnValue(NodeInterface::CHANGED));

        $this->assertTrue($this->proxy->hasStatus(NodeInterface::CHANGED));
    }
}
