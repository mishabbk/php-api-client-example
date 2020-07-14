<?php
require_once __DIR__ . '/vendor/autoload.php';

use ApiClientExample\HttpClientCreator;
use ApiClientExample\Methods;

$apiSecretKey = '***';
$apiBaseUri = 'https://api.url';

// Create api client
$api = new Methods(
  HttpClientCreator::create($apiBaseUri, $apiSecretKey)
);

// Get balance
echo $api->balance('UAH')->amount;

