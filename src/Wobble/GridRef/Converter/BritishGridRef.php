<?php

namespace Wobble\GridRef\Converter;

use Wobble\GridRef\EastingNorthing;

class BritishGridRef implements GridRefConverter
{
    /** @var GridRefHelper */
    protected $helper;

    public function __construct()
    {
        $this->helper = new GridRefHelper($this->getDatum(), 2500000, 2, -1000000, -500000);
    }

    public function supportsGridRef(string $gridRef): bool
    {
        if (!preg_match('/^(?P<prefix>[A-HJ-Z]{2})(?P<number>[ \d]*)$/', $gridRef, $matches)) {
            return false;
        }

        $prefix = $matches['prefix'];
        $number = str_replace(' ', '', $matches['number']);

        if ($prefix === 'WA' || $prefix === 'WV') {
            return false;
        }

        // TODO: Check grid prefix is otherwise valid

        $number = str_replace(' ', '', $matches['number']);
        return (strlen($number) % 2 === 0);
    }

    public function supportsEastingNorthing(EastingNorthing $eastingNorthing): bool
    {
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
        return 'GB';
    }
}