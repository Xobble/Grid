<?php

namespace Wobble\GridRef\Converter;

use Wobble\GridRef\Cartesian;

class GridRefHelper
{
    /** @var string */
    protected $datum;

    /** @var int */
    protected $initialGridSize;

    /** @var int */
    protected $letterPositions;

    /** @var int */
    protected $eastingOriginOffset;

    /** @var int */
    protected $northingOriginOffset;

    /** @var array */
    protected $mapping;

    /** @var array */
    protected $reverseMapping;

    public function __construct(string $datum, int $gridSize, int $letterPositions, int $eastingOriginOffset = 0, int $northingOriginOffset = 0)
    {
        $this->datum = $datum;
        $this->eastingOriginOffset = $eastingOriginOffset;
        $this->northingOriginOffset = $northingOriginOffset;
        $this->letterPositions = $letterPositions;
        $this->initialGridSize = $gridSize;

        $this->mapping = [];

        for ($j = 0; $j < 5; $j++) {
            for ($i = 0; $i < 5; $i++) {
                $index = $j * 5 + $i + ord('A');
                $index += $index >= ord('I') ? 1 : 0;

                $chr = chr($index);
                $this->mapping[$chr] = [$i, 4 - $j];
                $this->reverseMapping[$i][4-$j] = $chr;
            }
        }
    }

    public function toCartesian(string $gridRef) : Cartesian
    {
        $gridRef = str_replace(' ', '', $gridRef);
        $easting = $this->eastingOriginOffset;
        $northing = $this->northingOriginOffset;
        $gridSize = $this->initialGridSize;

        // Deal with letter prefixes
        for($z=0; $z<$this->letterPositions; $z++) {
            $gridSize /= 5;

            $letter = substr($gridRef, 0, 1);
            list($i, $j) = $this->mapping[$letter];

            $easting += ($gridSize * $i);
            $northing += ($gridSize * $j);

            $gridRef = substr($gridRef, 1);
        }

        // Deal with number part
        $numberPartLength = strlen($gridRef) / 2;
        $accuracy = pow(10, log10($gridSize) - $numberPartLength);

        $easting += (intval(substr($gridRef, 0, $numberPartLength)) * $accuracy);
        $northing += (intval(substr($gridRef, $numberPartLength)) * $accuracy);

        return new Cartesian($this->datum, $easting, $northing, $accuracy);
    }

    public function toGridRef(Cartesian $cartesian) : string
    {
        $gridRef = '';
        $gridSize = $this->initialGridSize;

        $easting = $cartesian->getEasting() - $this->eastingOriginOffset;
        $northing = $cartesian->getNorthing() - $this->northingOriginOffset;

        // Determine letter parts
        for($z=0; $z<$this->letterPositions; $z++) {
            $gridSize /= 5;

            list($i, $easting) = $this->getCountAndRemainder($easting, $gridSize);
            list($j, $northing) = $this->getCountAndRemainder($northing, $gridSize);

            $gridRef .= $this->reverseMapping[$i][$j];
        }

        $accuracy = $cartesian->getAccuracy();
        $places = log10($gridSize) - log10($accuracy);

        $gridRef .= $this->padMeasurement($easting, $accuracy, $places);
        $gridRef .= $this->padMeasurement($northing, $accuracy, $places);

        return $gridRef;
    }

    protected function getCountAndRemainder(int $x, int $size) : array {
        $count = floor($x / $size);
        $remainder = $x - ($count * $size);

        return [$count, $remainder];
    }

    protected function padMeasurement(int $measurement, int $accuracy, int $places) : string {
        $measurement = str_replace('.', '', $measurement);
        $measurement = intval($measurement) / $accuracy;
        $measurement = substr(strval($measurement), 0, $places);

        return str_pad($measurement, $places, '0', STR_PAD_LEFT);
    }
}