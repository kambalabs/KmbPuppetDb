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
namespace KmbPuppetDb;

class OrderBy
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->setData($data);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $elements = array_map(
            function ($element) {
                $order = isset($element['order']) ? $element['order'] : 'asc';
                return '{"field": "' . $element['field'] . '", "order": "' . $order . '"}';
            },
            $this->getData()
        );
        return '[' . implode(', ', $elements) . ']';
    }

    /**
     * Get Data.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set Data.
     *
     * @param array $data
     * @return OrderBy
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }
}
