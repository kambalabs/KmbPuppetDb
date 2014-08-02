<?php
namespace KmbPuppetDbTest\Service;

use KmbPuppetDb\Model\Report;
use KmbPuppetDb\Model\ReportInterface;
use KmbPuppetDb\Model\ReportsCollection;
use KmbPuppetDb\Service\ReportStatistics;

class ReportStatisticsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ReportStatistics
     */
    protected $reportStatistics;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $reportServiceMock;

    protected function setUp()
    {
        $this->reportServiceMock = $this->getMock('KmbPuppetDb\Service\Report');
        $this->reportStatistics = new ReportStatistics();
        $this->reportStatistics->setReportService($this->reportServiceMock);
        $this->reportServiceMock->expects($this->any())
            ->method('getAllForToday')
            ->will($this->returnCallback(function ($query = null) {
                $reports = [
                    new Report(ReportInterface::SUCCESS),
                    new Report(ReportInterface::SUCCESS),
                    new Report(ReportInterface::SKIPPED),
                    new Report(ReportInterface::SUCCESS),
                    new Report(ReportInterface::FAILURE),
                    new Report(ReportInterface::SKIPPED),
                    new Report(ReportInterface::FAILURE),
                    new Report(ReportInterface::NOOP),
                ];
                if ($query !== null) {
                    return ReportsCollection::factory(array_slice($reports, 2, 5), 5);
                }
                return ReportsCollection::factory($reports, 8);
            }));
    }

    /** @test */
    public function canGetSkipsCount()
    {
        $this->assertEquals(2, $this->reportStatistics->getSkipsCount());
    }

    /** @test */
    public function canGetSuccessCount()
    {
        $this->assertEquals(3, $this->reportStatistics->getSuccessCount());
    }

    /** @test */
    public function canGetFailuresCount()
    {
        $this->assertEquals(2, $this->reportStatistics->getFailuresCount());
    }

    /** @test */
    public function canGetNoopsCount()
    {
        $this->assertEquals(1, $this->reportStatistics->getNoopsCount());
    }

    /** @test */
    public function canGetAllAsArray()
    {
        $expectedStats = [
            'skips' => 2,
            'success' => 3,
            'failures' => 2,
            'noops' => 1,
        ];
        $this->assertEquals($expectedStats, $this->reportStatistics->getAllAsArray());
    }

    /** @test */
    public function canGetAllAsArrayWithQuery()
    {
        $expectedStats = [
            'skips' => 2,
            'success' => 1,
            'failures' => 2,
            'noops' => 0,
        ];
        $this->assertEquals($expectedStats, $this->reportStatistics->getAllAsArray(['=', 'environment', 'STABLE_PF1']));
    }
}
