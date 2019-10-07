<?php

namespace Wobble\Grid\Tests;

use PHPUnit\Framework\TestCase;
use Wobble\Grid\Cartesian;
use Wobble\Grid\GridRef\ChannelIslandsGridRef;

class ChannelIslandsGridTest extends TestCase
{
    const DATUM = 'EPSG:32630';

    public function dataTestCases() {
        return [
            ['WV38', '530000', '5480000', '10000'],
            ['WV47', '540000', '5470000', '10000'],
            ['WV33607982', '533600', '5479820', '10'],
            ['WV640559', '564000', '5455900', '100'],
            ['WA5507', '555000', '5507000', '1000'],
            ['WV333813', '533300', '5481300', '100'],
            ['WV24917846', '524910', '5478460', '10'],
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
        $britishGridRef = new ChannelIslandsGridRef();
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
        $britishGridRef = new ChannelIslandsGridRef();
        $cartesian = new Cartesian(self::DATUM, $easting, $northing, $accuracy);

        $expectedGridRef = str_replace(' ', '', $gridRef);
        $this->assertEquals($expectedGridRef, $britishGridRef->toGridRef($cartesian));
    }
}