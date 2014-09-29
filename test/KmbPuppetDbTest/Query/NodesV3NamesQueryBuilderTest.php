<?php
namespace KmbPuppetDbTest\Query;

use KmbPuppetDb\Query\NodesV3NamesQueryBuilder;

class NodesV3NamesQueryBuilderTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canBuild()
    {
        $queryBuilder = new NodesV3NamesQueryBuilder();

        $query = $queryBuilder->build(['node1\.local'], '~');

        $this->assertEquals('["~", "name", "node1\\\\.local"]', (string)$query);
    }

    /** @test */
    public function canBuildEmpty()
    {
        $queryBuilder = new NodesV3NamesQueryBuilder();

        $query = $queryBuilder->build([''], '~');

        $this->assertNull($query);
    }
}
