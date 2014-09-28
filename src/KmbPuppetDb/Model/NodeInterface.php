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

interface NodeInterface
{
    const ENVIRONMENT_FACT = 'kmbenv';
    const UNCHANGED = 'unchanged';
    const CHANGED   = 'changed';
    const FAILED    = 'failed';

    /**
     * Set name.
     *
     * @param string $name
     * @return NodeInterface
     */
    public function setName($name);

    /**
     * Get name.
     *
     * @return string
     */
    public function getName();

    /**
     * Set Environment.
     *
     * @param string $environment
     * @return NodeInterface
     */
    public function setEnvironment($environment);

    /**
     * Get Environment.
     *
     * @return string
     */
    public function getEnvironment();

    /**
     * Set facts.
     *
     * @param array $facts
     * @return NodeInterface
     */
    public function setFacts(array $facts);

    /**
     * Add fact.
     *
     * @param string $name
     * @param string $value
     * @return NodeInterface
     */
    public function addFact($name, $value);

    /**
     * Get facts.
     *
     * @return array
     */
    public function getFacts();

    /**
     * @return bool
     */
    public function hasFacts();

    /**
     * Determine if the node has the specified fact.
     *
     * @param string $name
     * @return bool
     */
    public function hasFact($name);

    /**
     * Get the specified fact.
     *
     * @param string $name
     * @return array
     */
    public function getFact($name);

    /**
     * Set time of last report.
     *
     * @param \DateTime $reportedAt
     * @return NodeInterface
     */
    public function setReportedAt(\DateTime $reportedAt = null);

    /**
     * Get time of last report.
     *
     * @return \DateTime
     */
    public function getReportedAt();

    /**
     * Set status.
     *
     * @param string $status
     * @return NodeInterface
     */
    public function setStatus($status);

    /**
     * Get status.
     *
     * @return string
     */
    public function getStatus();

    /**
     * Determine if the node has the specified status.
     *
     * @param $status
     * @return bool
     */
    public function hasStatus($status);
}
