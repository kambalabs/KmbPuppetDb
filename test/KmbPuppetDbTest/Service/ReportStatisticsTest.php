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
    }

    /** @test */
    public function canGetSkipsCount()
    {
        $this->reportServiceMock->expects($this->any())
            ->method('getAllForToday')
            ->will($this->returnValue(ReportsCollection::factory(array(
                new Report(ReportInterface::SKIPPED),
                new Report(ReportInterface::SKIPPED),
                new Report(ReportInterface::FAILURE),
            ), 3)));

        $this->assertEquals(2, $this->reportStatistics->getSkipsCount());
    }

    /** @test */
    public function canGetSuccessCount()
    {
        $this->reportServiceMock->expects($this->any())
            ->method('getAllForToday')
            ->will($this->returnValue(ReportsCollection::factory(array(
                new Report(ReportInterface::SUCCESS),
                new Report(ReportInterface::SUCCESS),
                new Report(ReportInterface::SUCCESS),
            ), 3)));

        $this->assertEquals(3, $this->reportStatistics->getSuccessCount());
    }

    /** @test */
    public function canGetFailuresCount()
    {
        $this->reportServiceMock->expects($this->any())
            ->method('getAllForToday')
            ->will($this->returnValue(ReportsCollection::factory(array(
                new Report(ReportInterface::SKIPPED),
                new Report(ReportInterface::SKIPPED),
                new Report(ReportInterface::FAILURE),
            ), 3)));

        $this->assertEquals(1, $this->reportStatistics->getFailuresCount());
    }

    /** @test */
    public function canGetNoopsCount()
    {
        $this->reportServiceMock->expects($this->any())
            ->method('getAllForToday')
            ->will($this->returnValue(ReportsCollection::factory(array(
                new Report(ReportInterface::NOOP),
                new Report(ReportInterface::SKIPPED),
                new Report(ReportInterface::FAILURE),
            ),3)));

        $this->assertEquals(1, $this->reportStatistics->getNoopsCount());
    }

    /** @test */
    public function canGetAllAsArray()
    {
        $this->reportServiceMock->expects($this->any())
            ->method('getAllForToday')
            ->will($this->returnValue(ReportsCollection::factory(array(
                new Report(ReportInterface::SUCCESS),
                new Report(ReportInterface::SKIPPED),
                new Report(ReportInterface::SKIPPED),
                new Report(ReportInterface::FAILURE),
            ), 4)));

        $expectedStats = array(
            'skips' => 2,
            'success' => 1,
            'failures' => 1,
            'noops' => 0,
        );
        $this->assertEquals($expectedStats, $this->reportStatistics->getAllAsArray());
    }
}
