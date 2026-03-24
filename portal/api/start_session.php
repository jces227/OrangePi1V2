<?php

require_once "omada.php";

$clientMac = $_POST['clientMac'];
$apMac = $_POST['apMac'];
$ssidName = $_POST['ssidName'];
$radioId = $_POST['radioId'];
$site = $_POST['site'];
$minutes = intval($_POST['minutes']);

$expire = $minutes * 60 * 1000 * 1000; // microseconds

OmadaAPI::login();

$result = OmadaAPI::authorize(
    $clientMac,
    $apMac,
    $ssidName,
    $radioId,
    $site,
    $expire
);
header('Content-Type: application/json');

echo $result;