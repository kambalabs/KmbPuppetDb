<?php
namespace KmbPuppetDbTest\Query;

use KmbPuppetDb\Query\Query;

class QueryTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canConvertEmptyQueryToString()
    {
        $query = new Query([]);

        $this->assertEquals('[]', (string)$query);
    }

    /** @test */
    public function canConvertSingleLevelQueryToString()
    {
        $query = new Query(['=', 'certname', 'node1.local']);

        $this->assertEquals('["=", "certname", "node1.local"]', (string)$query);
    }

    /** @test */
    public function canConvertMultipleLevelQueryToString()
    {
        $query = new Query([
            'AND',
            [
                '=',
                ['fact', 'kernelversion'],
                '3.2.0'
            ],
            [
                '=',
                ['fact', 'architecture'],
                'amd64'
            ],
        ]);

        $this->assertEquals('["AND", ["=", ["fact", "kernelversion"], "3.2.0"], ["=", ["fact", "architecture"], "amd64"]]', (string)$query);
    }

    /** @test */
    public function canConvertQueryWithTrueBooleanToString()
    {
        $query = new Query(['=', 'latest-report?', true]);

        $this->assertEquals('["=", "latest-report?", true]', (string)$query);
    }

    /** @test */
    public function canConvertQueryWithFalseBooleanToString()
    {
        $query = new Query(['=', 'latest-report?', false]);

        $this->assertEquals('["=", "latest-report?", false]', (string)$query);
    }
}
