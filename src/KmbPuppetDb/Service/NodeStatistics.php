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
use KmbPuppetDb\Query\Query;
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
     * @param \KmbPuppetDb\Query\Query|array $query
     * @return int
     */
    public function getUnchangedCount($query = null)
    {
        return $this->getStatistic('unchangedCount', $query);
    }

    /**
     * Get changed nodes count.
     *
     * @param \KmbPuppetDb\Query\Query|array $query
     * @return int
     */
    public function getChangedCount($query = null)
    {
        return $this->getStatistic('changedCount', $query);
    }

    /**
     * Get failed nodes count.
     *
     * @param \KmbPuppetDb\Query\Query|array $query
     * @return int
     */
    public function getFailedCount($query = null)
    {
        return $this->getStatistic('failedCount', $query);
    }

    /**
     * Get nodes count.
     *
     * @param \KmbPuppetDb\Query\Query|array $query
     * @return int
     */
    public function getNodesCount($query = null)
    {
        return $this->getStatistic('nodesCount', $query);
    }

    /**
     * Get nodes count grouped by Operating System.
     *
     * @param \KmbPuppetDb\Query\Query|array $query
     * @return array
     */
    public function getNodesCountByOS($query = null)
    {
        return $this->getStatistic('nodesCountByOS', $query);
    }

    /**
     * Get nodes percentage grouped by Operating System.
     *
     * @param \KmbPuppetDb\Query\Query|array $query
     * @return array
     */
    public function getNodesPercentageByOS($query = null)
    {
        return $this->getStatistic('nodesPercentageByOS', $query);
    }

    /**
     * Get OS count.
     *
     * @param \KmbPuppetDb\Query\Query|array $query
     * @return int
     */
    public function getOSCount($query = null)
    {
        return $this->getStatistic('osCount', $query);
    }

    /**
     * Get recently rebooted nodes.
     *
     * @param \KmbPuppetDb\Query\Query|array $query
     * @return array
     */
    public function getRecentlyRebootedNodes($query = null)
    {
        return $this->getStatistic('recentlyRebootedNodes', $query);
    }

    /**
     * Get all statistics as array.
     *
     * @param \KmbPuppetDb\Query\Query|array $query
     * @return array
     */
    public function getAllAsArray($query = null)
    {
        return [
            'unchangedCount' => $this->getUnchangedCount($query),
            'changedCount' => $this->getChangedCount($query),
            'failedCount' => $this->getFailedCount($query),
            'nodesCount' => $this->getNodesCount($query),
            'nodesCountByOS' => $this->getNodesCountByOS($query),
            'nodesPercentageByOS' => $this->getNodesPercentageByOS($query),
            'osCount' => $this->getOSCount($query),
            'recentlyRebootedNodes' => $this->getRecentlyRebootedNodes($query),
        ];
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
     * @param \KmbPuppetDb\Query\Query|array $query
     * @return mixed
     */
    protected function getStatistic($statistic, $query = null)
    {
        if ($this->$statistic == null) {
            $this->processStatistics($query);
        }
        return $this->$statistic;
    }

    /**
     * Browse all nodes and process all the statistics
     *
     * @param \KmbPuppetDb\Query\Query|array $query
     */
    protected function processStatistics($query = null)
    {
        $this->unchangedCount = 0;
        $this->changedCount = 0;
        $this->failedCount = 0;
        $this->nodesCountByOS = [];
        $this->nodesPercentageByOS = [];
        $this->recentlyRebootedNodes = [];
        $nodes = $this->getNodeService()->getAll($query);
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
