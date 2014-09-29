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

use KmbPuppetDb\Exception\InvalidArgumentException;
use KmbPuppetDb\Options\ModuleOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class NodesNamesQueryBuilderFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var ModuleOptions $options */
        $options = $serviceLocator->get('KmbPuppetDb\Options\ModuleOptions');

        $className = 'KmbPuppetDb\Query\Nodes' . strtoupper($options->getApiVersion()) . 'NamesQueryBuilder';
        if (!class_exists($className)) {
            throw new InvalidArgumentException('Invalid API version : ' . $options->getApiVersion());
        }

        return new $className();
    }
}
