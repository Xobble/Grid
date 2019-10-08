<?php

namespace Xobble\Grid;

interface GridRef
{
    public function supportsGridRef(string $gridRef) : bool;
    public function supportsCartesian(Cartesian $cartesian) : bool;

    public function getDatum() : string;
    public function getGridReferenceName() : string;

    public function toCartesian(string $gridRef) : Cartesian;
    public function toGridRef(Cartesian $cartesian) : string;
}