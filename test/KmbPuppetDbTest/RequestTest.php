<?php
namespace KmbPuppetDbTest;

use KmbPuppetDb\OrderBy;
use KmbPuppetDb\Query;
use KmbPuppetDb\Request;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function canGetSimpleFullUri()
    {
        $request = new Request('/fact-names');

        $this->assertEquals('/v3/fact-names', $request->getFullUri());
    }

    /** @test */
    public function canGetSimpleFullUriWithoutLeadingSlash()
    {
        $request = new Request('fact-names');

        $this->assertEquals('/v3/fact-names', $request->getFullUri());
    }

    /** @test */
    public function canGetFullUriWithQueryArray()
    {
        $request = new Request('/nodes', array(
            '=',
            array('fact', 'kernel'),
            'Linux',
        ));

        $this->assertEquals('/v3/nodes?query=%5B%22%3D%22%2C%20%5B%22fact%22%2C%20%22kernel%22%5D%2C%20%22Linux%22%5D', $request->getFullUri());
    }

    /** @test */
    public function canGetFullUriWithQueryObject()
    {
        $request = new Request('/nodes', new Query(array(
            '=',
            array('fact', 'kernel'),
            'Linux',
        )));

        $this->assertEquals('/v3/nodes?query=%5B%22%3D%22%2C%20%5B%22fact%22%2C%20%22kernel%22%5D%2C%20%22Linux%22%5D', $request->getFullUri());
    }

    /** @test */
    public function canGetFullUriWithOrderByArray()
    {
        $request = new Request('/nodes');
        $request->setOrderBy(array(
            array(
                'field' => 'value',
                'order' => 'desc',
            ),
            array(
                'field' => 'name',
            ),
        ));

        $this->assertEquals('/v3/nodes?order-by=%5B%7B%22field%22%3A%20%22value%22%2C%20%22order%22%3A%20%22desc%22%7D%2C%20%7B%22field%22%3A%20%22name%22%2C%20%22order%22%3A%20%22asc%22%7D%5D', $request->getFullUri());
    }

    /** @test */
    public function canGetFullUriWithOrderByObject()
    {
        $request = new Request('/nodes');
        $request->setOrderBy(new OrderBy(array(
            array(
                'field' => 'value',
                'order' => 'desc',
            ),
            array(
                'field' => 'name',
            ),
        )));

        $this->assertEquals('/v3/nodes?order-by=%5B%7B%22field%22%3A%20%22value%22%2C%20%22order%22%3A%20%22desc%22%7D%2C%20%7B%22field%22%3A%20%22name%22%2C%20%22order%22%3A%20%22asc%22%7D%5D', $request->getFullUri());
    }

    /** @test */
    public function canGetFullUriWithQueryAndOrderBy()
    {
        $request = new Request(
            '/nodes',
            array(
                '=',
                array('fact', 'kernel'),
                'Linux',
            ),
            array(
                array(
                    'field' => 'value',
                    'order' => 'desc',
                ),
                array(
                    'field' => 'name',
                ),
            )
        );

        $this->assertEquals('/v3/nodes?query=%5B%22%3D%22%2C%20%5B%22fact%22%2C%20%22kernel%22%5D%2C%20%22Linux%22%5D&order-by=%5B%7B%22field%22%3A%20%22value%22%2C%20%22order%22%3A%20%22desc%22%7D%2C%20%7B%22field%22%3A%20%22name%22%2C%20%22order%22%3A%20%22asc%22%7D%5D', $request->getFullUri());
    }

    /** @test */
    public function canGetFullUriWithPaging()
    {
        $request = new Request('/nodes');
        $request->setPaging(10, 20);

        $this->assertEquals('/v3/nodes?offset=10&limit=20&include-total=true', $request->getFullUri());
    }

    /** @test */
    public function canGetFullUriWhenOffsetAndLimitAreNull()
    {
        $request = new Request('/nodes');
        $request->setPaging(null, null);

        $this->assertEquals('/v3/nodes', $request->getFullUri());
    }

    /** @test */
    public function canGetFullUriWithSummarizeBy()
    {
        $request = new Request('/nodes');
        $request->setSummarizeBy('certname');

        $this->assertEquals('/v3/nodes?summarize-by=certname', $request->getFullUri());
    }
}
