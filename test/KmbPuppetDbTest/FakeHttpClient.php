<?php
namespace KmbPuppetDbTest;

use Zend\Http\Client;
use Zend\Http\Exception;
use Zend\Http\Headers;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Json\Json;

class FakeHttpClient extends Client
{
    protected $nodes = array(
        array(
            'name' => 'node1.local',
            'deactivated' => null,
            'catalog_timestamp' => '2014-01-31T16:01:16.683Z',
            'facts_timestamp' => '2014-01-31T16:01:15.628Z',
            'report_timestamp' => '2014-01-31T16:01:15.507Z'
        ),
        array(
            'name' => 'node2.local',
            'deactivated' => null,
            'catalog_timestamp' => '2014-01-31T16:00:24.713Z',
            'facts_timestamp' => '2014-01-31T16:00:23.604Z',
            'report_timestamp' => '2014-01-31T16:00:22.815Z'
        ),
        array(
            'name' => 'node3.local',
            'deactivated' => null,
            'catalog_timestamp' => '2014-01-31T16:00:24.713Z',
            'facts_timestamp' => '2014-01-31T16:00:23.604Z',
            'report_timestamp' => '2014-01-31T16:00:22.815Z'
        ),
        array(
            'name' => 'node4.local',
            'deactivated' => null,
            'catalog_timestamp' => '2014-01-31T16:00:24.713Z',
            'facts_timestamp' => '2014-01-31T16:00:23.604Z',
            'report_timestamp' => '2014-01-31T16:00:22.815Z'
        ),
        array(
            'name' => 'node5.local',
            'deactivated' => null,
            'catalog_timestamp' => '2014-01-31T16:00:24.713Z',
            'facts_timestamp' => '2014-01-31T16:00:23.604Z',
            'report_timestamp' => '2014-01-31T16:00:22.815Z'
        ),
        array(
            'name' => 'node6.local',
            'deactivated' => null,
            'catalog_timestamp' => '2014-01-31T16:00:24.713Z',
            'facts_timestamp' => '2014-01-31T16:00:23.604Z',
            'report_timestamp' => '2014-01-31T16:00:22.815Z'
        ),
        array(
            'name' => 'node7.local',
            'deactivated' => null,
            'catalog_timestamp' => '2014-01-31T16:00:24.713Z',
            'facts_timestamp' => '2014-01-31T16:00:23.604Z',
            'report_timestamp' => '2014-01-31T16:00:22.815Z'
        ),
        array(
            'name' => 'node8.local',
            'deactivated' => null,
            'catalog_timestamp' => '2014-01-31T16:00:24.713Z',
            'facts_timestamp' => '2014-01-31T16:00:23.604Z',
            'report_timestamp' => '2014-01-31T16:00:22.815Z'
        ),
        array(
            'name' => 'node9.local',
            'deactivated' => null,
            'catalog_timestamp' => '2014-01-31T16:00:24.713Z',
            'facts_timestamp' => '2014-01-31T16:00:23.604Z',
            'report_timestamp' => '2014-01-31T16:00:22.815Z'
        ),
    );

