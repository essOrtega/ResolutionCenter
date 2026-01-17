<?php
// Secure session settings
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0); // set to 1 when HTTPS is enabled
ini_set('session.cookie_samesite', 'Strict');

session_start();
