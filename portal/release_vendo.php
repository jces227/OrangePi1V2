<?php

$lock_file = "/tmp/vendo.lock";

if (file_exists($lock_file)) {
    unlink($lock_file);
}

echo "RELEASED";