    protected $facts = array(
        'node1.local' => array(
            'uptime_days' => '477',
            'operatingsystem' => 'Debian',
            'kernelversion' => '2.6.32',
            'lsbdistcodename' => 'wheezy',
            'lsbdistdescription' => 'Debian GNU/Linux 7.3 (wheezy)',
            'processorcount' => '4',
            'memorysize' => '2.00 GB',
            'uptime' => '477 days',
            'pf' => 'TEST',
            'hostname' => 'node1',
        ),
        'node2.local' => array(
            'uptime_days' => '233',
            'operatingsystem' => 'Debian',
            'kernelversion' => '3.2.0',
            'lsbdistcodename' => 'wheezy',
            'lsbdistdescription' => 'Debian GNU/Linux 7.1 (wheezy)',
            'processorcount' => '2',
            'memorysize' => '2.00 GB',
            'uptime' => '233 days',
            'pf' => 'TEST',
            'hostname' => 'node2',
        ),
        'node3.local' => array(
            'uptime_days' => '123',
            'operatingsystem' => 'Debian',
            'kernelversion' => '3.2.0',
            'lsbdistcodename' => 'wheezy',
            'lsbdistdescription' => 'Debian GNU/Linux 7.1 (wheezy)',
            'processorcount' => '8',
            'memorysize' => '8.00 GB',
            'uptime' => '123 days',
            'pf' => 'TEST',
            'hostname' => 'node3',
        ),
        'node4.local' => array(
            'uptime_days' => '399',
            'operatingsystem' => 'Debian',
            'kernelversion' => '2.6.32',
            'lsbdistcodename' => 'squeeze',
            'lsbdistdescription' => 'Debian GNU/Linux 6.0.9 (squeeze)',
            'processorcount' => '4',
            'memorysize' => '2.00 GB',
            'uptime' => '399 days',
            'pf' => 'TEST',
            'hostname' => 'node4',
        ),
        'node5.local' => array(
            'uptime_days' => '23',
            'operatingsystem' => 'Debian',
            'kernelversion' => '2.6.32',
            'lsbdistcodename' => 'squeeze',
            'lsbdistdescription' => 'Debian GNU/Linux 6.0.9 (squeeze)',
            'processorcount' => '1',
            'memorysize' => '1.00 GB',
            'uptime' => '23 days',
            'pf' => 'TEST',
            'hostname' => 'node5',
        ),
        'node6.local' => array(
            'uptime_days' => '10',
            'operatingsystem' => 'Windows',
            'kernelversion' => '2.6.32',
            'processorcount' => '8',
            'memorysize' => '16.00 GB',
            'uptime' => '10 days',
            'pf' => 'TEST',
            'hostname' => 'node6',
        ),
        'node7.local' => array(
            'uptime_days' => '0',
            'operatingsystem' => 'Debian',
            'kernelversion' => '3.2.0',
            'lsbdistcodename' => 'wheezy',
            'lsbdistdescription' => 'Debian GNU/Linux 7.4 (wheezy)',
            'processorcount' => '8',
            'memorysize' => '32.00 GB',
            'uptime' => '3:02 hours',
            'pf' => 'TEST',
            'hostname' => 'node7',
        ),
        'node8.local' => array(
            'uptime_days' => '0',
            'operatingsystem' => 'Debian',
            'kernelversion' => '2.6.32',
            'lsbdistcodename' => 'wheezy',
            'lsbdistdescription' => 'Debian GNU/Linux 7.2 (wheezy)',
            'processorcount' => '4',
            'memorysize' => '2.00 GB',
            'uptime' => '0:23 hours',
            'pf' => 'TEST',
            'hostname' => 'node8',
        ),
        'node9.local' => array(
            'uptime_days' => '302',
            'operatingsystem' => 'Debian',
            'kernelversion' => '2.6.32',
            'lsbdistcodename' => 'wheezy',
            'lsbdistdescription' => 'Debian GNU/Linux 7.5 (wheezy)',
            'processorcount' => '4',
            'memorysize' => '4.00 GB',
            'uptime' => '302 days',
            'pf' => 'TEST',
            'hostname' => 'node9',
        ),
    );

    protected $eventsCount = array(
        'node1.local' => array(),
        'node2.local' => array(array(
            'subject' => array(
                'title' => 'node2.local'
            ),
            'subject-type' => 'certname',
            'failures' => 0,
            'successes' => 1,
            'noops' => 0,
            'skips' => 0
        )),
        'node3.local' => array(array(
            'subject' => array(
                'title' => 'node3.local'
            ),
            'subject-type' => 'certname',
            'failures' => 3,
            'successes' => 0,
            'noops' => 0,
            'skips' => 8
        )),
        'node4.local' => array(),
        'node5.local' => array(),
        'node6.local' => array(array(
            'subject' => array(
                'title' => 'node6.local'
            ),
            'subject-type' => 'certname',
            'failures' => 0,
            'successes' => 9,
            'noops' => 0,
            'skips' => 0
        )),
        'node7.local' => array(),
        'node8.local' => array(array(
            'subject' => array(
                'title' => 'node8.local'
            ),
            'subject-type' => 'certname',
            'failures' => 1,
            'successes' => 1,
            'noops' => 0,
            'skips' => 3
        )),
        'node9.local' => array(),
    );

