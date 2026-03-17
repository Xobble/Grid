<?php

namespace Xobble\Grid\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Xobble\Grid\Cartesian;
use Xobble\Grid\Exception\GridRefException;
use Xobble\Grid\GridRef\BritishGridRef;

class BritishGridTest extends TestCase
{
    private const DATUM = 27700;

    public static function dataTestCases(): array {
        return [
            ['TG 51409 13177', 651409, 313177, 1],
            ['NA9999999999', 99999, 999999, 1],
            ['SV000000000000', 0, 0, 0.1],
            ['SV0000000000', 0, 0, 1],
            ['SV00000000', 0, 0, 10],
            ['SV000000', 0, 0, 100],
            ['SV0000', 0, 0, 1000],
            ['SV00', 0, 0, 10000],
            ['SV', 0, 0, 100000],
            ['NA960060', 96000, 906000, 100],
            ['SN109112', 210900, 211200, 100],
            ['HZ12345678', 412340, 1056780, 10],
            ['SK123456', 412300, 345600, 100],
            ['SP513061', 451300, 206100, 100],
        ];
    }

    /**
     * @throws GridRefException
     */
    #[DataProvider('dataTestCases')]
    public function testGridRefToCartesian(string $gridRef, float $expectedEasting, float $expectedNorthing, float $expectedAccuracy): void
    {
        $britishGridRef = new BritishGridRef();
        $cartesian = $britishGridRef->toCartesian($gridRef);

        $expected = 'EPSG:'.self::DATUM."({$expectedEasting}, {$expectedNorthing}) [{$expectedAccuracy}m]";

        $this->assertEquals($expected, $cartesian->__toString());
    }

    /**
     * @throws GridRefException
     */
    #[DataProvider('dataTestCases')]
    public function testCartesianToGridRef(string $gridRef, float $easting, float $northing, float $accuracy): void
    {
        $britishGridRef = new BritishGridRef();
        $cartesian = new Cartesian(self::DATUM, $easting, $northing, $accuracy);

        $expectedGridRef = str_replace(' ', '', $gridRef);
        $this->assertEquals($expectedGridRef, $britishGridRef->toGridRef($cartesian));
    }
}
