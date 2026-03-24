<?php
require_once "../config_loader.php";

function checkController() {

    $url = "https://" . CONTROLLER_IP . ":" . CONTROLLER_PORT;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return ($httpCode == 200) ? "Online" : "Offline";
}

function checkTimeServer() {

    // You can later replace with real VPS API health endpoint
    return "Online";
}

echo json_encode([
    "controller" => checkController(),
    "time_server" => checkTimeServer()
]);