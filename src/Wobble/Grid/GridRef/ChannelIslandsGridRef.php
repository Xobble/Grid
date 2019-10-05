<?php

namespace Wobble\Grid\GridRef;

use Wobble\Grid\GridRef;
use Wobble\Grid\Cartesian;

class ChannelIslandsGridRef implements GridRef
{
    const GRID_SIZE = 100000;

    public function supportsGridRef(string $gridRef): bool
    {
        if (!preg_match('/^W[AV](?P<number>[ \d]*)$/', $gridRef, $matches)) {
            return false;
        }

        $number = str_replace(' ', '', $matches['number']);
        return (strlen($number) % 2 === 0);
    }

    public function supportsCartesian(Cartesian $cartesian): bool
    {
        $northingPrefix = substr($cartesian->getNorthing(), 0, 2);
        if ($northingPrefix !== '54' && $northingPrefix != '55') {
            return false;
        }

        return $cartesian->getDatum() === $this->getDatum();
    }

    public function toCartesian(string $gridRef): Cartesian
    {
        // As per...
        // https://www.bwars.com/content/channel-islands-how-give-location-reference

        $numberPart = substr($gridRef, 2);
        $numberPartLength = strlen($numberPart) / 2;
        $accuracy = pow(10, log10(self::GRID_SIZE) - $numberPartLength);

        $northingPrefix = substr($gridRef, 0, 2) === 'WA' ? '55' : '54';

        $easting = intval('5' . substr($numberPart, 0, $numberPartLength)) * $accuracy;
        $northing = intval($northingPrefix . substr($numberPart, $numberPartLength)) * $accuracy;

        return new Cartesian($this->getDatum(), $easting, $northing, $accuracy);
    }

    public function toGridRef(Cartesian $cartesian): string
    {
        $northing = $cartesian->getNorthing();
        $isA = substr($northing, 0, 2) === '55';
        $accuracy = $cartesian->getAccuracy();

        $gridRef = $isA ? 'WA' : 'WV';
        $places = log10(self::GRID_SIZE) - log10($accuracy);

        $eastingPart = substr(substr($cartesian->getEasting(), 1), 0, $places);
        $northingPart = substr(substr($northing, 2), 0, $places);

        return $gridRef.$eastingPart.$northingPart;
    }

    public function getGridReferenceName(): string
    {
        return 'Channel Islands Grid';
    }

    public function getDatum(): string
    {
        return 'EPSG:32630';
    }
}