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

use KmbDomain\Model\EnvironmentInterface;

abstract class AbstractEnvironmentsQueryBuilder implements EnvironmentsQueryBuilderInterface
{
    /**
     * @param EnvironmentInterface[] $environments
     * @return Query
     */
    public function build(array $environments)
    {
        $data = [];
        foreach ($environments as $environment) {
            $query = $this->getQuery($environment);
            if ($query) {
                $data[] = $query;
            }
        }
        if (count($data) == 1) {
            $data = $data[0];
        } elseif (count($data) > 1) {
            array_unshift($data, 'OR');
        }
        return new Query($data);
    }

    /**
     * @param EnvironmentInterface $environment
     * @return array
     */
    abstract protected function getQuery($environment);
}
