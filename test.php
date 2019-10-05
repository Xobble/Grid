<?php

namespace Wibble;

use Wobble\Grid\Converter;
use Wobble\Grid\GridRef\BritishGridRef;
use Wobble\Grid\GridRef\ChannelIslandsWAGridRef;
use Wobble\Grid\GridRef\ChannelIslandsWVGridRef;
use Wobble\Grid\GridRef\IrishGridRef;
use Wobble\Grid\Cartesian;

require "./vendor/autoload.php";

$converter = new Converter([
    new IrishGridRef(),
    new BritishGridRef(),
    new ChannelIslandsWAGridRef(),
    new ChannelIslandsWVGridRef()
]);

function test(Converter $converter, $gridRef) {
    $eastingNorthing = $converter->toCartesian($gridRef);
    echo "{$gridRef} = {$eastingNorthing}\n";
    echo $converter->toGridRef($eastingNorthing)."\n\n";
}

function testEastingNorthing(Converter $converter, Cartesian $eastingNorthing) {
    $gridRef = $converter->toGridRef($eastingNorthing);
    echo "{$eastingNorthing} = {$gridRef}\n";
    echo $converter->toCartesian($gridRef)."\n\n";
}

echo "grid -> eastingNorthing -> grid\n";
echo "-------------------------------\n";

test($converter, 'O1590434671');
test($converter, 'SX4668270419');
test($converter, 'HP 62117 16164');
echo "\n";

//echo "eastingNorthing -> grid -> eastingNorthing\n";
//echo "------------------------------------------\n";
//
//
//testEastingNorthing($converter, new EastingNorthing('GB', '111', '222', '100'));
//testEastingNorthing($converter, new EastingNorthing('GB', '1111', '2222', '100'));
//testEastingNorthing($converter, new EastingNorthing('GB', '11111.1', '22222.2', '100'));
//testEastingNorthing($converter, new EastingNorthing('GB', '11111.1', '22222.2', '0.1'));
