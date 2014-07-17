<?php
namespace KmbPuppetDbTest\Model;

use KmbPuppetDb\Model\Report;
use KmbPuppetDb\Model\ReportHydrator;
use KmbPuppetDb\Model\ReportInterface;

class ReportHydratorTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canHydrate()
    {
        $report = new Report();
        $hydrator = new ReportHydrator();

        $hydrator->hydrate(array(
            "status" => "success",
            "timestamp" => "2014-03-30T06:41:40.286Z",
            "certname" => "node1.local",
            "containing-class" => "Common_mbs::Security_debian",
            "report" => "98e759bbe471faeab7b6c6de4556b7b4ec7670a1",
            "run-start-time" => "2014-03-31T12:36:00.417Z",
            "resource-title" => "list_deb_vul",
            "configuration-version" => "1396259366",
            "run-end-time" => "2014-03-31T12:36:05.709Z",
            "property" => "returns",
            "message" => "executed successfully",
            "old-value" => "notrun",
            "line" => 36,
            "file" => "/etc/puppet/environments/ING_TMP/modules/common_mbs/manifests/security_debian.pp",
            "report-receive-time" => "2014-03-31T12:36:09.803Z",
            "resource-type" => "File",
        ), $report);

        $this->assertEquals(new \DateTime('2014-03-30T06:41:40.286Z'), $report->getCreatedAt());
        $this->assertEquals('list_deb_vul', $report->getTitle());
        $this->assertEquals('File', $report->getType());
        $this->assertEquals('executed successfully', $report->getMessage());
        $this->assertEquals('Common_mbs::Security_debian', $report->getClassName());
        $this->assertEquals('node1.local', $report->getNodeName());
        $this->assertEquals(ReportInterface::SUCCESS, $report->getStatus());
    }
}
