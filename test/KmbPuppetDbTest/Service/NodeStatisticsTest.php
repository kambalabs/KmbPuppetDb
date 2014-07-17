<?php
namespace KmbPuppetDbTest\Service;

use KmbPuppetDb\Model\Node;
use KmbPuppetDb\Model\NodeInterface;
use KmbPuppetDb\Service\NodeStatistics;

class NodeStatisticsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var NodeStatistics
     */
    protected $nodeStatistics;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $nodeServiceMock;

    protected function setUp()
    {
        $this->nodeServiceMock = $this->getMock('KmbPuppetDb\Service\Node');
        $this->nodeStatistics = new NodeStatistics();
        $this->nodeStatistics->setNodeService($this->nodeServiceMock);
    }

    /** @test */
    public function canGetUnchangedCount()
    {
        $this->nodeServiceMock->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue(array(
                'node1.local' => new Node('node1.local', NodeInterface::UNCHANGED),
                'node2.local' => new Node('node2.local', NodeInterface::UNCHANGED),
                'node3.local' => new Node('node3.local', NodeInterface::FAILED),
            )));

        $this->assertEquals(2, $this->nodeStatistics->getUnchangedCount());
    }

    /** @test */
    public function canGetChangedCount()
    {
        $this->nodeServiceMock->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue(array(
                'node1.local' => new Node('node1.local', NodeInterface::UNCHANGED),
                'node2.local' => new Node('node2.local', NodeInterface::UNCHANGED),
                'node3.local' => new Node('node3.local', NodeInterface::CHANGED),
            )));

        $this->assertEquals(1, $this->nodeStatistics->getChangedCount());
    }

    /** @test */
    public function canGetFailedCount()
    {
        $this->nodeServiceMock->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue(array(
                'node1.local' => new Node('node1.local', NodeInterface::FAILED),
                'node2.local' => new Node('node2.local', NodeInterface::FAILED),
                'node3.local' => new Node('node3.local', NodeInterface::FAILED),
            )));

        $this->assertEquals(3, $this->nodeStatistics->getFailedCount());
    }

    /** @test */
    public function canGetNodesCount()
    {
        $this->nodeServiceMock->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue(array(
                'node1.local' => new Node('node1.local'),
                'node2.local' => new Node('node2.local'),
                'node3.local' => new Node('node3.local'),
            )));

        $this->assertEquals(3, $this->nodeStatistics->getNodesCount());
    }

    /** @test */
    public function canGetNodesCountByOS()
    {
        $now = new \DateTime();
        $this->nodeServiceMock->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue(array(
                'node1.local' => new Node('node1.local', NodeInterface::FAILED, $now, array(
                        'operatingsystem' => 'Debian',
                        'lsbdistdescription' => 'Debian GNU/Linux 6.0.7 (squeeze)',
                    )),
                'node2.local' => new Node('node2.local', NodeInterface::FAILED, $now, array(
                        'operatingsystem' => 'Debian',
                        'lsbdistdescription' => 'Debian GNU/Linux 6.0.7 (squeeze)',
                    )),
                'node3.local' => new Node('node3.local', NodeInterface::FAILED, $now, array(
                        'operatingsystem' => 'Debian',
                        'lsbdistdescription' => 'Debian GNU/Linux 7.4 (wheezy)',
                    )),
                'node4.local' => new Node('node4.local', NodeInterface::FAILED, $now, array('operatingsystem' => 'windows')),
                'node5.local' => new Node('node5.local', NodeInterface::FAILED, $now, array('operatingsystem' => 'windows')),
            )));

        $expectedStat = array(
            'Debian GNU/Linux 6.0.7 (squeeze)' => 2,
            'windows' => 2,
            'Debian GNU/Linux 7.4 (wheezy)' => 1,
        );
        $this->assertEquals($expectedStat, $this->nodeStatistics->getNodesCountByOS());
    }

    /** @test */
    public function canGetNodesPercentageByOS()
    {
        $now = new \DateTime();
        $this->nodeServiceMock->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue(array(
                'node1.local' => new Node('node1.local', NodeInterface::FAILED, $now, array(
                        'operatingsystem' => 'Debian',
                        'lsbdistdescription' => 'Debian GNU/Linux 6.0.7 (squeeze)',
                    )),
                'node2.local' => new Node('node2.local', NodeInterface::FAILED, $now, array(
                        'operatingsystem' => 'Debian',
                        'lsbdistdescription' => 'Debian GNU/Linux 6.0.7 (squeeze)',
                    )),
                'node3.local' => new Node('node3.local', NodeInterface::FAILED, $now, array(
                        'operatingsystem' => 'Debian',
                        'lsbdistdescription' => 'Debian GNU/Linux 7.4 (wheezy)',
                    )),
                'node4.local' => new Node('node4.local', NodeInterface::FAILED, $now, array('operatingsystem' => 'windows')),
                'node5.local' => new Node('node5.local', NodeInterface::FAILED, $now, array('operatingsystem' => 'windows')),
            )));

        $expectedStat = array(
            'Debian GNU/Linux 6.0.7 (squeeze)' => 0.40,
            'windows' => 0.40,
            'Debian GNU/Linux 7.4 (wheezy)' => 0.20,
        );
        $this->assertEquals($expectedStat, $this->nodeStatistics->getNodesPercentageByOS());
    }

    /** @test */
    public function canGetOSCount()
    {
        $now = new \DateTime();
        $this->nodeServiceMock->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue(array(
                'node1.local' => new Node('node1.local', NodeInterface::FAILED, $now, array(
                        'operatingsystem' => 'Debian',
                        'lsbdistdescription' => 'Debian GNU/Linux 6.0.7 (squeeze)',
                    )),
                'node2.local' => new Node('node2.local', NodeInterface::FAILED, $now, array(
                        'operatingsystem' => 'Debian',
                        'lsbdistdescription' => 'Debian GNU/Linux 6.0.7 (squeeze)',
                    )),
                'node3.local' => new Node('node3.local', NodeInterface::FAILED, $now, array(
                        'operatingsystem' => 'Debian',
                        'lsbdistdescription' => 'Debian GNU/Linux 7.4 (wheezy)',
                    )),
                'node4.local' => new Node('node4.local', NodeInterface::FAILED, $now, array('operatingsystem' => 'windows')),
                'node5.local' => new Node('node5.local', NodeInterface::FAILED, $now, array('operatingsystem' => 'windows')),
            )));

        $this->assertEquals(3, $this->nodeStatistics->getOSCount());
    }

    /** @test */
    public function canGetRecentlyRebootedNodes()
    {
        $now = new \DateTime();
        $this->nodeServiceMock->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue(array(
                'node1.local' => new Node('node1.local', NodeInterface::FAILED, $now, array(
                        'uptime_days' => '21',
                        'uptime' => '',
                    )),
                'node2.local' => new Node('node2.local', NodeInterface::FAILED, $now, array(
                        'uptime_days' => '0',
                        'uptime' => '2:32 hours',
                    )),
                'node3.local' => new Node('node3.local', NodeInterface::FAILED, $now, array(
                        'uptime_days' => '1',
                        'uptime' => '',
                    )),
                'node4.local' => new Node('node4.local', NodeInterface::FAILED, $now, array(
                        'uptime_days' => '0',
                        'uptime' => '4:01 hours',
                    )),
                'node5.local' => new Node('node5.local', NodeInterface::FAILED, $now, array(
                        'uptime_days' => '233',
                        'uptime' => '',
                    )),
            )));

        $expectedStat = array('node2.local' => '2:32 hours',  'node4.local' => '4:01 hours');
        $this->assertEquals($expectedStat, $this->nodeStatistics->getRecentlyRebootedNodes());
    }

    /** @test */
    public function canGetAllAsArray()
    {
        $now = new \DateTime();
        $this->nodeServiceMock->expects($this->any())
            ->method('getAll')
            ->will($this->returnValue(array(
                'node1.local' => new Node('node1.local', NodeInterface::UNCHANGED, $now, array(
                        'operatingsystem' => 'Debian',
                        'lsbdistdescription' => 'Debian GNU/Linux 6.0.7 (squeeze)',
                        'uptime_days' => '21',
                        'uptime' => '',
                    )),
                'node2.local' => new Node('node2.local', NodeInterface::FAILED, $now, array(
                        'operatingsystem' => 'Debian',
                        'lsbdistdescription' => 'Debian GNU/Linux 7.4 (wheezy)',
                        'uptime_days' => '0',
                        'uptime' => '2:32 hours',
                    )),
                'node3.local' => new Node('node3.local', NodeInterface::UNCHANGED, $now, array(
                        'operatingsystem' => 'windows',
                        'uptime_days' => '1',
                        'uptime' => '',
                    )),
                'node4.local' => new Node('node4.local', NodeInterface::CHANGED, $now, array(
                        'operatingsystem' => 'Debian',
                        'lsbdistdescription' => 'Debian GNU/Linux 6.0.7 (squeeze)',
                        'uptime_days' => '0',
                        'uptime' => '4:01 hours',
                    )),
                'node5.local' => new Node('node5.local', NodeInterface::UNCHANGED, $now, array(
                        'operatingsystem' => 'windows',
                        'uptime_days' => '233',
                        'uptime' => '',
                    )),
            )));

        $expectedStats = array(
            'unchangedCount' => 3,
            'changedCount' => 1,
            'failedCount' => 1,
            'nodesCount' => 5,
            'nodesCountByOS' => array(
                'Debian GNU/Linux 6.0.7 (squeeze)' => 2,
                'windows' => 2,
                'Debian GNU/Linux 7.4 (wheezy)' => 1,
            ),
            'nodesPercentageByOS' => array(
                'Debian GNU/Linux 6.0.7 (squeeze)' => 0.40,
                'windows' => 0.40,
                'Debian GNU/Linux 7.4 (wheezy)' => 0.20,
            ),
            'osCount' => 3,
            'recentlyRebootedNodes' => array('node2.local' => '2:32 hours',  'node4.local' => '4:01 hours'),
        );
        $this->assertEquals($expectedStats, $this->nodeStatistics->getAllAsArray());
    }
}
