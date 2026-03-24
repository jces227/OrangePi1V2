<?php

$config_path = "/var/www/html/admin/config.json";

if (!file_exists($config_path)) {
    die("Admin configuration not found.");
}

$config_json = file_get_contents($config_path);
$config = json_decode($config_json, true);

if (!$config) {
    die("Invalid admin configuration.");
}

// Define dynamic constants
define("WIFI_NAME", $config['wifi_name'] ?? 'WiFi');
define("LOGO_FILE", "/admin/uploads/" . ($config['logo_file'] ?? ''));
define("CONTROLLER_IP", $config['controller_ip'] ?? '');
define("CONTROLLER_PORT", $config['port'] ?? '');
define("CONTROLLER_ID", $config['controller_id'] ?? '');
define("OPERATOR_USERNAME", $config['operator_username'] ?? '');
define("OPERATOR_PASSWORD", $config['operator_password'] ?? '');

define("COOKIE_FILE_PATH", __DIR__ . "/cookie.txt");
define("TOKEN_FILE_PATH", __DIR__ . "/token.txt");

// Load packages
$PACKAGES = $config['packages'] ?? [];

// Optional: make it a constant too
define("PACKAGES", $PACKAGES);

define("SITE_NAME", $config['site_name'] ?? '');  // your Omada site name
define("RADIO_ID_24G", "0");
define("RADIO_ID_5G", "1");
define("SSID_NAME", $config['ssid_name'] ?? '');
define("AP_MAC", $config['ap_mac'] ?? '');  // MAC of AP (you can get it from Omada controller)