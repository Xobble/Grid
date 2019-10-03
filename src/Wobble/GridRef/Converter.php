<?php

namespace Wobble\GridRef;

use Wobble\GridRef\Exception\GridRefException;

class Converter
{
    /** @var GridRefConverter[] */
    protected $converters;

    /**
     * Converter constructor.
     * @param array $converters
     */
    public function __construct(array $converters = [])
    {
        $this->converters = $converters;
    }

    public function toEastingNorthing(string $gridRef) : EastingNorthing {
        foreach($this->converters as $converter) {
            if ($converter->supportsGridRef($gridRef)) {
                return $converter->toEastingNorthing($gridRef);
            }
        }

        throw new GridRefException('Invalid or unsupported grid reference');
    }

    public function toGridRef(EastingNorthing $eastingNorthing) : string {
        foreach($this->converters as $converter) {
            if ($converter->supportsEastingNorthing($eastingNorthing)) {
                return $converter->toGridRef($eastingNorthing);
            }
        }

        throw new GridRefException('Invalid or unsupported easting/northing');
    }
}