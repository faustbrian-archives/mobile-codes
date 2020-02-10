<?php

declare(strict_types=1);

/*
 * This file is part of Mobile Codes.
 *
 * (c) KodeKeep <hello@kodekeep.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../vendor/autoload.php';

$crawler = (new Goutte\Client())->request('GET', 'http://mcc-mnc.com/');

$datasets = [];
$crawler->filter('table > tbody > tr')->each(function ($node) use (&$datasets) {
    $datasets[] = [
        'mcc'          => $node->filter('td:nth-child(1)')->text(),
        'mnc'          => $node->filter('td:nth-child(2)')->text(),
        'iso'          => $node->filter('td:nth-child(3)')->text(),
        'country_name' => $node->filter('td:nth-child(4)')->text(),
        'country_code' => $node->filter('td:nth-child(5)')->text(),
        'network'      => $node->filter('td:nth-child(6)')->text(),
    ];
});

file_put_contents(__DIR__.'/../dist/unsorted/data.json', json_encode($datasets));

$records = [];
foreach ($datasets as $dataset) {
    $records[$dataset['country_name']][] = $dataset;
}

file_put_contents(__DIR__.'/../dist/sorted-by-country/data.json', json_encode($records));
