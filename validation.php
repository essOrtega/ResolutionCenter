<?php

function validateRequired($value) {
    return isset($value) && trim($value) !== '';
}

function validateLength($value, $min = null, $max = null) {
    $len = strlen(trim($value));
    if ($min !== null && $len < $min) return false;
    if ($max !== null && $len > $max) return false;
    return true;
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePhone($phone) {
    return preg_match('/^[0-9\-\+\(\)\s]{7,20}$/', $phone);
}

function validatePasswordComplexity($password) {
    if (strlen($password) < 8) return false;
    if (!preg_match('/[A-Z]/', $password)) return false;
    if (!preg_match('/[a-z]/', $password)) return false;
    if (!preg_match('/[0-9]/', $password)) return false;
    return true;
}
?>
