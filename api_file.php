<?php
    header('Content-Type: application/json');
    
    // Get Client Name
    $visitor_name=isset($_GET['visitor_name'])? 
    htmlspecialchars($_GET['visitor_name']):'Mark';
    
    // to get client ip
    function getClientIp() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED']) && !empty($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } elseif (isset($_SERVER['HTTP_FORWARDED_FOR']) && !empty($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_FORWARDED']) && !empty($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } elseif (isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = 'UNKNOWN';
        }
         
        // In the event there are multiple IPs, take the first one
        $ipaddress = explode(',', $ipaddress)[0];
        return trim($ipaddress);
    }
    
    $client_ip = getClientIp();

    // to get location of the requester
    $ipinfo_token = 'ebf4873b57ca37'; //my openip api key
    $ipinfo_url = "http://ipinfo.io/{$client_ip}/json?token={$ipinfo_token}";

    $location_data = file_get_contents($ipinfo_url);
    $location_info = json_decode($location_data, true);

    $latlong = isset($location_info['loc']) ? explode(',', $location_info['loc']) : [0, 0];
    $latitude = $latlong[0];
    $longitude = $latlong[1];


    // Get Cleint Weather based on his location
    $weather_api_key = "e9e8cb86930305cbf7723efdae0bc76b"; //my openweathermap api key
    $weather_url = "http://api.openweathermap.org/data/2.5/weather?lat={$latitude}&lon={$longitude}&units=metric&appid={$weather_api_key}";

    $weather_data = file_get_contents($weather_url);
    $weather_info = json_decode($weather_data, true);

    $temperature = isset($weather_info['main']['temp']) ? $weather_info['main']['temp'] : 'N/A';
    $weather_description = isset($weather_info['weather'][0]['description']) ? $weather_info['weather'][0]['description'] : 'N/A';
    $city = isset($weather_info['name']) ? $weather_info['name'] : 'Unknown';

  
// Return Json Response
    $response=array(
      "client_ip" => $client_ip,
        "location" => $city,
        "weather" => $weather_description,
        "greeting"=>"Hello, $visitor_name!, the temperature is $temperature degrees celcius in $city",
    );

    echo json_encode($response);
?>