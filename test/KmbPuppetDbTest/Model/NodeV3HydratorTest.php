<?php
namespace KmbPuppetDbTest\Model;

use KmbPuppetDb\Model\Node;
use KmbPuppetDb\Model\NodeInterface;
use KmbPuppetDb\Model\NodeV3Hydrator;

class NodeV3HydratorTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canHydrateWithNodeData()
    {
        $node = new Node();
        $hydrator = new NodeV3Hydrator();

        $hydrator->hydrate([
            'name' => 'node1.local',
            'deactivated' => null,
            'catalog_timestamp' => '2014-01-31T16:01:16.683Z',
            'facts_timestamp' => '2014-01-31T16:01:15.628Z',
            'report_timestamp' => '2014-01-31T16:01:15.507Z',
        ], $node);

        $this->assertEquals('node1.local', $node->getName());
        $this->assertEquals('2014-01-31 16:01:15.628000', $node->getReportedAt()->format('Y-m-d H:i:s.u'));
    }

    /** @test */
    public function canHydrateWithNodeStatus()
    {
        $node = new Node();
        $hydrator = new NodeV3Hydrator();

        $hydrator->hydrate([
            'status' => NodeInterface::UNCHANGED,
        ], $node);

        $this->assertEquals(NodeInterface::UNCHANGED, $node->getStatus());
    }

    /** @test */
    public function canHydrateWithFacts()
    {
        $node = new Node();
        $hydrator = new NodeV3Hydrator();

        $hydrator->hydrate([
            'facts' => [
                NodeInterface::ENVIRONMENT_FACT => 'STABLE_PF1',
                'uptime_days' => 477,
                'operatingsystem' => 'Debian',
            ]
        ], $node);

        $this->assertEquals([
            NodeInterface::ENVIRONMENT_FACT => 'STABLE_PF1',
            'uptime_days' => 477,
            'operatingsystem' => 'Debian',
        ], $node->getFacts());
        $this->assertEquals('STABLE_PF1', $node->getEnvironment());
    }

    /** @test */
    public function canExtract()
    {
        $node = new Node('node1.local', null, null, ['operatingsystem' => 'Debian', NodeInterface::ENVIRONMENT_FACT => 'DEFAULT'], 'STABLE_PF1');
        $hydrator = new NodeV3Hydrator();

        $data = $hydrator->extract($node);

        $this->assertEquals([
            'name' => 'node1.local',
            'environment' => 'STABLE_PF1',
            'values' => [
                'operatingsystem' => 'Debian',
                NodeInterface::ENVIRONMENT_FACT => 'STABLE_PF1',
            ],
        ], $data);
    }
}
