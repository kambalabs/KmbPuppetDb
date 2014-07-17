<?php
namespace KmbPuppetDbTest;

use KmbPuppetDb\Query;

class QueryTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canConvertEmptyQueryToString()
    {
        $query = new Query(array());

        $this->assertEquals('[]', (string) $query);
    }

    /** @test */
    public function canConvertSingleLevelQueryToString()
    {
        $query = new Query(array(
            '=',
            'certname',
            'node1.local'
        ));

        $this->assertEquals('["=", "certname", "node1.local"]', (string) $query);
    }

    /** @test */
    public function canConvertMultipleLevelQueryToString()
    {
        $query = new Query(array(
            'AND',
            array(
                '=',
                array('fact', 'kernelversion'),
                '3.2.0'
            ),
            array(
                '=',
                array('fact', 'architecture'),
                'amd64'
            ),
        ));

        $this->assertEquals('["AND", ["=", ["fact", "kernelversion"], "3.2.0"], ["=", ["fact", "architecture"], "amd64"]]', (string) $query);
    }

    /** @test */
    public function canConvertQueryWithTrueBooleanToString()
    {
        $query = new Query(array('=', 'latest-report?', true));

        $this->assertEquals('["=", "latest-report?", true]', (string) $query);
    }

    /** @test */
    public function canConvertQueryWithFalseBooleanToString()
    {
        $query = new Query(array('=', 'latest-report?', false));

        $this->assertEquals('["=", "latest-report?", false]', (string) $query);
    }
}
