<?php
namespace Wibble;

use PDO;
use PDOException;

use Wobble\Grid\Converter;
use Wobble\Grid\GridRef\ChannelIslandsGridRef;
use Wobble\Grid\GridRef\IrishGridRef;
use Wobble\Grid\GridRef\BritishGridRef;

require "./vendor/autoload.php";

$converter = new Converter([
    new IrishGridRef(),
    new BritishGridRef(),
    new ChannelIslandsGridRef(),
]);


try {
    $database = 'DATABASE';
    $username = 'USERNAME';
    $password = 'PASSWORD';

    $dsn = new PDO("mysql:dbname={$database};host=127.0.0.1", $username, $password);
} catch(PDOException $e) {
    die("Connection failed: ".$e->getMessage());
}

$count = 0;
foreach($dsn->query('SELECT * FROM record WHERE datum="CI"') as $row) {

    $gridRef = $row['grid_ref'];
    $count++;

    $eastingNorthing = $converter->toCartesian($gridRef);

    $log = "Failed for $gridRef\n";
    $log .= sprintf("->eastingNorthing = %s,%s,%s [Expected: %s,%s,%s]\n",
        $eastingNorthing->getEasting(),
        $eastingNorthing->getNorthing(),
        $eastingNorthing->getAccuracy(),
        $row['easting'],
        $row['northing'],
        $row['accuracy']
    );

    if (
        $eastingNorthing->getEasting() !== $row['easting'] ||
        $eastingNorthing->getNorthing() !== $row['northing'] ||
        $eastingNorthing->getAccuracy() !== $row['accuracy']
    ) {
        echo $log."\n";
        continue;
    }

    $gridRef2 = $converter->toGridRef($eastingNorthing);
    $log .= "->gridRef = $gridRef2\n";

    if ($gridRef2 !== str_replace(' ', '', $gridRef)) {
        echo $log."\n";
    }
}

echo "Tested against $count records\n";
