<?php
require_once('vendor/autoload.php');

    if (isset($_GET['ajax'])) {
        $key = $_GET['input_key'];
        $state = $_GET['input_state'];
        $city = $_GET['input_city'];
        $limit = $_GET['input_limit'];

        $client = new OutscraperClient('Z29vZ2xlLW9hdXRoMnwxMDQ0NzQwNzkzMjMxNTM2MDIyODB8NDMyZWRiOThkNw');
        $results = $client->google_maps_search([$key . ' ' . $state . ' ' . $city], limit: $limit, language: 'en', region: 'us');

        echo json_encode($results);
    }
