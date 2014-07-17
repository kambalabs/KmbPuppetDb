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

class Node implements NodeInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var \DateTime
     */
    protected $reportedAt;

    /**
     * @var array
     */
    protected $facts = array();

    public function __construct($name = null, $status = null, $reportedAt = null, $facts = array())
    {
        $this->setName($name);
        $this->setStatus($status);
        $this->setReportedAt($reportedAt);
        $this->setFacts($facts);
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name.
     *
     * @param string $name
     * @return NodeInterface
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get facts.
     *
     * @return array
     */
    public function getFacts()
    {
        return $this->facts;
    }

    /**
     * Set facts.
     *
     * @param array $facts
     * @return NodeInterface
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
     * @return NodeInterface
     */
    public function addFact($name, $value)
    {
        $this->facts[$name] = $value;
        return $this;
    }

    /**
     * Determine if the node has the specified fact.
     *
     * @param string $name
     * @return bool
     */
    public function hasFact($name)
    {
        return array_key_exists($name, $this->getFacts());
    }

    /**
     * Get the specified fact.
     *
     * @param string $name
     * @return string
     */
    public function getFact($name)
    {
        if ($this->hasFact($name)) {
            return $this->facts[$name];
        }
        return null;
    }

    /**
     * Get time of last report.
     *
     * @return \DateTime
     */
    public function getReportedAt()
    {
        return $this->reportedAt;
    }

    /**
     * Set time of last report.
     *
     * @param \DateTime $reportedAt
     * @return NodeInterface
     */
    public function setReportedAt(\DateTime $reportedAt = null)
    {
        $this->reportedAt = $reportedAt;
        return $this;
    }

    /**
     * Get status.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set status.
     *
     * @param string $status
     * @return NodeInterface
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Determine if the node has the specified status.
     *
     * @param $status
     * @return bool
     */
    public function hasStatus($status)
    {
        return $this->getStatus() == $status;
    }
}
