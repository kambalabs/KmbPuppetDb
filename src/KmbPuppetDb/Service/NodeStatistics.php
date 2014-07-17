<?php
/**
 * @copyright Copyright (c) 2014 Orange Applications for Business
 * @link      http://github.com/multimediabs/kamba for the canonical source repository
 *
 * This file is part of kamba.
 *
 * kamba is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * kamba is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with kamba.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace KmbPuppetDb\Service;

use KmbPuppetDb\Model;
use KmbPuppetDb\Service;

class NodeStatistics implements NodeStatisticsInterface
{
    /**
     * @var Service\Node
     */
    protected $nodeService;

    /**
     * @var int
     */
    protected $unchangedCount;

    /**
     * @var int
     */
    protected $changedCount;

    /**
     * @var int
     */
    protected $failedCount;

    /**
     * @var int
     */
    protected $nodesCount;

    /**
     * @var int
     */
    protected $osCount;

    /**
     * @var array
     */
    protected $nodesCountByOS;

    /**
     * @var array
     */
    protected $nodesPercentageByOS;

    /**
     * @var array
     */
    protected $recentlyRebootedNodes;

    /**
     * Get unchanged nodes count.
     *
     * @return int
     */
    public function getUnchangedCount()
    {
        return $this->getStatistic('unchangedCount');
    }

    /**
     * Get changed nodes count.
     *
     * @return int
     */
    public function getChangedCount()
    {
        return $this->getStatistic('changedCount');
    }

    /**
     * Get failed nodes count.
     *
     * @return int
     */
    public function getFailedCount()
    {
        return $this->getStatistic('failedCount');
    }

    /**
     * Get nodes count.
     *
     * @return int
     */
    public function getNodesCount()
    {
        return $this->getStatistic('nodesCount');
    }

    /**
     * Get nodes count grouped by Operating System.
     *
     * @return array
     */
    public function getNodesCountByOS()
    {
        return $this->getStatistic('nodesCountByOS');
    }

    /**
     * Get nodes percentage grouped by Operating System.
     *
     * @return array
     */
    public function getNodesPercentageByOS()
    {
        return $this->getStatistic('nodesPercentageByOS');
    }

    /**
     * Get OS count.
     *
     * @return int
     */
    public function getOSCount()
    {
        return $this->getStatistic('osCount');
    }

    /**
     * Get recently rebooted nodes.
     *
     * @return array
     */
    public function getRecentlyRebootedNodes()
    {
        return $this->getStatistic('recentlyRebootedNodes');
    }

    /**
     * Get all statistics as array.
     *
     * @return array
     */
    public function getAllAsArray()
    {
        return array(
            'unchangedCount' => $this->getUnchangedCount(),
            'changedCount' => $this->getChangedCount(),
            'failedCount' => $this->getFailedCount(),
            'nodesCount' => $this->getNodesCount(),
            'nodesCountByOS' => $this->getNodesCountByOS(),
            'nodesPercentageByOS' => $this->getNodesPercentageByOS(),
            'osCount' => $this->getOSCount(),
            'recentlyRebootedNodes' => $this->getRecentlyRebootedNodes(),
        );
    }

    /**
     * Get node service.
     *
     * @return Service\Node
     */
    public function getNodeService()
    {
        return $this->nodeService;
    }

    /**
     * Set node service.
     *
     * @param Service\Node $nodeService
     * @return NodeStatistics
     */
    public function setNodeService(Service\Node $nodeService)
    {
        $this->nodeService = $nodeService;
        return $this;
    }

    /**
     * @param $statistic
     * @return mixed
     */
    protected function getStatistic($statistic)
    {
        if ($this->$statistic == null) {
            $this->processStatistics();
        }
        return $this->$statistic;
    }

    /**
     * Browse all nodes and process all the statistics
     */
    protected function processStatistics()
    {
        $this->unchangedCount = 0;
        $this->changedCount = 0;
        $this->failedCount = 0;
        $this->nodesCountByOS = array();
        $this->nodesPercentageByOS = array();
        $this->recentlyRebootedNodes = array();
        $nodes = $this->getNodeService()->getAll();
        $this->nodesCount = count($nodes);
        foreach ($nodes as $node) {
            /** @var Model\Node $node */
            if ($node->getStatus() == Model\NodeInterface::UNCHANGED) {
                $this->unchangedCount++;
            } elseif ($node->getStatus() == Model\NodeInterface::CHANGED) {
                $this->changedCount++;
            } elseif ($node->getStatus() == Model\NodeInterface::FAILED) {
                $this->failedCount++;
            }
            if ($node->getFact('uptime_days') < 1) {
                $this->recentlyRebootedNodes[$node->getName()] = $node->getFact('uptime');
            }
            $osName = $node->hasFact('lsbdistdescription') ? $node->getFact('lsbdistdescription') : $node->getFact('operatingsystem');
            if (!array_key_exists($osName, $this->nodesCountByOS)) {
                $this->nodesCountByOS[$osName] = 0;
            }
            $this->nodesCountByOS[$osName]++;
            $this->nodesPercentageByOS[$osName] = round($this->nodesCountByOS[$osName] / $this->nodesCount, 2);
        }
        arsort($this->nodesCountByOS);
        arsort($this->nodesPercentageByOS);
        $this->osCount = count($this->nodesCountByOS);
    }
}
