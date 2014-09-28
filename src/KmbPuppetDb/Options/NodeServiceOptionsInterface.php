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
namespace KmbPuppetDb\Options;

interface NodeServiceOptionsInterface
{
    /**
     * Get node entity class name.
     *
     * @return string
     */
    public function getNodeEntityClass();

    /**
     * Set node entity class name.
     *
     * @param string $nodeEntityClass
     * @return ModuleOptions
     */
    public function setNodeEntityClass($nodeEntityClass);

    /**
     * Set node entity proxy class name.
     *
     * @param string $nodeEntityProxyClass
     * @return ModuleOptions
     */
    public function setNodeEntityProxyClass($nodeEntityProxyClass);

    /**
     * Get node entity proxy class name.
     *
     * @return string
     */
    public function getNodeEntityProxyClass();

    /**
     * Get node hydrator class name.
     *
     * @return string
     */
    public function getNodeHydratorClass();

    /**
     * Set node hydrator class name.
     *
     * @param string $nodeHydratorClass
     * @return NodeServiceOptionsInterface
     */
    public function setNodeHydratorClass($nodeHydratorClass);
}
