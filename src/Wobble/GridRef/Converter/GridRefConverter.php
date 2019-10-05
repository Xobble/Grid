<?php

namespace Wobble\GridRef\Converter;

use Wobble\GridRef\Cartesian;

interface GridRefConverter
{
    public function supportsGridRef(string $gridRef) : bool;
    public function supportsCartesian(Cartesian $cartesian) : bool;
    public function getDatum() : string;

    public function toCartesian(string $gridRef) : Cartesian;
    public function toGridRef(Cartesian $cartesian) : string;
}