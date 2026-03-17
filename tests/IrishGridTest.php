<?php

namespace Xobble\Grid\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Xobble\Grid\Cartesian;
use Xobble\Grid\Exception\GridRefException;
use Xobble\Grid\GridRef\IrishGridRef;

class IrishGridTest extends TestCase
{
    private const DATUM = 29902;

    public static function dataTestCases(): array {
        return [
            ['O1590434671', 315904, 234671, 1],
            ['J 02598 74444', 302598, 374444, 1],
            ['K 87600 82200', 487600, 382200, 1],
            ['W0225', 102000, 25000, 1000],
            ['F632251',63200, 325100, 100],
            ['X622997',262200, 99700, 100],
            ['O2718', 327000, 218000, 1000],
            ['J36', 330000, 360000, 10000],
            ['G9501527196', 195015, 327196, 1],
        ];
    }

    /**
     * @throws GridRefException
     */
    #[DataProvider('dataTestCases')]
    public function testGridRefToCartesian(string $gridRef, float $expectedEasting, float $expectedNorthing, float $expectedAccuracy): void
    {
        $britishGridRef = new IrishGridRef();
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
        $britishGridRef = new IrishGridRef();
        $cartesian = new Cartesian(self::DATUM, $easting, $northing, $accuracy);

        $expectedGridRef = str_replace(' ', '', $gridRef);
        $this->assertEquals($expectedGridRef, $britishGridRef->toGridRef($cartesian));
    }
}
