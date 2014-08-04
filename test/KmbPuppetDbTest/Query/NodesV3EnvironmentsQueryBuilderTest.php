<?php
namespace KmbPuppetDbTest\Query;

use KmbDomain\Model\Environment;
use KmbPuppetDb\Query\NodesV3EnvironmentsQueryBuilder;

class NodesV3EnvironmentsQueryBuilderTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canBuildSingleEnvironmentQuery()
    {
        $environment = new Environment();
        $environment->setName('PF1');
        $queryBuilder = new NodesV3EnvironmentsQueryBuilder();

        $query = $queryBuilder->build([$environment]);

        $this->assertEquals('["=", ["fact", "kmbenv"], "PF1"]', (string)$query);
    }

    /** @test */
    public function canConvertToString()
    {
        $environment1 = new Environment();
        $environment1->setName('PF1');
        $environment2 = new Environment();
        $environment2->setName('PF2');
        $queryBuilder = new NodesV3EnvironmentsQueryBuilder();

        $query = $queryBuilder->build([$environment1, $environment2]);

        $this->assertEquals('["OR", ["=", ["fact", "kmbenv"], "PF1"], ["=", ["fact", "kmbenv"], "PF2"]]', (string)$query);
    }
}