    protected $reportsToday = array(
        array(
            'status' => 'success',
            'timestamp' => '2014-03-31T06:41:40.286Z',
            'certname' => 'node2.local',
            'resource-type' => 'File',
            'resource-title' => '/etc/default/hobbit-client',
            'containing-class' => 'Xymon::Client_debian',
            'message' => null,
        ), array(
            'status' => 'failure',
            'timestamp' => '2014-03-31T06:39:19.540Z',
            'certname' => 'node3.local',
            'resource-type' => 'File',
            'resource-title' => '/etc/default/hobbit-client',
            'containing-class' => 'Xymon::Client_debian',
            'message' => 'change from absent to present failed',
        ), array(
            'status' => 'failure',
            'timestamp' => '2014-03-31T03:27:09.567Z',
            'certname' => 'node3.local',
            'resource-type' => 'Exec',
            'resource-title' => 'xymond',
            'containing-class' => 'Xymon::Client_debian',
            'message' => 'change from stopped to started failed',
        ), array(
            'status' => 'failure',
            'timestamp' => '2014-03-31T06:33:35.130Z',
            'certname' => 'node3.local',
            'resource-type' => 'Package',
            'resource-title' => 'xymon-client',
            'containing-class' => 'Xymon::Client_debian',
            'message' => 'change from purged to present failed',
        ), array(
            'status' => 'skipped',
            'timestamp' => '2014-03-31T06:39:19.540Z',
            'certname' => 'node3.local',
            'resource-type' => 'File',
            'resource-title' => '/etc/mcollective/facts.yaml',
            'containing-class' => 'Mcollective::Plugin',
            'message' => null,
        ), array(
            'status' => 'skipped',
            'timestamp' => '2014-03-31T03:27:09.567Z',
            'certname' => 'node3.local',
            'resource-type' => 'Exec',
            'resource-title' => 'vulnerabilities',
            'containing-class' => 'Common::Security_debian',
            'message' => null,
        ), array(
            'status' => 'skipped',
            'timestamp' => '2014-03-31T06:33:35.130Z',
            'certname' => 'node3.local',
            'resource-type' => 'Package',
            'resource-title' => 'sssd',
            'containing-class' => 'Ad::Install',
            'message' => null,
        ), array(
            'status' => 'skipped',
            'timestamp' => '2014-03-31T06:39:19.540Z',
            'certname' => 'node3.local',
            'resource-type' => 'File',
            'resource-title' => '/etc/hosts',
            'containing-class' => 'Common::Hosts',
            'message' => null,
        ), array(
            'status' => 'skipped',
            'timestamp' => '2014-03-31T03:27:09.567Z',
            'certname' => 'node3.local',
            'resource-type' => 'Exec',
            'resource-title' => 'bind',
            'containing-class' => 'Common::DNS',
            'message' => null,
        ), array(
            'status' => 'skipped',
            'timestamp' => '2014-03-31T06:33:35.130Z',
            'certname' => 'node3.local',
            'resource-type' => 'Package',
            'resource-title' => 'apache2',
            'containing-class' => 'Apache',
            'message' => null,
        ), array(
            'status' => 'skipped',
            'timestamp' => '2014-03-31T06:39:19.540Z',
            'certname' => 'node3.local',
            'resource-type' => 'File',
            'resource-title' => '/etc/bash.bashrc',
            'containing-class' => 'Common::Bash',
            'message' => null,
        ), array(
            'status' => 'skipped',
            'timestamp' => '2014-03-31T03:27:09.567Z',
            'certname' => 'node3.local',
            'resource-type' => 'Exec',
            'resource-title' => 'apache-restart',
            'containing-class' => 'Apache',
            'message' => null,
        ), array(
            'status' => 'success',
            'timestamp' => '2014-03-31T06:34:13.077Z',
            'certname' => 'node6.local',
            'resource-type' => 'Service',
            'resource-title' => 'mcollective',
            'containing-class' => 'Mcollective::Service',
            'message' => null,
        ), array(
            'status' => 'success',
            'timestamp' => '2014-03-31T06:34:19.012Z',
            'certname' => 'node8.local',
            'resource-type' => 'File',
            'resource-title' => '/etc/hosts',
            'containing-class' => 'Common::Hosts',
            'message' => null,
        ), array(
            'status' => 'failure',
            'timestamp' => '2014-03-31T06:33:35.130Z',
            'certname' => 'node8.local',
            'resource-type' => 'Package',
            'resource-title' => 'xymon-client',
            'containing-class' => 'Xymon::Client_debian',
            'message' => 'change from purged to present failed',
        ), array(
            'status' => 'skipped',
            'timestamp' => '2014-03-31T06:39:19.540Z',
            'certname' => 'node8.local',
            'resource-type' => 'File',
            'resource-title' => '/etc/bash.bashrc',
            'containing-class' => 'Common::Bash',
            'message' => null,
        )
    );

