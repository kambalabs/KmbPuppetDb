<?php
/**
 * @copyright Copyright (c) 2014 Orange Applications for Business
 * @link      http://github.com/kambalabs for the sources repositories
 *
 * This file is part of Kamba.
 *
 * Kamba is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * Kamba is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Kamba.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace KmbPuppetDb\Model;

class NodeV4Hydrator extends AbstractNodeHydrator
{
    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array  $data
     * @param  Node $object
     * @return Node
     */
    public function hydrate(array $data, $object)
    {
        parent::hydrate($data, $object);
        if (isset($data['certname'])) {
            $object->setName($data['certname']);
        }
        if (isset($data['facts-timestamp'])) {
            $object->setReportedAt(new \DateTime($data['facts-timestamp']));
        }
        if (isset($data['facts-environment'])) {
            $object->setEnvironment($data['facts-environment']);
        }
        return $object;
    }
}
