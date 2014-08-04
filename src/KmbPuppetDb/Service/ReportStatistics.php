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

use KmbPuppetDb\Model;
use KmbPuppetDb\Query\Query;
use KmbPuppetDb\Service;

class ReportStatistics implements ReportStatisticsInterface
{
    /**
     * @var Service\Report
     */
    protected $reportService;

    /**
     * @var int
     */
    protected $skips;

    /**
     * @var int
     */
    protected $success;

    /**
     * @var int
     */
    protected $failures;

    /**
     * @var int
     */
    protected $noops;

    /**
     * Get skips count.
     *
     * @param \KmbPuppetDb\Query\Query|array $query
     * @return int
     */
    public function getSkipsCount($query = null)
    {
        return $this->getStatistic('skips', $query);
    }

    /**
     * Get success count.
     *
     * @param Query|array $query
     * @return int
     */
    public function getSuccessCount($query = null)
    {
        return $this->getStatistic('success', $query);
    }

    /**
     * Get failures count.
     *
     * @param Query|array $query
     * @return int
     */
    public function getFailuresCount($query = null)
    {
        return $this->getStatistic('failures', $query);
    }

    /**
     * Get noops count.
     *
     * @param Query|array $query
     * @return int
     */
    public function getNoopsCount($query = null)
    {
        return $this->getStatistic('noops', $query);
    }

    /**
     * Get all statistics as array.
     *
     * @param \KmbPuppetDb\Query\Query|array $query
     * @return array
     */
    public function getAllAsArray($query = null)
    {
        return [
            'skips' => $this->getSkipsCount($query),
            'success' => $this->getSuccessCount($query),
            'failures' => $this->getFailuresCount($query),
            'noops' => $this->getNoopsCount($query),
        ];
    }

    /**
     * Get report service.
     *
     * @return Service\Report
     */
    public function getReportService()
    {
        return $this->reportService;
    }

    /**
     * Set report service.
     *
     * @param Service\Report $reportService
     * @return ReportStatistics
     */
    public function setReportService(Service\Report $reportService)
    {
        $this->reportService = $reportService;
        return $this;
    }

    /**
     * @param $statistic
     * @param Query|array $query
     * @return mixed
     */
    protected function getStatistic($statistic, $query = null)
    {
        if ($this->$statistic == null) {
            $this->processStatistics($query);
        }
        return $this->$statistic;
    }

    /**
     * Browse all reports and process all the statistics
     *
     * @param \KmbPuppetDb\Query\Query|array $query
     */
    protected function processStatistics($query = null)
    {
        $this->skips = 0;
        $this->success = 0;
        $this->failures = 0;
        $this->noops = 0;
        $reports = $this->getReportService()->getAllForToday($query);
        foreach ($reports as $report) {
            /** @var Model\Report $report */
            if ($report->getStatus() == Model\ReportInterface::SKIPPED) {
                $this->skips++;
            } elseif ($report->getStatus() == Model\ReportInterface::SUCCESS) {
                $this->success++;
            } elseif ($report->getStatus() == Model\ReportInterface::FAILURE) {
                $this->failures++;
            } elseif ($report->getStatus() == Model\ReportInterface::NOOP) {
                $this->noops++;
            }
        }
    }
}
