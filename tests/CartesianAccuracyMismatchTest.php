<?php

namespace Xobble\Grid\Tests;

use PHPUnit\Framework\TestCase;
use Xobble\Grid\Cartesian;
use Xobble\Grid\Exception\GridRefException;

class CartesianAccuracyMismatchTest extends TestCase
{
    public function dataTestCases() {
        return [
            [123, 456, 1, true],
            [12345, 45601, 100, false],
            [12340, 45600, 100, false],
            [12000, 45000, 100, true],
            [12001, 500, 0.1, true],
            [12001, 500.34, 0.1, false],
            [0, 0, 10000, true],
            [0, 0, 0.1, true],
        ];
    }

    /**
     * @dataProvider dataTestCases
     *
     * @param float $easting
     * @param float $northing
     * @param float $accuracy
     * @param bool $expectedToSucceed
     *
     * @throws GridRefException
     */
    public function testAccuracyMismatch(float $easting, float $northing, float $accuracy, bool $expectedToSucceed)
    {
        if (!$expectedToSucceed) {
            $this->expectException(GridRefException::class);
        }

        $cartesian = new Cartesian(27700, $easting, $northing, $accuracy);

        if ($expectedToSucceed) {
            $this->assertInstanceOf(Cartesian::class, $cartesian);
        }
    }

}