<?php
namespace KmbPuppetDbTest\Model;

use KmbPuppetDb\Model\Node;
use KmbPuppetDb\Model\NodeInterface;

class NodeTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canAddFact()
    {
        $node = new Node();

        $node->addFact('operatingsystem', 'Debian');

        $this->assertEquals(array('operatingsystem' => 'Debian'), $node->getFacts());
    }

    /** @test */
    public function canGetFact()
    {
        $node = new Node();
        $node->addFact('operatingsystem', 'Debian');

        $this->assertEquals('Debian', $node->getFact('operatingsystem'));
    }

    /** @test */
    public function cannotGetUnknownFact()
    {
        $node = new Node();

        $this->assertNull($node->getFact('unknown'));
    }

    /** @test */
    public function canDetermineIfNodeHasNotSpecifiedFact()
    {
        $node = new Node();

        $this->assertFalse($node->hasFact('operatingsystem'));
    }

    /** @test */
    public function canDetermineIfNodeHasSpecifiedFact()
    {
        $node = new Node();
        $node->addFact('operatingsystem', 'Debian');

        $this->assertTrue($node->hasFact('operatingsystem'));
    }

    /** @test */
    public function canDetermineIfNodeHasNotSpecifiedStatus()
    {
        $node = new Node();
        $node->setStatus(NodeInterface::CHANGED);

        $this->assertFalse($node->hasStatus(NodeInterface::UNCHANGED));
    }

    /** @test */
    public function canDetermineIfNodeHasSpecifiedStatus()
    {
        $node = new Node();
        $node->setStatus(NodeInterface::CHANGED);

        $this->assertTrue($node->hasStatus(NodeInterface::CHANGED));
    }
}
