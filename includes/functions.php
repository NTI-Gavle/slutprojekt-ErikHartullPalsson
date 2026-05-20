<?php

function requireLogin() {

    if (!isset($_SESSION['user_id'])) {

        header("Location: /login.php");
        exit;
    }
}

function isAdmin() {

    return isset($_SESSION['role']) &&
           $_SESSION['role'] === 'admin';
}

function rateLimit($key, $seconds) {

    if (!isset($_SESSION['rate_limit'])) {

        $_SESSION['rate_limit'] = [];
    }

    $now = time();

    if (isset($_SESSION['rate_limit'][$key])) {

        $lastRequest =
            $_SESSION['rate_limit'][$key];

        if (($now - $lastRequest) < $seconds) {

            return false;
        }
    }

    $_SESSION['rate_limit'][$key] = $now;

    return true;
}