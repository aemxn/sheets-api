<?php
require_once 'utils.php';
require_once 'config.php';
require __DIR__ . '/vendor/autoload.php';

$googleClient = \Ttskch\GoogleSheetsApi\Factory\GoogleClientFactory::createServiceAccountClient(__DIR__ . SERVICE_JSON_PATH);

$api = \Ttskch\GoogleSheetsApi\Factory\ApiClientFactory::create($googleClient);

$spreadsheetId = SHEET_ID;
$range = 'A1:C4';
$response = $api->getGoogleService()->spreadsheets_values->get($spreadsheetId, $range);
$values = $response->getValues();

echo p($values);