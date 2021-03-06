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

use KmbBase\DateTimeFactory;
use KmbPuppetDb;
use KmbPuppetDb\Service;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ReportFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $service = new Service\Report();
        $service->setOptions($serviceLocator->get('KmbPuppetDb\Options\ModuleOptions'));
        /** @var KmbPuppetDb\Client $puppetDbClient */
        $puppetDbClient = $serviceLocator->get('KmbPuppetDb\Client');
        $service->setPuppetDbClient($puppetDbClient);
        /** @var DateTimeFactory $dateTimeFactory */
        $dateTimeFactory = $serviceLocator->get('DateTimeFactory');
        $service->setDateTimeFactory($dateTimeFactory);
        return $service;
    }
}
