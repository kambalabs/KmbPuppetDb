<?php
namespace KmbPuppetDbTest\Query;

use KmbDomain\Model\Environment;
use KmbPuppetDb\Query\NodesV4EnvironmentsQueryBuilder;

class NodesV4EnvironmentQueryBuilderTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canConvertToStringSingleEnvironment()
    {
        $environment = new Environment();
        $environment->setName('PF1');
        $queryBuilder = new NodesV4EnvironmentsQueryBuilder();

        $query = $queryBuilder->build([$environment]);

        $this->assertEquals('["=", "facts-environment", "PF1"]', (string)$query);
    }

    /** @test */
    public function canConvertToString()
    {
        $environment1 = new Environment();
        $environment1->setName('PF1');
        $environment2 = new Environment();
        $environment2->setName('PF2');
        $queryBuilder = new NodesV4EnvironmentsQueryBuilder();

        $query = $queryBuilder->build([$environment1, $environment2]);

        $this->assertEquals('["OR", ["=", "facts-environment", "PF1"], ["=", "facts-environment", "PF2"]]', (string)$query);
    }
}
