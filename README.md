# Grid
A small library to assist with converting between British National Grid, Irish Grid and Channel Islands Grid references and their cartesian representations

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
