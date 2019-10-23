<?php

namespace Xobble\Grid;

use Xobble\Grid\Exception\GridRefException;
use Xobble\Grid\Exception\UnsupportedRefException;

class Converter
{
    /** @var array|GridRef[] */
    protected $converters;

    /**
     * Converter constructor.
     * @param array|GridRef[] $converters
     */
    public function __construct(array $converters = [])
    {
        $this->converters = $converters;
    }

    /**
     * @param string $gridRef
     * @return Cartesian
     * @throws GridRefException
     */
    public function toCartesian(string $gridRef) : Cartesian {
        foreach($this->converters as $converter) {
            try {
                return $converter->toCartesian($gridRef);
            }
            catch(UnsupportedRefException $e) {}
        }

        throw new UnsupportedRefException('Unsupported grid reference');
    }

    /**
     * @param Cartesian $cartesian
     * @return string
     * @throws GridRefException
     */
    public function toGridRef(Cartesian $cartesian) : string {
        foreach($this->converters as $converter) {
            try {
                return $converter->toGridRef($cartesian);
            }
            catch(UnsupportedRefException $e) {}
        }

        throw new UnsupportedRefException('Unsupported Cartesian coordinates');
    }
}