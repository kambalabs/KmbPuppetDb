<?php
namespace KmbPuppetDbTest\Service;

use KmbCore\FakeDateTimeFactory;
use KmbPuppetDb\Model;
use KmbPuppetDb\Options\ModuleOptions;
use KmbPuppetDb\Request;
use KmbPuppetDb\Response;
use KmbPuppetDb\Service;

class ReportTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $puppetDbClientMock;

    /**
     * @var Service\Report
     */
    private $reportService;

    protected function setUp()
    {
        $this->puppetDbClientMock = $this->getMock('KmbPuppetDb\Client');
        $this->puppetDbClientMock->expects($this->any())
            ->method('send')
            ->will($this->returnCallback(function (Request $request) {
                $reports = array(
                    array(
                        "status" => "failure",
                        "timestamp" => "2014-03-30T06:41:40.286Z",
                        "certname" => "node1.local",
                        "containing-class" => "Security::Debian",
                    ),
                    array(
                        "status" => "success",
                        "timestamp" => "2014-03-30T06:41:40.286Z",
                        "certname" => "node4.local",
                        "containing-class" => "Security::Debian",
                    ),
                    array(
                        "status" => "failure",
                        "timestamp" => "2014-03-30T06:41:40.286Z",
                        "certname" => "node3.local",
                        "containing-class" => "Security::Debian",
                    ),
                    array(
                        "status" => "success",
                        "timestamp" => "2014-03-30T06:41:40.286Z",
                        "certname" => "node1.local",
                        "containing-class" => "Security::Debian",
                    ),
                    array(
                        "status" => "success",
                        "timestamp" => "2014-03-30T06:34:19.012Z",
                        "certname" => "node2.local",
                        "containing-class" => "Services::Puppetclient",
                    ),
                    array(
                        "status" => "failure",
                        "timestamp" => "2014-03-30T06:34:19.012Z",
                        "certname" => "node8.local",
                        "containing-class" => "Services::Puppetclient",
                    ),
                    array(
                        "status" => "success",
                        "timestamp" => "2014-03-30T06:34:19.012Z",
                        "certname" => "node4.local",
                        "containing-class" => "Services::Puppetclient",
                    ),
                    array(
                        "status" => "success",
                        "timestamp" => "2014-03-30T06:34:19.012Z",
                        "certname" => "node3.local",
                        "containing-class" => "Services::Puppetclient",
                    ),
                    array(
                        "status" => "success",
                        "timestamp" => "2014-03-31T06:34:19.012Z",
                        "certname" => "node9.local",
                        "containing-class" => "Services::Puppetclient",
                    )
                );
                $defaultQuery = array(
                    'and',
                    array('>=', 'timestamp', '2014-03-30T00:00:00+02:00'),
                    array('<=', 'timestamp', '2014-03-30T23:59:59+02:00'),
                );
                if ($request->getQuery() !== null) {
                    if ($request->getQuery() == $defaultQuery) {
                        $reports = array_slice($reports, 0, 8);
                    } elseif ($request->getQuery() == array('and', $defaultQuery, array('=', 'status', 'success'))) {
                        $reports = array_slice($reports, 1, 7);
                    } else {
                        $reports = array_slice($reports, 1, 6);
                    }
                }
                $defaultOrder = array(array(
                    'field' => 'timestamp',
                    'order' => 'desc'
                ));
                if ($request->getOrderBy() != $defaultOrder) {
                    usort($reports, function ($a, $b) {
                        if ($a['certname'] === $b['certname']) {
                            return 0;
                        }
                        if ($a['certname'] > $b['certname']) {
                            return -1;
                        }
                        return 1;
                    });
                }
                $total = count($reports);
                $data = array_slice($reports, $request->getOffset(), $request->getLimit());
                $response = new Response();
                $response->setData(json_decode(json_encode(($data))));
                $response->setTotal($total);
                return $response;
            }));

        $this->reportService = new Service\Report();
        $this->reportService->setOptions(new ModuleOptions());
        $this->reportService->setPuppetDbClient($this->puppetDbClientMock);
        $this->reportService->setDateTimeFactory(new FakeDateTimeFactory(new \DateTime('2014-03-30T10:00:00+02:00')));
    }

    /** @test */
    public function canGetAll()
    {
        $reports = $this->reportService->getAll();

        $this->assertInstanceOf('KmbPuppetDb\Model\ReportsCollection', $reports);
        $this->assertEquals(9, count($reports));
        $firstReport = $reports->get(0);
        $this->assertInstanceOf('KmbPuppetDb\Model\Report', $firstReport);
        $this->assertEquals(Model\ReportInterface::FAILURE, $firstReport->getStatus());
        $this->assertEquals('node1.local', $firstReport->getNodeName());
    }

    /** @test */
    public function canGetAllWithPaging()
    {
        $reports = $this->reportService->getAll(1, 5);

        $this->assertEquals(5, count($reports));
        $this->assertEquals(9, $reports->getTotal());
        $this->assertEquals('node4.local', $reports->get(0)->getNodeName());
    }

    /** @test */
    public function canGetAllWithQuery()
    {
        $reports = $this->reportService->getAll(array('>=', 'timestamp', '2014-01-31T10:00:00'));

        $this->assertEquals(6, count($reports));
        $this->assertEquals(6, $reports->getTotal());
        $this->assertEquals('node4.local', $reports->get(0)->getNodeName());
    }

    /** @test */
    public function canGetAllWithOrdering()
    {
        $reports = $this->reportService->getAll(0, 5, array('field' => 'certname', 'order' => 'desc'));

        $this->assertEquals('node9.local', $reports->get(0)->getNodeName());
    }

    /** @test */
    public function canGetAllForToday()
    {
        $reports = $this->reportService->getAllForToday();

        $this->assertEquals(8, count($reports));
        $this->assertEquals(8, $reports->getTotal());
        $this->assertEquals('node1.local', $reports->get(0)->getNodeName());
    }

    /** @test */
    public function canGetAllForTodayWithPaging()
    {
        $reports = $this->reportService->getAllForToday(2, 5);

        $this->assertEquals(5, count($reports));
        $this->assertEquals(8, $reports->getTotal());
        $this->assertEquals('node3.local', $reports->get(0)->getNodeName());
    }

    /** @test */
    public function canGetAllForTodayWithQuery()
    {
        $reports = $this->reportService->getAllForToday(array('=', 'status', 'success'));

        $this->assertEquals(7, count($reports));
        $this->assertEquals(7, $reports->getTotal());
        $this->assertEquals('node4.local', $reports->get(0)->getNodeName());
    }
}
