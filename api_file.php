<?php

function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function get_location($ip) {
    $json = file_get_contents("https://ip-api.com/json/{$ip}");
    $details = json_decode($json);
    return $details->city ?? 'Unknown';
}

function get_temperature($city) {
    $apiKey = 'e9e8cb86930305cbf7723efdae0bc76b';
    $city = urlencode($city);
    $url = "https://api.openweathermap.org/data/2.5/weather?q={$city}&units=metric&appid={$apiKey}";
    $json = file_get_contents($url);
    $data = json_decode($json);
    return $data->main->temp ?? 'Unknown';
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['visitor_name'])) {
    $visitor_name = htmlspecialchars($_GET['visitor_name']);
    $client_ip = get_client_ip();
    $location = get_location($client_ip);
    $temperature = get_temperature($location);

    $response = [
        'client_ip' => $client_ip,
        'location' => $location,
        'greeting' => "Hello, $visitor_name! The temperature is $temperature degrees Celsius in $location"
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    echo "Use the endpoint /api/hello?visitor_name=Mark";
}

?>
