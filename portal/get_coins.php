<?php
$coin_file = __DIR__ . "/coins.txt";

if (file_exists($coin_file)) {
    $content = trim(file_get_contents($coin_file));
    echo intval($content ?: 0);
} else {
    echo 0;
}
?>