# Grid
A small library to assist with converting between British National Grid, Irish Grid and Channel Islands Grid references and their cartesian representations

## Installation

```bash
$ composer require xobble/grid
```

## Usage

```php
use Xobble\Grid\Cartesian;
use Xobble\Grid\Converter;
use Xobble\Grid\GridRef\ChannelIslandsGridRef;
use Xobble\Grid\GridRef\IrishGridRef;
use Xobble\Grid\GridRef\BritishGridRef;

// 1. Instantiate the converter with the grid types you want to support

$converter = new Converter([
    new IrishGridRef(),
    new BritishGridRef(),
    new ChannelIslandsGridRef(),
]);

// 2. Start converting

// British National Grid:
$cart1 = $converter->toCartesian('SX466827');        // EPSG:27700(246600, 82700) [100m]
$converter->toGridRef($cart1);                       // SX466827

$cart2 = new Cartesian('EPSG:27700', '651409', '313177', '1');
$grid2 = $converter->toGridRef($cart2);              // TG5140913177
$converter->toCartesian($grid2);                     // EPSG:27700(651409, 313177) [1m]

// Irish Grid:
$converter->toCartesian('X622997');                  // EPSG:29902(262200, 99700) [100m]
 
// Channel Islands Grid:
$converter->toCartesian('WV305754');                 // EPSG:32630(530500, 5475400) [100m]

// An UnsupportedRefException will be triggered for unsupported grid references or cartesian coordinates:

$converter->toCartesian('AB22997');                  // UnsupportedRefException

// A GridRefException will be triggered if trying to convert badly constructed Cartesians:
//
// GridRefException: Accuracy + easting / northing mismatch
// (States 100m accuracy, but easting/northing have meter and tens accuracy digits): 
$badCart1 = new Cartesian('27700', '651409', '313122', '100');
$converter->toGridRef($badCart1);                    

// GridRefException: Accuracy must be a power of 10 with an integer exponent (e.g. 1, 10, 100, 1000...)
$badCart2 = new Cartesian('27700', '651400', '313100', '200');
$converter->toGridRef($badCart2) ;                  
```

## Supported Datums / Grids

* EPSG:27700 - class BritishGridRef - [British National Grid](https://en.wikipedia.org/wiki/Ordnance_Survey_National_Grid)
* EPSG:29902 - class IrishGridRef - [Irish Grid](https://en.wikipedia.org/wiki/Irish_grid_reference_system)
* EPSG:32630 - class - ChannelIslandsGridRef - [Channel Islands Grid](https://www.bwars.com/content/channel-islands-how-give-location-reference)

## GridRef interface

Other grid reference systems can be supporting by creating classes that implement the following interface. 

```php
use Xobble\Grid\Cartesian;

interface GridRef
{
    public function getDatum() : string;
    public function getGridReferenceName() : string;

    public function toCartesian(string $gridRef) : Cartesian;
    public function toGridRef(Cartesian $cartesian) : string;
}
```

```php
    use Xobble\Grid\Cartesian;
    use Xobble\Grid\GridRef\BritishGridRef;
    
    $grid = new BritishGridRef();
    
    $grid->getGridReferenceName();                   // British National Grid
    $grid->getDatum();                               // EPSG:27700
    $grid->toCartesian('SR123456');                  // Returns a Cartesian - "EPSG:27700(112300, 145600) [100m]"
    $grid->toCartesian('HL123456');                  // throws UnsupportedRefException
    
    $cart1 = new Cartesian('EPSG:27700', '651409', '313177', '1');
    $cart2 = new Cartesian('EPSG:29902', '651409', '313177', '1');
    
    $ref1 = $grid->toGridRef($cart1);                // Returns a string - "TG5140913177"
    $grid->toGridRef($cart2);                        // throws UnsupportedRefException
    
    $grid->toCartesian($ref1);                       // EPSG:27700(651409, 313177) [1m]
```

## Cartesian class

```php
    use Xobble\Grid\Cartesian;

    $cart = new Cartesian('EPSG:27700', '651409', '313177', '1');
    
    echo $cart->getDatum();    // EPSG:27700
    echo $cart->getEasting();  // 651409
    echo $cart->getNorthing(); // 313177
    echo $cart->getAccuracy(); // 1
    echo $cart;                // EPSG:27700(651409, 313177) [1m]
```
