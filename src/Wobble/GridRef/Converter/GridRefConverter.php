<?php

namespace Wobble\GridRef\Converter;

use Wobble\GridRef\EastingNorthing;

interface GridRefConverter
{
    public function supportsGridRef(string $gridRef) : bool;
    public function supportsEastingNorthing(EastingNorthing $eastingNorthing) : bool;
    public function getDatum() : string;

    public function toEastingNorthing(string $gridRef) : EastingNorthing;
    public function toGridRef(EastingNorthing $eastingNorthing) : string;
}