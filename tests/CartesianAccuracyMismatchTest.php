<?php

namespace Xobble\Grid\Tests;

use PHPUnit\Framework\TestCase;
use Xobble\Grid\Cartesian;
use Xobble\Grid\Exception\GridRefException;

class CartesianAccuracyMismatchTest extends TestCase
{
    public function dataTestCases() {
        return [
            ['123', '456', '1', true],
            ['12345', '45601', '100', false],
            ['12340', '45600', '100', false],
            ['12000', '45000', '100', true],
            ['1200.1', '45000', '0.1', true],
            ['1200.1', '45000.34', '0.1', false],
            ['0', '0', '10000', true],
            ['0', '0', '0.1', true],
        ];
    }

    /**
     * @dataProvider dataTestCases
     *
     * @param string $easting
     * @param string $northing
     * @param string $accuracy
     * @param bool $expectedToSucceed
     *
     * @throws GridRefException
     */
    public function testAccuracyMismatch(string $easting, string $northing, string $accuracy, bool $expectedToSucceed)
    {
        if (!$expectedToSucceed) {
            $this->expectException(GridRefException::class);
        }

        $cartesian = new Cartesian('EPSG:27700', $easting, $northing, $accuracy);

        if ($expectedToSucceed) {
            $this->assertInstanceOf(Cartesian::class, $cartesian);
        }
    }

}