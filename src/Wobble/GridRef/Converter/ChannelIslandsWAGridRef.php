<?php

namespace Wobble\GridRef\Converter;

use Wobble\GridRef\EastingNorthing;

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

    public function supportsEastingNorthing(EastingNorthing $eastingNorthing): bool
    {
        if (substr($eastingNorthing->getNorthing(), 0, 2) !== '55') {
            return false;
        }

        return $eastingNorthing->getDatum() === $this->getDatum();
    }

    public function toEastingNorthing(string $gridRef): EastingNorthing
    {
        return $this->helper->toEastingNorthing($gridRef);
    }

    public function toGridRef(EastingNorthing $eastingNorthing): string
    {
        return $this->helper->toGridRef($eastingNorthing);
    }

    public function getDatum(): string
    {
        return 'CI';
    }
}