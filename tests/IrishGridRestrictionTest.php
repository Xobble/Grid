<?php

namespace Xobble\Grid\Tests;

use PHPUnit\Framework\TestCase;
use Xobble\Grid\Cartesian;
use Xobble\Grid\Exception\GridRefException;
use Xobble\Grid\Exception\UnsupportedRefException;
use Xobble\Grid\GridRef\IrishGridRef;

class IrishGridRestrictionTest extends TestCase
{
    public function dataTestCases() {
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
     * @dataProvider dataTestCases
     *
     * @param string $gridRef
     * @param bool $expectedSupport
     * @throws GridRefException
     */
    public function testGridRefSupported(string $gridRef, bool $expectedSupport)
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

    public function dataTestCasesWhenRestricted() {
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
     * @dataProvider dataTestCasesWhenRestricted
     *
     * @param string $gridRef
     * @param bool $expectedSupport
     * @throws GridRefException
     */
    public function testGridRefSupportedWhenRestricted(string $gridRef, bool $expectedSupport)
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