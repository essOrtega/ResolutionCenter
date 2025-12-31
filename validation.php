<?php

function validate_required($value) {
    return isset($value) && trim($value) !== '';
}

function validate_length($value, $min = null, $max = null) {
    $len = strlen(trim($value));
    if ($min !== null && $len < $min) return false;
    if ($max !== null && $len > $max) return false;
    return true;
}

function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validate_phone($phone) {
    return preg_match('/^[0-9\-\+\(\)\s]{7,20}$/', $phone);
}

function validate_password_complexity($password) {
    if (strlen($password) < 8) return false;
    if (!preg_match('/[A-Z]/', $password)) return false;
    if (!preg_match('/[a-z]/', $password)) return false;
    if (!preg_match('/[0-9]/', $password)) return false;
    return true;
}
?>
