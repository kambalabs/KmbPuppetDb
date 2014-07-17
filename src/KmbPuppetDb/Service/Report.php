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

use KmbCore\DateTimeFactoryInterface;
use KmbPuppetDb\Model;
use KmbPuppetDb;
use KmbPuppetDb\Options\ReportServiceOptionsInterface;
use KmbPuppetDb\Service;
use Zend\Stdlib\Hydrator\HydratorInterface;

class Report implements ReportInterface
{
    /** @var ReportServiceOptionsInterface */
    protected $options;

    /** @var KmbPuppetDb\ClientInterface */
    protected $puppetDbClient;

    /** @var DateTimeFactoryInterface */
    protected $dateTimeFactory;

    /**
     * Retrieves all reports matching with specified query, paging and sorting.
     * Default order is by timestamp desc.
     * $query can be omitted :
     *    $reportService->getAll(10, 10);
     *
     * @param mixed $query
     * @param int   $offset
     * @param mixed $limit
     * @param array $orderBy
     * @return Model\ReportsCollection
     */
    public function getAll($query = null, $offset = null, $limit = null, $orderBy = null)
    {
        if (is_int($query)) {
            $orderBy = $limit;
            $limit = $offset;
            $offset = $query;
            $query = null;
        }
        if ($orderBy == null) {
            $orderBy = array(array(
                'field' => 'timestamp',
                'order' => 'desc'
            ));
        }
        $request = new KmbPuppetDb\Request('/events', $query, $orderBy);
        $request->setPaging($offset, $limit);
        $response = $this->getPuppetDbClient()->send($request);

        $reports = array();
        foreach ($response->getData() as $data) {
            $node = $this->createReportFromData($data);
            $reports[] = $node;
        }
        return Model\ReportsCollection::factory($reports, $response->getTotal());
    }

    /**
     * Retrieves all reports for the current day and matching with specified query, paging and sorting.
     * If query is null, all the reports of the current day are returned.
     * Default order is by timestamp desc.
     * $query can be omitted :
     *    $reportService->getAll(10, 10);
     *
     * @param mixed $query
     * @param int   $offset
     * @param mixed $limit
     * @param array $orderBy
     * @return Model\ReportsCollection
     */
    public function getAllForToday($query = null, $offset = null, $limit = null, $orderBy = null)
    {
        if (is_int($query)) {
            $orderBy = $limit;
            $limit = $offset;
            $offset = $query;
            $query = null;
        }
        if ($query == null) {
            $query = $this->getTodayQuery();
        } else {
            $query = array(
                'and',
                $this->getTodayQuery(),
                $query
            );
        }
        return $this->getAll($query, $offset, $limit, $orderBy);
    }

    /**
     * Get options.
     *
     * @return ReportServiceOptionsInterface
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set options.
     *
     * @param $options
     * @return Service\Report
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Get PuppetDB Client.
     *
     * @return KmbPuppetDb\ClientInterface
     */
    public function getPuppetDbClient()
    {
        return $this->puppetDbClient;
    }

    /**
     * Set PuppetDB Client.
     *
     * @param KmbPuppetDb\ClientInterface $puppetDbClient
     * @return Service\Report
     */
    public function setPuppetDbClient(KmbPuppetDb\ClientInterface $puppetDbClient)
    {
        $this->puppetDbClient = $puppetDbClient;
        return $this;
    }

    /**
     * Get DateTimeFactory.
     *
     * @return \KmbCore\DateTimeFactoryInterface
     */
    public function getDateTimeFactory()
    {
        return $this->dateTimeFactory;
    }

    /**
     * Set DateTimeFactory.
     *
     * @param \KmbCore\DateTimeFactoryInterface $dateTimeFactory
     * @return Report
     */
    public function setDateTimeFactory($dateTimeFactory)
    {
        $this->dateTimeFactory = $dateTimeFactory;
        return $this;
    }

    /**
     * @param $data
     * @return Model\ReportInterface
     */
    protected function createReportFromData($data)
    {
        $reportEntityClass = $this->getOptions()->getReportEntityClass();
        $reportHydratorClass = $this->getOptions()->getReportHydratorClass();
        /** @var HydratorInterface $hydrator */
        $hydrator = new $reportHydratorClass;
        return $hydrator->hydrate((array)$data, new $reportEntityClass);
    }

    /**
     * @return array
     */
    protected function getTodayQuery()
    {
        $now = $this->getDateTimeFactory()->now();
        $from = clone $now;
        $to = clone $now;
        $query = array(
            'and',
            array('>=', 'timestamp', $from->setTime(0, 0, 0)->format(\DateTime::W3C)),
            array('<=', 'timestamp', $to->setTime(23, 59, 59)->format(\DateTime::W3C)),
        );
        return $query;
    }
}
