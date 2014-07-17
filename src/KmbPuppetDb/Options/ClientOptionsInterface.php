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

interface ClientOptionsInterface
{
    /**
     * Get PuppetDB base URI.
     *
     * @return string
     */
    public function getBaseUri();

    /**
     * Set PuppetDB base URI.
     *
     * @param $baseUri
     *
     * @return ClientOptionsInterface
     */
    public function setBaseUri($baseUri);

    /**
     * Get HTTP client options.
     *
     * @return array
     */
    public function getHttpOptions();

    /**
     * Set HTTP client options.
     *
     * @param $httpOptions
     *
     * @return ClientOptionsInterface
     */
    public function setHttpOptions($httpOptions);
}
