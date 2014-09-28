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
use KmbPuppetDb;

interface NodeInterface
{
    /**
     * Retrieves a node by its name.
     *
     * @param string $name
     * @return Model\NodeInterface
     */
    public function getByName($name);

    /**
     * Retrieves all nodes matching with specified query, paging and sorting.
     * All nodes are returned if parameters are null.
     *
     * @param mixed   $query
     * @param int     $offset
     * @param mixed   $limit
     * @param array   $orderBy
     * @return Model\NodesCollection
     */
    public function getAll($query = null, $offset = null, $limit = null, $orderBy = null);

    /**
     * @param string $nodeName
     * @return string
     */
    public function getNodeStatus($nodeName);

    /**
     * @param string $nodeName
     * @return array
     */
    public function getNodeFacts($nodeName);

    /**
     * @param Model\NodeInterface $node
     * @return NodeInterface
     */
    public function replaceFacts(Model\NodeInterface $node);
}
