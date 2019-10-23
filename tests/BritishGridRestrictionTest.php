<?php

namespace Xobble\Grid\Tests;

use PHPUnit\Framework\TestCase;
use Xobble\Grid\Cartesian;
use Xobble\Grid\Exception\GridRefException;
use Xobble\Grid\Exception\UnsupportedRefException;
use Xobble\Grid\GridRef\BritishGridRef;

class BritishGridRestrictionTest extends TestCase
{
    public function dataTestCases() {
        return [
            ['HL00', false],
            ['TW00', false],
            ['DQ00', false],
            ['NP00', false],
            ['JL00', false],
            ['IL00', false],
            ['SI00', false],
            ['WA00', false],
            ['WV00', false],
            ['SR00', true],
            ['SC00', true],
            ['NT00', true],
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

        $britishGridRef = new BritishGridRef();
        $cartesian = $britishGridRef->toCartesian($gridRef);

        if ($expectedSupport) {
            $this->assertInstanceOf(Cartesian::class, $cartesian);
        }
    }

    public function dataTestCasesWhenNoRestrictions() {
        return [
            ['HL00', true],
            ['TW00', true],
            ['DQ00', true],
            ['NP00', true],
            ['JL00', true],
            ['IL00', false],
            ['SI00', false],
            ['WA00', true],
            ['WV00', true],
            ['SR00', true],
            ['SC00', true],
            ['NT00', true],
        ];
    }

    /**
     * @dataProvider dataTestCasesWhenNoRestrictions
     *
     * @param string $gridRef
     * @param bool $expectedSupport
     * @throws GridRefException
     */
    public function testGridRefSupportedWhenNoRestrictions(string $gridRef, bool $expectedSupport)
    {
        if (!$expectedSupport) {
            $this->expectException(UnsupportedRefException::class);
        }

        $britishGridRef = new BritishGridRef([
            'allowed_references' => null,
            'grid_exclude_channel_islands' => false,
        ]);
        $cartesian = $britishGridRef->toCartesian($gridRef);

        if ($expectedSupport) {
            $this->assertInstanceOf(Cartesian::class, $cartesian);
        }
    }

    public function dataChannelIslandTestCases() {
        return [
            ['HL00', true],
            ['TW00', true],
            ['DQ00', true],
            ['NP00', true],
            ['JL00', true],
            ['IL00', false],
            ['SI00', false],
            ['WA00', false],
            ['WV00', false],
            ['SR00', true],
            ['SC00', true],
            ['NT00', true],
        ];
    }

    /**
     * @dataProvider dataChannelIslandTestCases
     *
     * @param string $gridRef
     * @param bool $expectedSupport
     * @throws GridRefException
     */
    public function testGridRefSupportedWhenOnlyChannelIslandRestrictions(string $gridRef, bool $expectedSupport)
    {
        if (!$expectedSupport) {
            $this->expectException(UnsupportedRefException::class);
        }

        $britishGridRef = new BritishGridRef([
            'allowed_references' => null,
        ]);
        $cartesian = $britishGridRef->toCartesian($gridRef);

        if ($expectedSupport) {
            $this->assertInstanceOf(Cartesian::class, $cartesian);
        }
    }
}