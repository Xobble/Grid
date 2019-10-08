<?php

namespace Xobble\Grid;

use Xobble\Grid\Exception\GridRefException;

class Cartesian
{
    /** @var string */
    protected $easting;

    /** @var string */
    protected $northing;

    /** @var string */
    protected $accuracy;

    /** @var string */
    protected $datum;

    /**
     * Cartesian constructor.
     * @param string $datum
     * @param string $easting
     * @param string $northing
     * @param string $accuracy
     *
     * @throws GridRefException
     */
    public function __construct(string $datum, string $easting, string $northing, string $accuracy)
    {
        $this->checkAccuracyAgrees($accuracy, $easting);
        $this->checkAccuracyAgrees($accuracy, $northing);

        $this->datum = $datum;
        $this->easting = $easting;
        $this->northing = $northing;
        $this->accuracy = $accuracy;
    }

    /**
     * @param $accuracy
     * @param $distance
     *
     * @throws GridRefException
     */
    protected function checkAccuracyAgrees($accuracy, $distance) : void {
        $multiplier = $accuracy >= 1 ? 1 :  pow(10, abs(log10($accuracy)));
        $distanceInAccuracyUnits = intval(floor(($distance * $multiplier) / $accuracy));
        $expectedDistance = strval(($distanceInAccuracyUnits / $multiplier) * $accuracy);

        if ($expectedDistance !== $distance) {
            throw new GridRefException('Accuracy + easting / northing mismatch');
        }
    }

    public function getEasting(): string
    {
        return $this->easting;
    }

    public function getNorthing(): string
    {
        return $this->northing;
    }

    public function getDatum(): string
    {
        return $this->datum;
    }

    public function getAccuracy(): string
    {
        return $this->accuracy;
    }

    public function __toString(): string
    {
        return "$this->datum($this->easting, $this->northing) [{$this->accuracy}m]";
    }
}