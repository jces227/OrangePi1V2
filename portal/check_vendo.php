<?php

$lock_file = "/tmp/vendo.lock";
$timeout = 60; // seconds
$current_time = time();

if (file_exists($lock_file)) {

    $data = json_decode(file_get_contents($lock_file), true);

    if ($data && isset($data['time'])) {

        $lock_age = $current_time - $data['time'];

        // Auto release if timeout reached
        if ($lock_age > $timeout) {
            unlink($lock_file);
        } else {
            echo "BUSY";
            exit;
        }
    }
}

// create new lock
$lock_data = [
    "mac" => $_GET['mac'] ?? "unknown",
    "time" => $current_time
];

file_put_contents($lock_file, json_encode($lock_data));

echo "OK";