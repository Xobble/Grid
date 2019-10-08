<?php

namespace Xobble\Grid\Tests;

use PHPUnit\Framework\TestCase;
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
     */
    public function testGridRefSupported(string $gridRef, bool $expectedSupport)
    {
        $irishGridRef = new IrishGridRef();
        $this->assertEquals($expectedSupport, $irishGridRef->supportsGridRef($gridRef));
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
     */
    public function testGridRefSupportedWhenRestricted(string $gridRef, bool $expectedSupport)
    {
        $irishGridRef = new IrishGridRef([
            'allowed_references' => ['H', 'J', 'K', 'L', 'M', 'N', 'Z'],
        ]);
        $this->assertEquals($expectedSupport, $irishGridRef->supportsGridRef($gridRef));
    }
}