<?php

namespace Xobble\Grid\GridRef;

use Xobble\Grid\Exception\UnsupportedRefException;
use Xobble\Grid\GridRef;
use Xobble\Grid\Cartesian;

class ChannelIslandsGridRef implements GridRef
{
    const GRID_SIZE = 100000;

    public function toCartesian(string $gridRef): Cartesian
    {
        if (!$this->supportsGridRef($gridRef)) {
            throw new UnsupportedRefException();
        }

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
        if (!$this->supportsCartesian($cartesian)) {
            throw new UnsupportedRefException();
        }

        $northing = $cartesian->getNorthing();
        $isA = substr($northing, 0, 2) === '55';
        $accuracy = $cartesian->getAccuracy();

        $gridRef = $isA ? 'WA' : 'WV';
        $places = log10(self::GRID_SIZE) - log10($accuracy);

        $processPart = function($part) use($places) {
            $part = str_replace('.', '', $part);
            $part = substr($part, 0, $places);

            return $part;
        };

        $eastingPart = $processPart(substr($cartesian->getEasting(), 1));
        $northingPart = $processPart(substr($northing, 2));

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

    protected function supportsCartesian(Cartesian $cartesian): bool
    {
        $northingPrefix = substr($cartesian->getNorthing(), 0, 2);
        if ($northingPrefix !== '54' && $northingPrefix != '55') {
            return false;
        }

        return $cartesian->getDatum() === $this->getDatum();
    }

    protected function supportsGridRef(string $gridRef): bool
    {
        if (!preg_match('/^W[AV](?P<number>[ \d]*)$/', $gridRef, $matches)) {
            return false;
        }

        $number = str_replace(' ', '', $matches['number']);
        return (strlen($number) % 2 === 0);
    }
}