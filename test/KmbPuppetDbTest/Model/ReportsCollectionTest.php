<?php
namespace KmbPuppetDbTest\Model;

use KmbPuppetDb\Model\Report;
use KmbPuppetDb\Model\ReportsCollection;

class ReportsCollectionTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canCreateCollection()
    {
        $data = array(new Report('success', 'node1.local'));

        $collection = ReportsCollection::factory($data, 10);

        $this->assertInstanceOf('KmbPuppetDb\Model\ReportsCollection', $collection);
        $this->assertEquals($data, $collection->getData());
        $this->assertEquals(10, $collection->getTotal());
    }

    /** @test */
    public function canIterateOnCollection()
    {
        $data = array(new Report('success', 'node1.local'), new Report('success', 'node2.local'));
        $collection = ReportsCollection::factory($data, 10);

        $nodesNames = array();
        foreach ($collection as $report) {
            /** @var Report $report */
            $nodesNames[] = $report->getNodeName();
        }
        $collection->rewind();

        $this->assertEquals(array('node1.local', 'node2.local'), $nodesNames);
        $this->assertEquals(0, $collection->key());
    }

    /** @test */
    public function canCountReportsCollection()
    {
        $data = array(new Report('success', 'node1.local'), new Report('success', 'node2.local'));
        $collection = ReportsCollection::factory($data, 10);

        $this->assertEquals(2, count($collection));
    }

    /** @test */
    public function canGetByIndex()
    {
        $data = array(new Report('node1.local'), new Report('node2.local'));
        $collection = ReportsCollection::factory($data);

        $this->assertEquals($data[1], $collection->get(1));
    }
}
