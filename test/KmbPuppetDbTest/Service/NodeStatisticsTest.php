<?php
namespace KmbPuppetDbTest\Service;

use KmbPuppetDb\Model\Node;
use KmbPuppetDb\Model\NodeInterface;
use KmbPuppetDb\Model\NodesCollection;
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
        $this->nodeServiceMock->expects($this->any())
            ->method('getAll')
            ->will($this->returnCallback(function ($query = null) {
                $now = new \DateTime();
                $nodes = [
                    new Node('node1.local', NodeInterface::UNCHANGED, $now, [
                            'operatingsystem' => 'Debian',
                            'lsbdistdescription' => 'Debian GNU/Linux 6.0.7 (squeeze)',
                            'uptime_days' => '21',
                            'uptime' => '',
                            'kmbenv' => 'STABLE_PF1',
                        ]),
                    new Node('node2.local', NodeInterface::FAILED, $now, [
                            'operatingsystem' => 'Debian',
                            'lsbdistdescription' => 'Debian GNU/Linux 7.4 (wheezy)',
                            'uptime_days' => '0',
                            'uptime' => '2:32 hours',
                            'kmbenv' => 'STABLE_PF2',
                        ]),
                    new Node('node3.local', NodeInterface::UNCHANGED, $now, [
                            'operatingsystem' => 'windows',
                            'uptime_days' => '1',
                            'uptime' => '',
                            'kmbenv' => 'STABLE_PF2',
                        ]),
                    new Node('node4.local', NodeInterface::CHANGED, $now, [
                            'operatingsystem' => 'Debian',
                            'lsbdistdescription' => 'Debian GNU/Linux 6.0.7 (squeeze)',
                            'uptime_days' => '0',
                            'uptime' => '4:01 hours',
                            'kmbenv' => 'STABLE_PF2',
                        ]),
                    new Node('node5.local', NodeInterface::UNCHANGED, $now, [
                            'operatingsystem' => 'windows',
                            'uptime_days' => '233',
                            'uptime' => '',
                            'kmbenv' => 'STABLE_PF3',
                        ]),
                ];
                if ($query !== null) {
                    return NodesCollection::factory(array_slice($nodes, 1, 3), 3);
                }
                return NodesCollection::factory($nodes, 5);
            }));
    }

    /** @test */
    public function canGetUnchangedCount()
    {
        $this->assertEquals(3, $this->nodeStatistics->getUnchangedCount());
    }

    /** @test */
    public function canGetChangedCount()
    {
        $this->assertEquals(1, $this->nodeStatistics->getChangedCount());
    }

    /** @test */
    public function canGetFailedCount()
    {
        $this->assertEquals(1, $this->nodeStatistics->getFailedCount());
    }

    /** @test */
    public function canGetNodesCount()
    {
        $this->assertEquals(5, $this->nodeStatistics->getNodesCount());
    }

    /** @test */
    public function canGetNodesCountByOS()
    {
        $expectedStat = [
            'Debian GNU/Linux 6.0.7 (squeeze)' => 2,
            'windows' => 2,
            'Debian GNU/Linux 7.4 (wheezy)' => 1,
        ];
        $this->assertEquals($expectedStat, $this->nodeStatistics->getNodesCountByOS());
    }

    /** @test */
    public function canGetNodesPercentageByOS()
    {
        $expectedStat = [
            'Debian GNU/Linux 6.0.7 (squeeze)' => 0.40,
            'windows' => 0.40,
            'Debian GNU/Linux 7.4 (wheezy)' => 0.20,
        ];
        $this->assertEquals($expectedStat, $this->nodeStatistics->getNodesPercentageByOS());
    }

    /** @test */
    public function canGetOSCount()
    {
        $this->assertEquals(3, $this->nodeStatistics->getOSCount());
    }

    /** @test */
    public function canGetRecentlyRebootedNodes()
    {
        $expectedStat = ['node2.local' => '2:32 hours', 'node4.local' => '4:01 hours'];
        $this->assertEquals($expectedStat, $this->nodeStatistics->getRecentlyRebootedNodes());
    }

    /** @test */
    public function canGetAllAsArray()
    {
        $expectedStats = [
            'unchangedCount' => 3,
            'changedCount' => 1,
            'failedCount' => 1,
            'nodesCount' => 5,
            'nodesCountByOS' => [
                'Debian GNU/Linux 6.0.7 (squeeze)' => 2,
                'windows' => 2,
                'Debian GNU/Linux 7.4 (wheezy)' => 1,
            ],
            'nodesPercentageByOS' => [
                'Debian GNU/Linux 6.0.7 (squeeze)' => 0.40,
                'windows' => 0.40,
                'Debian GNU/Linux 7.4 (wheezy)' => 0.20,
            ],
            'osCount' => 3,
            'recentlyRebootedNodes' => ['node2.local' => '2:32 hours', 'node4.local' => '4:01 hours'],
        ];
        $this->assertEquals($expectedStats, $this->nodeStatistics->getAllAsArray());
    }

    /** @test */
    public function canGetAllAsArrayWithQuery()
    {
        $expectedStats = [
            'unchangedCount' => 1,
            'changedCount' => 1,
            'failedCount' => 1,
            'nodesCount' => 3,
            'nodesCountByOS' => [
                'Debian GNU/Linux 6.0.7 (squeeze)' => 1,
                'windows' => 1,
                'Debian GNU/Linux 7.4 (wheezy)' => 1,
            ],
            'nodesPercentageByOS' => [
                'Debian GNU/Linux 6.0.7 (squeeze)' => 0.33,
                'windows' => 0.33,
                'Debian GNU/Linux 7.4 (wheezy)' => 0.33,
            ],
            'osCount' => 3,
            'recentlyRebootedNodes' => ['node2.local' => '2:32 hours', 'node4.local' => '4:01 hours'],
        ];
        $this->assertEquals($expectedStats, $this->nodeStatistics->getAllAsArray(['=', ['fact', NodeInterface::ENVIRONMENT_FACT], 'STABLE_PF2']));
    }
}
