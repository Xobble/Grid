<?php

namespace Wobble\GridRef\Converter;

use Wobble\GridRef\Cartesian;

class ChannelIslandsWAGridRef implements GridRefConverter
{
    /** @var GridRefHelper */
    protected $helper;

    public function __construct()
    {
        $this->helper = new GridRefHelper($this->getDatum(), 2500000, 2, 0, 5100000);
    }

    public function supportsGridRef(string $gridRef): bool
    {
        if (!preg_match('/^WA(?P<number>[ \d]*)$/', $gridRef, $matches)) {
            return false;
        }

        $number = str_replace(' ', '', $matches['number']);
        return (strlen($number) % 2 === 0);
    }

    public function supportsCartesian(Cartesian $cartesian): bool
    {
        if (substr($cartesian->getNorthing(), 0, 2) !== '55') {
            return false;
        }

        return $cartesian->getDatum() === $this->getDatum();
    }

    public function toCartesian(string $gridRef): Cartesian
    {
        return $this->helper->toCartesian($gridRef);
    }

    public function toGridRef(Cartesian $cartesian): string
    {
        return $this->helper->toGridRef($cartesian);
    }

    public function getDatum(): string
    {
        return 'CI';
    }
}