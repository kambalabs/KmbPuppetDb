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

interface ReportInterface
{
    /**
     * Retrieves all reports matching with specified query, paging and sorting.
     *
     * @param mixed $query
     * @param int   $offset
     * @param mixed $limit
     * @param array $orderBy
     * @return Model\ReportsCollection
     */
    public function getAll($query = null, $offset = null, $limit = null, $orderBy = null);

    /**
     * Retrieves all reports for the current day and matching with specified query, paging and sorting.
     *
     * @param mixed $query
     * @param int   $offset
     * @param mixed $limit
     * @param array $orderBy
     * @return Model\ReportsCollection
     */
    public function getAllForToday($query = null, $offset = null, $limit = null, $orderBy = null);
}
