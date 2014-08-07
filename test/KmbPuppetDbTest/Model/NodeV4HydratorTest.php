<?php
namespace KmbPuppetDbTest\Model;

use KmbPuppetDb\Model\Node;
use KmbPuppetDb\Model\NodeV4Hydrator;

class NodeV4HydratorTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canHydrateWithNodeData()
    {
        $node = new Node();
        $hydrator = new NodeV4Hydrator();

        $hydrator->hydrate([
            'name' => 'node1.local',
            'deactivated' => null,
            'catalog-timestamp' => '2014-01-31T16:01:16.683Z',
            'catalog-environment' => 'STABLE_PF1',
            'facts-timestamp' => '2014-01-31T16:01:15.628Z',
            'facts-environment' => 'STABLE_PF1',
            'report-timestamp' => '2014-01-31T16:01:15.507Z',
            'report-environment' => 'STABLE_PF1',
        ], $node);

        $this->assertEquals('node1.local', $node->getName());
        $this->assertEquals('2014-01-31 16:01:15.628000', $node->getReportedAt()->format('Y-m-d H:i:s.u'));
        $this->assertEquals('STABLE_PF1', $node->getEnvironment());
    }

    /**
     * In the documentation of API v4, they say that the node name is returned in the « certname » field.
     * It's not the reality but in case of they change that, we accept both « certname » and « name ».
     * @test
     */
    public function canHydrateWithCertname()
    {
        $node = new Node();
        $hydrator = new NodeV4Hydrator();

        $hydrator->hydrate([
            'certname' => 'node1.local',
        ], $node);

        $this->assertEquals('node1.local', $node->getName());
    }
}
