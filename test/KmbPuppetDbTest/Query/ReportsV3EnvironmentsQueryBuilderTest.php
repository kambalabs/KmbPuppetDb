<?php
namespace KmbPuppetDbTest\Query;

use KmbDomain\Model\Environment;
use KmbPuppetDb\Query\ReportsV3EnvironmentsQueryBuilder;

class ReportsV3EnvironmentsQueryBuilderTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canConvertToString()
    {
        $environment1 = new Environment();
        $environment1->setName('PF1');
        $environment2 = new Environment();
        $environment2->setName('PF2');
        $queryBuilder = new ReportsV3EnvironmentsQueryBuilder();

        $query = $queryBuilder->build([$environment1, $environment2]);

        $this->assertEquals('[]', (string)$query);
    }
}
