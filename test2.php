<?php
namespace Wibble;

use PDO;
use PDOException;

use Wobble\GridRef\Converter;
use Wobble\GridRef\Converter\IrishGridRef;
use Wobble\GridRef\Converter\BritishGridRef;
use Wobble\GridRef\Converter\ChannelIslandsWAGridRef;
use Wobble\GridRef\Converter\ChannelIslandsWVGridRef;

require "./vendor/autoload.php";

$converter = new Converter([
    new IrishGridRef(),
    new BritishGridRef(),
    new ChannelIslandsWAGridRef(),
    new ChannelIslandsWVGridRef()
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
foreach($dsn->query('SELECT * FROM record') as $row) {

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