    protected $reportsYesterday = array(
        array(
            'status' => 'success',
            'timestamp' => '2014-03-30T06:41:40.286Z',
            'certname' => 'node1.local',
            'resource-type' => 'File',
            'resource-title' => '/etc/bash.bashrc',
            'containing-class' => 'Common::Bash',
            'message' => null,
        ), array(
            'status' => 'skipped',
            'timestamp' => '2014-03-30T06:34:19.012Z',
            'certname' => 'node4.local',
            'resource-type' => 'Service',
            'resource-title' => 'mcollective',
            'containing-class' => 'Mcollective::Service',
            'message' => null,
        )
    );

    protected $factNames = array(
        "kernel",
        "operatingsystem",
        "osfamily",
        "uptime",
        "uptime_days",
        "hostname",
        "lsbdistcodename",
        "lsbdistdescription",
        "pf",
        "memorysize"
    );

    /**
     * @var string
     */
    protected $fakeDateTime;

    /**
     * @var boolean
     */
    protected $failure = false;

    public function __construct($fakeDateTime)
    {
        $this->setFakeDateTime($fakeDateTime);
    }

    public function send(Request $request = null)
    {
        $this->response = $this->getMock('Zend\Http\Response');

        $headers = new Headers();
        $isSuccess = !$this->isFailure();
        $statusLine = 'HTTP/1.0 200 OK';
        $body = '';

        if ($this->isFailure()) {
            $statusLine = 'HTTP/1.0 500 Internal Server Error';
        } elseif ($this->getMethod() == Request::METHOD_POST) {
            $body = Json::encode(['uuid' => 'f37d4e5e-b31b-49df-b89e-5762387d867b']);
        } elseif (preg_match("~/v3/nodes$~", $this->getUri())) {
            $body = Json::encode($this->nodes);
        } elseif (preg_match("~/v3/nodes\\?offset=([0-9]+)&limit=([0-9]+)&include\\-total=true$~", $this->getUri(), $matches)) {
            $body = Json::encode(array_slice($this->nodes, $matches[1], $matches[2]));
            $headers->addHeaders(array('X-Records' => count($this->nodes)));
        } elseif (preg_match("~/v3/nodes\\?query=%5B%22%3D%22%2C%20%5B%22fact%22%2C%20%22(.*)%22%5D%2C%20%22(.*)%22%5D~", $this->getUri(), $matches)) {
            $body = Json::encode($this->getNodesByFact($matches[1], $matches[2]));
        } elseif (preg_match("~/v3/nodes/(.*)/facts$~", $this->getUri(), $matches)) {
            $body = Json::encode($this->getFormattedFacts($matches[1]));
        } elseif (preg_match("~/v3/nodes/(.*)$~", $this->getUri(), $matches)) {
            $body = Json::encode($this->getNode($matches[1]));
        } elseif (preg_match("~/v3/event-counts\\?query=%5B%22and%22%2C%20%5B%22%3D%22%2C%20%22certname%22%2C%20%22(.*)%22%5D%2C%20%5B%22%3D%22%2C%20%22latest-report%3F%22%2C%20true%5D%5D~", $this->getUri(), $matches)) {
            $body = Json::encode($this->getEventsCount($matches[1]));
        } elseif (preg_match("~/v3/events\\?query=.+" . $this->getFakeDateTime() . ".+&offset=([0-9]+)&limit=([0-9]+)&include\\-total=true$~", $this->getUri(), $matches)) {
            $body = Json::encode(array_slice($this->reportsToday, $matches[1], $matches[2]));
            $headers->addHeaders(array('X-Records' => count($this->reportsToday)));
        } elseif (preg_match("~/v3/events\\?query=.+" . $this->getFakeDateTime() . "~", $this->getUri())) {
            $body = Json::encode($this->reportsToday);
        } elseif (preg_match("~/v3/events\\?query=~", $this->getUri())) {
            $body = Json::encode($this->reportsYesterday);
        } elseif (preg_match("~/v3/fact-names$~", $this->getUri())) {
            $body = Json::encode($this->factNames);
        } else {
            $isSuccess = false;
            $statusLine = 'HTTP/1.0 404 Not found';
        }

        $this->response->expects($this->any())
            ->method('getHeaders')
            ->will($this->returnValue($headers));

        $this->response->expects($this->any())
            ->method('getBody')
            ->will($this->returnValue($body));

        $this->response->expects($this->any())
            ->method('isSuccess')
            ->will($this->returnValue($isSuccess));

        $this->response->expects($this->any())
            ->method('renderStatusLine')
            ->will($this->returnValue($statusLine));

        return $this->response;
    }

