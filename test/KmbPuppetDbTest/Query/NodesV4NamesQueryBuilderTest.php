<?php
namespace KmbPuppetDbTest\Query;

use KmbPuppetDb\Query\NodesV4NamesQueryBuilder;

class NodesV4NamesQueryBuilderTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canBuild()
    {
        $queryBuilder = new NodesV4NamesQueryBuilder();

        $query = $queryBuilder->build(['node1\.local'], '~');

        $this->assertEquals('["~", "name", "node1\\\\.local"]', (string)$query);
    }

    /** @test */
    public function canBuildEmpty()
    {
        $queryBuilder = new NodesV4NamesQueryBuilder();

        $query = $queryBuilder->build([''], '~');

        $this->assertNull($query);
    }
}
