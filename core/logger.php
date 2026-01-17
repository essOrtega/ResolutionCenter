<?php

function log_event($message) {
    $logFile = __DIR__ . '/../logs/security.log';

    // Ensure logs directory exists
    if (!is_dir(dirname($logFile))) {
        mkdir(dirname($logFile), 0777, true);
    }

    $timestamp = date("Y-m-d H:i:s");
    $entry = "[$timestamp] $message" . PHP_EOL;

    file_put_contents($logFile, $entry, FILE_APPEND);
}