    /**
     * Get Failure status.
     *
     * @return boolean
     */
    public function isFailure()
    {
        return $this->failure;
    }

    /**
     * Specify if the request should respond an internal server error (500)
     *
     * @param boolean $failure
     * @return FakeHttpClient
     */
    public function setFailure($failure)
    {
        $this->failure = $failure;
        return $this;
    }

    /**
     * Set failure to true
     *
     * @return FakeHttpClient
     */
    public function fails()
    {
        $this->setFailure(true);
        return $this;
    }

    /**
     * Get FakeDateTime.
     *
     * @return string
     */
    public function getFakeDateTime()
    {
        return $this->fakeDateTime;
    }

    /**
     * Set FakeDateTime.
     *
     * @param string $fakeDateTime
     * @return FakeHttpClient
     */
    public function setFakeDateTime($fakeDateTime)
    {
        $this->fakeDateTime = $fakeDateTime;
        return $this;
    }

    /**
     * Returns a matcher that matches when the method it is evaluated for
     * is executed zero or more times.
     *
     * @return \PHPUnit_Framework_MockObject_Matcher_AnyInvokedCount
     * @since  Method available since Release 3.0.0
     */
    public static function any()
    {
        return new \PHPUnit_Framework_MockObject_Matcher_AnyInvokedCount;
    }

    /**
     * @param  mixed $value
     * @return \PHPUnit_Framework_MockObject_Stub_Return
     * @since  Method available since Release 3.0.0
     */
    public static function returnValue($value)
    {
        return new \PHPUnit_Framework_MockObject_Stub_Return($value);
    }

    /**
     * @param $originalClassName
     * @return object
     */
    protected function getMock($originalClassName)
    {
        return \PHPUnit_Framework_MockObject_Generator::getMock($originalClassName);
    }

    /**
     * @param $fact
     * @param $value
     * @return array
     */
    protected function getNodesByFact($fact, $value)
    {
        $nodes = array();
        foreach ($this->nodes as $node) {
            $factValue = $this->getFact($node['name'], $fact);
            if ($factValue === $value) {
                $nodes[] = $node;
            }
        }
        return $nodes;
    }

    /**
     * @param $node
     * @param $fact
     * @return string
     */
    protected function getFact($node, $fact)
    {
        $nodeFacts = $this->getFacts($node);
        return isset($nodeFacts[$fact]) ? $nodeFacts[$fact] : null;
    }

    /**
     * @param $nodeName
     * @return array
     */
    protected function getNode($nodeName)
    {
        foreach ($this->nodes as $node) {
            if ($node['name'] === $nodeName) {
                return $node;
            }
        }
        return array('error' => "No information is known about $nodeName");
    }

    /**
     * @param $node
     * @return array
     */
    protected function getFacts($node)
    {
        return isset($this->facts[$node]) ? $this->facts[$node] : array();
    }

    /**
     * @param $node
     * @return array
     */
    protected function getFormattedFacts($node)
    {
        $facts = array();
        foreach ($this->getFacts($node) as $fact => $value) {
            $facts[] = array(
                'certname' => $node,
                'name' => $fact,
                'value' => $value
            );
        }
        return $facts;
    }

    /**
     * @param $node
     * @return array
     */
    protected function getEventsCount($node)
    {
        return $this->eventsCount[$node];
    }
}
