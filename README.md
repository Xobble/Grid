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

    $converter = new Converter([
        new IrishGridRef(),
        new BritishGridRef(),
        new ChannelIslandsGridRef(),
    ]);
    
    $gridRef1 = 'SX466827';
    $toCart1 = $converter->toCartesian($gridRef1);
    $toGrid1 = $converter->toGridRef($toCart1);
    echo "$gridRef1 = $toCart1\n";

    $cartesian2 = new Cartesian('EPSG:27700', '651409', '313177', '1');
    $toGrid2 = $converter->toGridRef($cartesian2);
    echo "$cartesian2 = $toGrid2\n";

    // Output:
    //   SX466827 = EPSG:27700(246600, 82700) [100m]
    //   EPSG:27700(651409, 313177) [1m] = TG5140913177
```

## Supported Datums / Grids

* EPSG:27700 - [British National Grid](https://en.wikipedia.org/wiki/Ordnance_Survey_National_Grid)
* EPSG:29902 - [Irish Grid](https://en.wikipedia.org/wiki/Irish_grid_reference_system)
* EPSG:32630 - [Channel Islands Grid](https://www.bwars.com/content/channel-islands-how-give-location-reference)

## GridRef interface

```php
interface GridRef
{
    public function supportsGridRef(string $gridRef) : bool;
    public function supportsCartesian(Cartesian $cartesian) : bool;

    public function getDatum() : string;
    public function getGridReferenceName() : string;

    public function toCartesian(string $gridRef) : Cartesian;
    public function toGridRef(Cartesian $cartesian) : string;
}
```

```php
    $grid = new BritishGridRef();
    
    echo $grid->getGridReferenceName();                   // British National Grid
    echo $grid->getDatum();                               // EPSG:27700
    echo $grid->supportsGridRef('SR123456') ? "Y" : "N";  // Y
    echo $grid->supportsGridRef('HL123456') ? "Y" : "N";  // N
    
    $cart1 = new Cartesian('EPSG:27700', '651409', '313177', '1');
    $cart2 = new Cartesian('EPSG:29902', '651409', '313177', '1');
    
    echo $grid->supportsCartesian($cart1) ? "Y" : "N";    // Y
    echo $grid->supportsCartesian($cart2) ? "Y" : "N";    // N
    
    $gridRef = $grid->toGridRef($cart1);
    echo $gridRef;                                        // TG5140913177
    echo $grid->toCartesian($gridRef);                    // EPSG:27700(651409, 313177) [1m]
```

## Cartesian class

```
    $cart = new Cartesian('EPSG:27700', '651409', '313177', '1');
    
    echo $cart->getDatum();    // EPSG:27700
    echo $cart->getEasting();  // 651409
    echo $cart->getNorthing(); // 313177
    echo $cart->getAccuracy(); // 1
    echo $cart;                // EPSG:27700(651409, 313177) [1m]
```
