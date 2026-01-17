<?php

function validate_password_policy(string $password): array {
    $errors = [];

    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Password must contain at least one uppercase letter.";
    }
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Password must contain at least one number.";
    }
    if (!preg_match('/[\W_]/', $password)) {
        $errors[] = "Password must contain at least one special character.";
    }

    return $errors;
}
