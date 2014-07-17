<?php
namespace KmbPuppetDbTest;

use KmbPuppetDb\OrderBy;

class OrderByTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canConvertEmptyOrderByToString()
    {
        $orderBy = new OrderBy(array());

        $this->assertEquals('[]', (string) $orderBy);
    }

    /** @test */
    public function canConvertOrderByToString()
    {
        $orderBy = new OrderBy(array(
            array(
                'field' => 'value',
                'order' => 'desc',
            ),
            array(
                'field' => 'name',
            ),
        ));

        $this->assertEquals('[{"field": "value", "order": "desc"}, {"field": "name", "order": "asc"}]', (string) $orderBy);
    }
}
