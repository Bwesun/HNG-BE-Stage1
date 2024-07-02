
<br>
<?php
  header('Content-Type: application/json');

  $visitorName = $_GET['visitor_name'];
  $clientIp = $_SERVER['REMOTE_ADDR'];
  $location = 'New York'; // Hardcoded for simplicity, but you could use an IP geolocation API
  $temperature = 11; // Hardcoded for simplicity, but you could fetch real data from an API

  $response = array(
    'client_ip' => $clientIp,
    'location' => $location,
    'greeting' => "Hello, $visitorName!, the temperature is $temperature degrees Celcius in $location"
  );

  echo json_encode($response);
?>
