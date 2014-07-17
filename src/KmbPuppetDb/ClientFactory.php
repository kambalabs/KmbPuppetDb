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

use Zend\Http;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ClientFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return Client
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $puppetDbClient = new Client();

        $options = $serviceLocator->get('KmbPuppetDb\Options\ModuleOptions');

        $httpClient = $serviceLocator->get('KmbPuppetDb\Http\Client');
        $httpClient->setOptions($options->getHttpOptions());

        $puppetDbClient->setHttpClient($httpClient);
        $puppetDbClient->setOptions($options);
        $puppetDbClient->setLogger($serviceLocator->get('Logger'));

        return $puppetDbClient;
    }
}
