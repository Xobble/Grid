<?php

namespace Wobble\Grid\GridRef;

use Wobble\Grid\GridRef;
use Wobble\Grid\Cartesian;

class BritishGridRef implements GridRef
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

        return (strlen($number) % 2 === 0);
    }

    public function supportsCartesian(Cartesian $cartesian): bool
    {
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

    public function getGridReferenceName(): string
    {
        return 'British National Grid';
    }

    public function getDatum(): string
    {
        return 'EPSG:27700';
    }
}