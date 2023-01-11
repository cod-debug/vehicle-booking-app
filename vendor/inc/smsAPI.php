<?php
require_once('../vendor/autoload.php');

$client = new \GuzzleHttp\Client();

$response = $client->request('POST', 'https://api.movider.co/v1/balance', [
  'form_params' => [
    'api_key' => '2IIMk1Hl3VR1PmjuQ54zZd0DUDu',
    'api_secret' => '5BjZ0QpidrENanXKbeS2T8fGUuSF6mWntoAYZS4H'
  ],
  'headers' => [
    'accept' => 'application/json',
    'content-type' => 'application/x-www-form-urlencoded',
  ],
]);

echo $response->getBody();