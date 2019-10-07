<?php

namespace Wobble\Grid\Tests;

use PHPUnit\Framework\TestCase;
use Wobble\Grid\Cartesian;
use Wobble\Grid\GridRef\BritishGridRef;

class BritishGridTest extends TestCase
{
    const DATUM = 'EPSG:27700';

    public function dataTestCases() {
        return [
            ['TG 51409 13177', '651409', '313177', '1'],
            ['NA9999999999', '99999', '999999', '1'],
            ['SV000000000000', '0', '0', '0.1'],
            ['SV0000000000', '0', '0', '1'],
            ['SV00000000', '0', '0', '10'],
            ['SV000000', '0', '0', '100'],
            ['SV0000', '0', '0', '1000'],
            ['SV00', '0', '0', '10000'],
            ['SV', '0', '0', '100000'],
            ['NA960060', '96000', '906000', '100'],
            ['SN109112', '210900', '211200', '100'],
            ['HZ12345678', '412340', '1056780', '10'],
            ['SK123456', '412300', '345600', '100'],
            ['SP513061', '451300', '206100', '100'],
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
        $britishGridRef = new BritishGridRef();
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
        $britishGridRef = new BritishGridRef();
        $cartesian = new Cartesian(self::DATUM, $easting, $northing, $accuracy);

        $expectedGridRef = str_replace(' ', '', $gridRef);
        $this->assertEquals($expectedGridRef, $britishGridRef->toGridRef($cartesian));
    }
}