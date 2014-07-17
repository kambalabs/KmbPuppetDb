<?php
namespace KmbPuppetDbTest\Model;

use KmbPuppetDb\Model\Node;
use KmbPuppetDb\Model\NodesCollection;

class NodesCollectionTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canCreateCollection()
    {
        $nodes = array(new Node('node1.local'));

        $collection = NodesCollection::factory($nodes, 10);

        $this->assertInstanceOf('KmbPuppetDb\Model\NodesCollection', $collection);
        $this->assertEquals($nodes, $collection->getNodes());
        $this->assertEquals(10, $collection->getTotal());
    }

    /** @test */
    public function canIterateOnNodesCollection()
    {
        $nodes = array(new Node('node1.local'), new Node('node2.local'));
        $collection = NodesCollection::factory($nodes, 10);

        $nodesNames = array();
        foreach ($collection as $node) {
            /** @var Node $node */
            $nodesNames[] = $node->getName();
        }
        $collection->rewind();

        $this->assertEquals(array('node1.local', 'node2.local'), $nodesNames);
        $this->assertEquals(0, $collection->key());
    }

    /** @test */
    public function canCountNodesCollection()
    {
        $nodes = array(new Node('node1.local'), new Node('node2.local'));
        $collection = NodesCollection::factory($nodes, 10);

        $this->assertEquals(2, count($collection));
    }

    /** @test */
    public function canGetNodeByName()
    {
        $nodes = array(new Node('node1.local'), new Node('node2.local'));
        $collection = NodesCollection::factory($nodes);

        $this->assertEquals($nodes[1], $collection->getNodeByName('node2.local'));
    }

    /** @test */
    public function canGetNodeByIndex()
    {
        $nodes = array(new Node('node1.local'), new Node('node2.local'));
        $collection = NodesCollection::factory($nodes);

        $this->assertEquals($nodes[1], $collection->getNodeByIndex(1));
    }
}
