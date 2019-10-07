<?php

namespace Wobble\Grid\Tests;

use PHPUnit\Framework\TestCase;
use Wobble\Grid\Cartesian;
use Wobble\Grid\GridRef\IrishGridRef;

class IrishGridTest extends TestCase
{
    const DATUM = 'EPSG:29902';

    public function dataTestCases() {
        return [
            ['O1590434671', '315904', '234671', '1'],
            ['J 02598 74444', '302598', '374444', '1'],
            ['K 87600 82200', '487600', '382200', '1'],
            ['W0225', '102000', '25000', '1000'],
            ['F632251','63200', '325100', '100'],
            ['X622997','262200', '99700', '100'],
            ['O2718', '327000', '218000', '1000'],
            ['J36', '330000', '360000', '10000'],
            ['G9501527196', '195015', '327196', '1'],
        ];
    }

    /**
     * @dataProvider dataTestCases
     *
     * @param string $gridRef
     * @param string $expectedEasting
     * @param string $expectedNorthing
     * @param string $expectedAccuracy
     */
    public function testGridRefToCartesian(string $gridRef, string $expectedEasting, string $expectedNorthing, string $expectedAccuracy)
    {
        $britishGridRef = new IrishGridRef();
        $cartesian = $britishGridRef->toCartesian($gridRef);

        $expected = self::DATUM."({$expectedEasting}, {$expectedNorthing}) [{$expectedAccuracy}m]";

        $this->assertEquals($expected, $cartesian->__toString());
    }

    /**
     * @dataProvider dataTestCases
     *
     * @param string $gridRef
     * @param string $easting
     * @param string $northing
     * @param string $accuracy
     */
    public function testCartesianToGridRef(string $gridRef, string $easting, string $northing, string $accuracy)
    {
        $britishGridRef = new IrishGridRef();
        $cartesian = new Cartesian(self::DATUM, $easting, $northing, $accuracy);

        $expectedGridRef = str_replace(' ', '', $gridRef);
        $this->assertEquals($expectedGridRef, $britishGridRef->toGridRef($cartesian));
    }
}