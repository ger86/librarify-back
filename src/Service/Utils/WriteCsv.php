<?php

namespace App\Service\Utils;

use League\Csv\Writer;

class WriteCsv implements WriteCsvInterface
{
    public function write(iterable $elements, string $path, array $headers = null, string $mode = 'w+'): void
    {
        $csv = Writer::createFromPath($path, $mode);
        if ($headers !== null) {
            $csv->insertOne($headers);
        }
        $csv->insertAll($elements);
    }
}
