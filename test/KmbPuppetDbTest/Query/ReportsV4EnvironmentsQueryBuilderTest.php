<?php
namespace KmbPuppetDbTest\Query;

use KmbDomain\Model\Environment;
use KmbPuppetDb\Query\ReportsV4EnvironmentsQueryBuilder;

class ReportsV4EnvironmentsQueryBuilderTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canConvertToString()
    {
        $environment1 = new Environment();
        $environment1->setName('PF1');
        $environment2 = new Environment();
        $environment2->setName('PF2');
        $queryBuilder = new ReportsV4EnvironmentsQueryBuilder();

        $query = $queryBuilder->build([$environment1, $environment2]);

        $this->assertEquals('["OR", ["=", "environment", "PF1"], ["=", "environment", "PF2"]]', (string)$query);
    }
}
