<?php

namespace Wobble\Grid\GridRef;

use Wobble\Grid\GridRef;
use Wobble\Grid\Cartesian;

class IrishGridRef implements GridRef
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

    public function supportsCartesian(Cartesian $cartesian) : bool
    {
        return $cartesian->getDatum() === $this->getDatum();
    }

    public function toCartesian(string $gridRef) : Cartesian
    {
        return $this->helper->toCartesian($gridRef);
    }

    public function toGridRef(Cartesian $cartesian) : string
    {
        return $this->helper->toGridRef($cartesian);
    }

    public function getDatum(): string
    {
        return 'IRISH';
    }
}