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
namespace KmbPuppetDb\Query;

abstract class AbstractQueryBuilder implements QueryBuilderInterface
{
    /**
     * @param array  $elements
     * @param string $operator
     * @return Query
     */
    public function build(array $elements, $operator = '=')
    {
        $data = [];
        foreach ($elements as $element) {
            $query = $this->getQuery($element, $operator);
            if ($query) {
                $data[] = $query;
            }
        }
        if (count($data) == 1) {
            $data = $data[0];
        } elseif (count($data) > 1) {
            array_unshift($data, 'OR');
        }
        if (!empty($data)) {
            return new Query($data);
        }
    }

    /**
     * @param mixed  $element
     * @param string $operator
     * @return array
     */
    abstract protected function getQuery($element, $operator);
}
