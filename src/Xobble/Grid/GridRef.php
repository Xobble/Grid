<?php

namespace Xobble\Grid;

use Xobble\Grid\Exception\GridRefException;

interface GridRef
{
    public function getDatum() : int;
    public function getGridReferenceName() : string;

    /**
     * @param string $gridRef
     * @return Cartesian
     * @throws GridRefException
     */
    public function toCartesian(string $gridRef) : Cartesian;

    /**
     * @param Cartesian $cartesian
     * @return string
     * @throws GridRefException
     */
    public function toGridRef(Cartesian $cartesian) : string;
}