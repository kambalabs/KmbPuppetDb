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

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions implements ClientOptionsInterface, NodeServiceOptionsInterface, ReportServiceOptionsInterface
{
    /**
     * Turn off strict options mode
     */
    protected $__strictMode__ = false;

    /**
     * @var string
     */
    protected $baseUri = 'https://localhost:8081';

    /**
     * @var string
     */
    protected $apiVersion = 'v3';

    /**
     * @var array
     */
    protected $httpOptions = [];

    /**
     * @var string
     */
    protected $nodeEntityClass = 'KmbPuppetDb\Model\Node';

    /**
     * @var string
     */
    protected $nodeHydratorClass = 'KmbPuppetDb\Model\NodeHydrator';

    /**
     * @var string
     */
    protected $reportEntityClass = 'KmbPuppetDb\Model\Report';

    /**
     * @var string
     */
    protected $reportHydratorClass = 'KmbPuppetDb\Model\ReportHydrator';

    /**
     * Set PuppetDB base URI.
     *
     * @param $baseUri
     * @return ClientOptionsInterface
     */
    public function setBaseUri($baseUri)
    {
        $this->baseUri = $baseUri;
        return $this;
    }

    /**
     * Get PuppetDB base URI.
     *
     * @return string
     */
    public function getBaseUri()
    {
        return $this->baseUri;
    }

    /**
     * Set ApiVersion.
     *
     * @param string $apiVersion
     * @return ModuleOptions
     */
    public function setApiVersion($apiVersion)
    {
        $this->apiVersion = $apiVersion;
        return $this;
    }

    /**
     * Get ApiVersion.
     *
     * @return string
     */
    public function getApiVersion()
    {
        return $this->apiVersion;
    }

    /**
     * Set HTTP client options.
     *
     * @param $httpOptions
     *
     * @return ClientOptionsInterface
     */
    public function setHttpOptions($httpOptions)
    {
        $this->httpOptions = $httpOptions;
        return $this;
    }

    /**
     * Get HTTP client options.
     *
     * @return array
     */
    public function getHttpOptions()
    {
        return $this->httpOptions;
    }

    /**
     * Set node entity class name.
     *
     * @param string $nodeEntityClass
     * @return NodeServiceOptionsInterface
     */
    public function setNodeEntityClass($nodeEntityClass)
    {
        $this->nodeEntityClass = $nodeEntityClass;
        return $this;
    }

    /**
     * Get node entity class name.
     *
     * @return string
     */
    public function getNodeEntityClass()
    {
        return $this->nodeEntityClass;
    }

    /**
     * Set node hydrator class name.
     *
     * @param string $nodeHydratorClass
     * @return NodeServiceOptionsInterface
     */
    public function setNodeHydratorClass($nodeHydratorClass)
    {
        $this->nodeHydratorClass = $nodeHydratorClass;
        return $this;
    }

    /**
     * Get node hydrator class name.
     *
     * @return string
     */
    public function getNodeHydratorClass()
    {
        return $this->nodeHydratorClass;
    }

    /**
     * Set report entity class name.
     *
     * @param string $reportEntityClass
     * @return ModuleOptions
     */
    public function setReportEntityClass($reportEntityClass)
    {
        $this->reportEntityClass = $reportEntityClass;
        return $this;
    }

    /**
     * Get report entity class name.
     *
     * @return string
     */
    public function getReportEntityClass()
    {
        return $this->reportEntityClass;
    }

    /**
     * Set report hydrator class name.
     *
     * @param string $reportHydratorClass
     * @return ReportServiceOptionsInterface
     */
    public function setReportHydratorClass($reportHydratorClass)
    {
        $this->reportHydratorClass = $reportHydratorClass;
        return $this;
    }

    /**
     * Get report hydrator class name.
     *
     * @return string
     */
    public function getReportHydratorClass()
    {
        return $this->reportHydratorClass;
    }
}
