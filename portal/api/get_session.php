<?php
require_once "../config.php";

$mac = $_GET['mac'] ?? '';

if (!$mac) {
    echo json_encode(["remaining_seconds" => 0, "balance" => 0]);
    exit;
}

// Call VPS Time API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, TIME_SERVER_API . "/getSession.php?mac=" . urlencode($mac));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
curl_close($ch);

echo $response;