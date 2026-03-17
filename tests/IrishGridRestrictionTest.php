<?php

namespace Xobble\Grid\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Xobble\Grid\Cartesian;
use Xobble\Grid\Exception\GridRefException;
use Xobble\Grid\Exception\UnsupportedRefException;
use Xobble\Grid\GridRef\IrishGridRef;

class IrishGridRestrictionTest extends TestCase
{
    public static function dataTestCases(): array {
        return [
            ['A00', true],
            ['B00', true],
            ['C00', true],
            ['D00', true],
            ['H00', true],
            ['I00', false],
            ['M00', true],
            ['Z00', true],
        ];
    }

    /**
     * @throws GridRefException
     */
    #[DataProvider('dataTestCases')]
    public function testGridRefSupported(string $gridRef, bool $expectedSupport): void
    {
        if (!$expectedSupport) {
            $this->expectException(UnsupportedRefException::class);
        }

        $irishGridRef = new IrishGridRef();
        $cartesian = $irishGridRef->toCartesian($gridRef);

        if ($expectedSupport) {
            $this->assertInstanceOf(Cartesian::class, $cartesian);
        }
    }

    public static function dataTestCasesWhenRestricted(): array {
        return [
            ['A00', false],
            ['B00', false],
            ['C00', false],
            ['D00', false],
            ['H00', true],
            ['I00', false],
            ['M00', true],
            ['Z00', true],
        ];
    }

    /**
     * @throws GridRefException
     */
    #[DataProvider('dataTestCasesWhenRestricted')]
    public function testGridRefSupportedWhenRestricted(string $gridRef, bool $expectedSupport): void
    {
        if (!$expectedSupport) {
            $this->expectException(UnsupportedRefException::class);
        }

        $irishGridRef = new IrishGridRef([
            'allowed_references' => ['H', 'J', 'K', 'L', 'M', 'N', 'Z'],
        ]);
        $cartesian = $irishGridRef->toCartesian($gridRef);

        if ($expectedSupport) {
            $this->assertInstanceOf(Cartesian::class, $cartesian);
        }
    }
}
