<?php

namespace Xobble\Grid;

use Xobble\Grid\Exception\GridRefException;

class Cartesian
{
    /** @var float */
    protected $easting;

    /** @var float */
    protected $northing;

    /** @var float */
    protected $accuracy;

    /** @var int */
    protected $datum;

    /**
     * Cartesian constructor.
     * @param int $datum
     * @param float $easting
     * @param float $northing
     * @param float $accuracy
     *
     * @throws GridRefException
     */
    public function __construct(int $datum, float $easting, float $northing, float $accuracy)
    {
        $this->checkAccuracyIsPowerOfTenWithIntegerExponent($accuracy);
        $this->checkAccuracyAgrees($accuracy, $easting);
        $this->checkAccuracyAgrees($accuracy, $northing);

        $this->datum = $datum;
        $this->easting = $easting;
        $this->northing = $northing;
        $this->accuracy = $accuracy;
    }

    /**
     * @param float $accuracy
     * @param float $distance
     * @throws GridRefException
     */
    protected function checkAccuracyAgrees(float $accuracy, float $distance) : void {
        $multiplier = $accuracy >= 1 ? 1 :  pow(10, abs(log10($accuracy)));
        $distanceInAccuracyUnits = intval(floor(($distance * $multiplier) / $accuracy));
        $expectedDistance = ($distanceInAccuracyUnits / $multiplier) * $accuracy;

        if ($expectedDistance !== $distance) {
            throw new GridRefException('Accuracy + easting / northing mismatch');
        }
    }

    /**
     * @param float $accuracy
     * @throws GridRefException
     */
    protected function checkAccuracyIsPowerOfTenWithIntegerExponent(float $accuracy)
    {
        $expectedAccuracy = floatval(pow(10, intval(log10($accuracy))));

        if ($expectedAccuracy !== $accuracy) {
            throw new GridRefException('Accuracy must be a power of 10 with an integer exponent (e.g. 1, 10, 100, 1000...)');
        }
    }

    public function getEasting(): float
    {
        return $this->easting;
    }

    public function getNorthing(): float
    {
        return $this->northing;
    }

    public function getDatum(): int
    {
        return $this->datum;
    }

    public function getAccuracy(): float
    {
        return $this->accuracy;
    }

    public function __toString(): string
    {
        return "EPSG:$this->datum($this->easting, $this->northing) [{$this->accuracy}m]";
    }
}