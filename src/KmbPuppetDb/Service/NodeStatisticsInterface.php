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

interface NodeStatisticsInterface
{
    /**
     * Get unchanged nodes count.
     *
     * @return int
     */
    public function getUnchangedCount();

    /**
     * Get changed nodes count.
     *
     * @return int
     */
    public function getChangedCount();

    /**
     * Get failed nodes count.
     *
     * @return int
     */
    public function getFailedCount();

    /**
     * Get nodes count.
     *
     * @return int
     */
    public function getNodesCount();

    /**
     * Get nodes count grouped by Operating System.
     *
     * @return array
     */
    public function getNodesCountByOS();

    /**
     * Get nodes percentage grouped by Operating System.
     *
     * @return array
     */
    public function getNodesPercentageByOS();

    /**
     * Get OS count.
     *
     * @return int
     */
    public function getOSCount();

    /**
     * Get recently rebooted nodes.
     *
     * @return array
     */
    public function getRecentlyRebootedNodes();

    /**
     * Get all statistics as array.
     *
     * @return array
     */
    public function getAllAsArray();
}
