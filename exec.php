<?php

require __DIR__."/vendor/autoload.php";

use Clue\React\Buzz\Browser;
use Grab\Scraper;
use React\Filesystem\Filesystem;

$config = require __DIR__."/config.php";


$loop = React\EventLoop\Factory::create();

$scraper = new Scraper(
    new Browser($loop), Filesystem::create($loop), __DIR__.'/images'
);

$reader = \League\Csv\Reader::createFromPath($config['CSV_SOURCE_PATH']);
$reader->setHeaderOffset(0);

foreach (yield_rows($reader->getRecords()) as $row) {
    foreach (explode("\n", $row[$config['IMAGE_COLUMN_HEADER']]) as $url) {
        $scraper->addUrl($url);
    };
}

$scraper->scrape();
$loop->run();

function yield_rows($records)
{
    yield from $records;
}
