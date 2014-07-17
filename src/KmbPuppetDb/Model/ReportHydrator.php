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
namespace KmbPuppetDb\Model;

use Zend\Stdlib\Hydrator\HydratorInterface;

class ReportHydrator implements HydratorInterface
{

    /**
     * Extract values from an object
     *
     * @param  object $object
     * @return array
     */
    public function extract($object)
    {
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array  $data
     * @param  object $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        if (isset($data['timestamp'])) {
            $object->setCreatedAt(new \DateTime($data['timestamp']));
        }
        if (isset($data['resource-title'])) {
            $object->setTitle($data['resource-title']);
        }
        if (isset($data['resource-type'])) {
            $object->setType($data['resource-type']);
        }
        if (isset($data['message'])) {
            $object->setMessage($data['message']);
        }
        if (isset($data['containing-class'])) {
            $object->setClassName($data['containing-class']);
        }
        if (isset($data['certname'])) {
            $object->setNodeName($data['certname']);
        }
        if (isset($data['status'])) {
            $object->setStatus($data['status']);
        }
        return $object;
    }
}
