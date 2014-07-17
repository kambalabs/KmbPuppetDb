<?php
namespace KmbPuppetDbTest\Model;

use KmbPuppetDb\Model\Node;
use KmbPuppetDb\Model\NodeHydrator;
use KmbPuppetDb\Model\NodeInterface;

class NodeHydratorTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canHydrateWithNodeData()
    {
        $node = new Node();
        $hydrator = new NodeHydrator();

        $hydrator->hydrate(array(
            'name' => 'node1.local',
            'deactivated' => null,
            'catalog_timestamp' => '2014-01-31T16:01:16.683Z',
            'facts_timestamp' => '2014-01-31T16:01:15.628Z',
            'report_timestamp' => '2014-01-31T16:01:15.507Z',
        ), $node);

        $this->assertEquals('node1.local', $node->getName());
        $this->assertEquals('2014-01-31 16:01:15.628000', $node->getReportedAt()->format('Y-m-d H:i:s.u'));
    }

    /** @test */
    public function canHydrateWithNodeStatus()
    {
        $node = new Node();
        $hydrator = new NodeHydrator();

        $hydrator->hydrate(array(
            'status' => NodeInterface::UNCHANGED,
        ), $node);

        $this->assertEquals(NodeInterface::UNCHANGED, $node->getStatus());
    }

    /** @test */
    public function canHydrateWithFacts()
    {
        $node = new Node();
        $hydrator = new NodeHydrator();

        $hydrator->hydrate(array(
            'facts' => array(
                'uptime_days' => 477,
                'operatingsystem' => 'Debian',
            )
        ), $node);

        $this->assertEquals(array(
            'uptime_days' => 477,
            'operatingsystem' => 'Debian',
        ), $node->getFacts());
    }
}
