<?php
require_once 'config.php';

try {
    $client = new \GuzzleHttp\Client(['base_uri' => 'https://api.zoom.us']);

    $db = new DB();
    $arr_token = $db->get_access_token();
    $accessToken = $arr_token->access_token;

    $response = $client->request('GET', '/v2/users/me/meetings', [
        "headers" => [
            "Authorization" => "Bearer $accessToken"
        ]
    ]);

    $data = json_decode($response->getBody());

    if (!empty($data)) {
        foreach ($data->meetings as $d) {
            $topic = $d->topic;
            $join_url = $d->join_url;
            echo "<h3>Topic: $topic</h3>";
            echo "Join URL: $join_url";
        }
    }
}
catch(Exception $e) {
    echo $e->getMessage();
}