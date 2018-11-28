<?php
require_once 'utils.php';
require_once 'config.php';
require_once 'const.php';
require __DIR__ . '/vendor/autoload.php';

$googleClient = \Ttskch\GoogleSheetsApi\Factory\GoogleClientFactory::createServiceAccountClient(__DIR__ . SERVICE_JSON_PATH);

$api = \Ttskch\GoogleSheetsApi\Factory\ApiClientFactory::create($googleClient);

$spreadsheetId = SHEET_ID;
$range = RANGE;
$response = $api->getGoogleService()->spreadsheets_values->get($spreadsheetId, $range);
$values = $response->getValues();


// FOR DEBUG SHOW HEADER
// $header_val = $values;
// $header_col = 0;

// // get header string row 1
// foreach($header_val[0] as $value) { // header, always row 1, DON'T CHANGE
//     // echo j($value, 0);
//     $header_col++;
// }

// get real data starting from row 2
array_shift($values); // remove row 1 (header)
$total_row = count($values);

for ($row = 0; $row < $total_row; $row++) {

    $dates[] = $values[$row][DATE]; // store dates in an array for duplication detection

    $dayEvents[] = array(
        'id' => $values[$row][ID],
        'date' => $values[$row][DATE],
        'name' => $values[$row][NAME],
        'package' => $values[$row][PACKAGE],
        'person' => $values[$row][PERSON],
        'start_time' => $values[$row][START_TIME],
        'end_time' => $values[$row][END_TIME],
        'event_id' => '1'
    );
}

$datesData = array('number' => '0',
    'date_id' => '1',
    'dayEvents' => $dayEvents);

$res = array_fill_keys($dates, $datesData); // merge array keys

// cleanup data array
foreach($res as $key => $val) {
    $i = 0;
    
    foreach($val[KEY_DAY_EVENTS] as $dayEvent) {
        if ($dayEvent[KEY_DATE] != $key) {
            // echo j($i, 0);
            // echo j($dayEvent[KEY_DATE], 0);
            unset($res[$key][KEY_DAY_EVENTS][$i]); // remove dates that is not same as key
        }
        $res[$key][KEY_NUMBER] = count($res[$key][KEY_DAY_EVENTS]);
        $i++;
    }
}

echo response($res);