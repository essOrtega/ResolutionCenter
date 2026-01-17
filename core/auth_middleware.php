<?php

require_once __DIR__ . '/logger.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Session timeout (15 minutes)
$timeout = 15 * 60;

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
    if (isset($_SESSION['user_id'])) { 
        log_event("Session timeout for user {$_SESSION['user_id']}"); 
    }

    session_unset();
    session_destroy();
    header("Location: ../login.php?timeout=1");
    exit;
}

$_SESSION['last_activity'] = time();

function enforce_https() {
    // If HTTPS is not enabled
    if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {

        // Allow HTTP on localhost for development
        if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') {
            return;
        }

        // Redirect to HTTPS version
        $httpsUrl = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header("Location: $httpsUrl");
        exit;
    }
}

/**
 * Require user to be logged in.
 */
function require_auth() {
    if (empty($_SESSION['user_id'])) {
        header("Location: ../login.php?auth=required");
        exit;
    }
}

// Main authorization middleware 
function require_role($roles = []) { 
    enforce_https(); 
    
    // Check login 
    if (!isset($_SESSION['user_id'])) { 
        header("Location: ../login.php"); 
        exit; 
    } 
    
    // Check role 
    if (!in_array($_SESSION['role'], $roles)) { 
        log_event("Unauthorized access attempt by user {$_SESSION['user_id']} with role {$_SESSION['role']}");
        header("Location: ../unauthorized.php"); 
        exit; 
    } 
}
