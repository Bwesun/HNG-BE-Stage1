<?php
  $api_key = 'e9e8cb86930305cbf7723efdae0bc76b';
  $client_ip = $_SERVER['REMOTE_ADDR'];
  $geo_ip_api = 'freegeoip.io/json/'. $client_ip;
  $weather_api = '';

  // Get client's location using GeoIP API
  $geo_ip_response = json_decode(file_get_contents($geo_ip_api), true);
  $city = $geo_ip_response['city'];
  $country_code = $geo_ip_response['country_code'];

  // Get weather data using OpenWeatherMap API
  $weather_params = array(
    'q' => "$city,$country_code",
    'units' => 'metric',
    'appid' => $api_key
  );
  $weather_response = json_decode(file_get_contents($weather_api, false, stream_context_create(array(
    'http' => array(
      'method' => 'GET',
      'header' => 'Accept: application/json',
      'content' => http_build_query($weather_params)
    )
  ))), true);

  $temperature = $weather_response['main']['temp'];

  echo "Client's city: $city";
  echo "<br>Client's temperature: $temperature°C";
?>
