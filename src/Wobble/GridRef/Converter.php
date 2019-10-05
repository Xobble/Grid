<?php

namespace Wobble\GridRef;

use Wobble\GridRef\Converter\GridRefConverter;
use Wobble\GridRef\Exception\GridRefException;

class Converter
{
    /** @var array|GridRefConverter[] */
    protected $converters;

    /**
     * Converter constructor.
     * @param array|GridRefConverter[] $converters
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
            if ($converter->supportsGridRef($gridRef)) {
                return $converter->toCartesian($gridRef);
            }
        }

        throw new GridRefException('Invalid or unsupported grid reference');
    }

    /**
     * @param Cartesian $cartesian
     * @return string
     * @throws GridRefException
     */
    public function toGridRef(Cartesian $cartesian) : string {
        foreach($this->converters as $converter) {
            if ($converter->supportsCartesian($cartesian)) {
                return $converter->toGridRef($cartesian);
            }
        }

        throw new GridRefException('Invalid or unsupported easting/northing');
    }
}