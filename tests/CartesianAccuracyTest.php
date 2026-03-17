<?php

namespace Xobble\Grid\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Xobble\Grid\Cartesian;
use Xobble\Grid\Exception\GridRefException;

class CartesianAccuracyTest extends TestCase
{
    public static function dataTestCases(): array {
        return [
            [1, true],
            [100, true],
            [0.1, true],
            [10000, true],
            [0.2, false],
            [69, false],
            [10101, false],
            [1.6, false],
            [10000.1, false],
        ];
    }

    /**
     * @throws GridRefException
     */
    #[DataProvider('dataTestCases')]
    public function testAccuracyIsPowerOf10WithIntegerExponent(float $accuracy, bool $expectedToSucceed): void
    {
        if (!$expectedToSucceed) {
            $this->expectException(GridRefException::class);
       }

        $cartesian = new Cartesian(27700, 0, 0, $accuracy);

        if ($expectedToSucceed) {
            $this->assertInstanceOf(Cartesian::class, $cartesian);
        }
    }
}
