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

use KmbPuppetDb\Query;

interface NodeStatisticsInterface
{
    /**
     * Get unchanged nodes count.
     *
     * @param Query|array $query
     * @return int
     */
    public function getUnchangedCount($query = null);

    /**
     * Get changed nodes count.
     *
     * @param Query|array $query
     * @return int
     */
    public function getChangedCount($query = null);

    /**
     * Get failed nodes count.
     *
     * @param Query|array $query
     * @return int
     */
    public function getFailedCount($query = null);

    /**
     * Get nodes count.
     *
     * @param Query|array $query
     * @return int
     */
    public function getNodesCount($query = null);

    /**
     * Get nodes count grouped by Operating System.
     *
     * @param Query|array $query
     * @return array
     */
    public function getNodesCountByOS($query = null);

    /**
     * Get nodes percentage grouped by Operating System.
     *
     * @param Query|array $query
     * @return array
     */
    public function getNodesPercentageByOS($query = null);

    /**
     * Get OS count.
     *
     * @param Query|array $query
     * @return int
     */
    public function getOSCount($query = null);

    /**
     * Get recently rebooted nodes.
     *
     * @param Query|array $query
     * @return array
     */
    public function getRecentlyRebootedNodes($query = null);

    /**
     * Get all statistics as array.
     *
     * @param Query|array $query
     * @return array
     */
    public function getAllAsArray($query = null);
}
