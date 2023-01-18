<?php
require_once 'config.php';

$client = new GuzzleHttpClient(['base_uri' => 'https://api.zoom.us']);

$db = new DB();
$arr_token = $db->get_access_token();
$accessToken = $arr_token->access_token;

$response = $client->request('DELETE', '/v2/meetings/{meeting_id}', [
    "headers" => [
        "Authorization" => "Bearer $accessToken"
    ]
]);

if (204 == $response->getStatusCode()) {
    echo "Meeting is deleted.";
}