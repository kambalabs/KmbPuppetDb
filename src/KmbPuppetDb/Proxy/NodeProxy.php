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
namespace KmbPuppetDb\Proxy;

use KmbPuppetDb\Model;
use KmbPuppetDb\Service;

class NodeProxy implements Model\NodeInterface
{
    /** @var  Service\Node */
    protected $nodeService;

    /** @var  Model\NodeInterface */
    protected $node;

    /** @var  string */
    protected $environment;

    /** @var  array */
    protected $facts;

    /** @var  string */
    protected $status;

    /**
     * Set NodeService.
     *
     * @param \KmbPuppetDb\Service\Node $nodeService
     * @return NodeProxy
     */
    public function setNodeService($nodeService)
    {
        $this->nodeService = $nodeService;
        return $this;
    }

    /**
     * Get NodeService.
     *
     * @return \KmbPuppetDb\Service\Node
     */
    public function getNodeService()
    {
        return $this->nodeService;
    }

    /**
     * Set Node.
     *
     * @param \KmbPuppetDb\Model\NodeInterface $node
     * @return NodeProxy
     */
    public function setNode($node)
    {
        $this->node = $node;
        return $this;
    }

    /**
     * Get Node.
     *
     * @return \KmbPuppetDb\Model\NodeInterface
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * Set name.
     *
     * @param string $name
     * @return NodeProxy
     */
    public function setName($name)
    {
        $this->node->setName($name);
        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->node->getName();
    }

    /**
     * Set Environment.
     *
     * @param string $environment
     * @return NodeProxy
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
        return $this;
    }

    /**
     * Get Environment.
     *
     * @return string
     */
    public function getEnvironment()
    {
        if ($this->environment === null) {
            $this->setEnvironment($this->getFact(Model\NodeInterface::ENVIRONMENT_FACT));
        }
        return $this->environment;
    }

    /**
     * Set facts.
     *
     * @param array $facts
     * @return NodeProxy
     */
    public function setFacts(array $facts)
    {
        $this->facts = $facts;
        return $this;
    }

    /**
     * Add fact.
     *
     * @param string $name
     * @param string $value
     * @return NodeProxy
     */
    public function addFact($name, $value)
    {
        $this->facts[$name] = $value;
        return $this;
    }

    /**
     * Get facts.
     *
     * @return array
     */
    public function getFacts()
    {
        if ($this->facts === null) {
            $this->setFacts($this->nodeService->getNodeFacts($this->getName()));
        }
        return $this->facts;
    }

    /**
     * @return bool
     */
    public function hasFacts()
    {
        return count($this->getFacts()) > 0;
    }

    /**
     * Determine if the node has the specified fact.
     *
     * @param string $name
     * @return bool
     */
    public function hasFact($name)
    {
        if ($this->hasFacts() && array_key_exists($name, $this->facts)) {
            return true;
        }
        return false;
    }

    /**
     * Get the specified fact.
     *
     * @param string $name
     * @return array
     */
    public function getFact($name)
    {
        if ($this->hasFact($name)) {
            return $this->facts[$name];
        }
    }

    /**
     * Set time of last report.
     *
     * @param \DateTime $reportedAt
     * @return NodeProxy
     */
    public function setReportedAt(\DateTime $reportedAt = null)
    {
        $this->node->setReportedAt($reportedAt);
        return $this;
    }

    /**
     * Get time of last report.
     *
     * @return \DateTime
     */
    public function getReportedAt()
    {
        return $this->node->getReportedAt();
    }

    /**
     * Set status.
     *
     * @param string $status
     * @return NodeProxy
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get status.
     *
     * @return string
     */
    public function getStatus()
    {
        if ($this->status === null) {
            $this->setStatus($this->nodeService->getNodeStatus($this->getName()));
        }
        return $this->status;
    }

    /**
     * Determine if the node has the specified status.
     *
     * @param $status
     * @return bool
     */
    public function hasStatus($status)
    {
        return $this->getStatus() === $status;
    }
}
