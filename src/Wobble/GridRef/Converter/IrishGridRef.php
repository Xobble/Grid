<?php

namespace Wobble\GridRef\Converter;

use Wobble\GridRef\EastingNorthing;

class IrishGridRef implements GridRefConverter
{
    /** @var GridRefHelper */
    protected $helper;

    public function __construct()
    {
        $this->helper = new GridRefHelper($this->getDatum(), 500000, 1);
    }

    public function supportsGridRef(string $gridRef) : bool
    {
        if (!preg_match('/^[A-HJ-Z](?P<number>[ \d]*)$/', $gridRef, $matches)) {
            return false;
        }

        $number = str_replace(' ', '', $matches['number']);
        return (strlen($number) % 2 === 0);
    }

    public function supportsEastingNorthing(EastingNorthing $eastingNorthing) : bool
    {
        return $eastingNorthing->getDatum() === $this->getDatum();
    }

    public function toEastingNorthing(string $gridRef) : EastingNorthing
    {
        return $this->helper->toEastingNorthing($gridRef);
    }

    public function toGridRef(EastingNorthing $eastingNorthing): string
    {
        return $this->helper->toGridRef($eastingNorthing);
    }

    public function getDatum(): string
    {
        return 'IRISH';
    }
}