<?php
/**
 * --------------------------------------------------------------
 * File: mits_imageslider_security.php
 * Author: Hetfield
 * Copyright: (c) 2026 - MerZ IT-SerVice
 * Web: https://www.merz-it-service.de
 * --------------------------------------------------------------
 */

function mits_imageslider_admin_token(): string
{
    if (empty($_SESSION['MITS_IMAGESLIDER_ADMIN_CSRF'])) {
        $_SESSION['MITS_IMAGESLIDER_ADMIN_CSRF'] = bin2hex(random_bytes(24));
    }
    return (string)$_SESSION['MITS_IMAGESLIDER_ADMIN_CSRF'];
}

function mits_imageslider_admin_csrf_fields(): string
{
    $fields = '<input type="hidden" name="mits_imageslider_admin_csrf" value="' . htmlspecialchars(mits_imageslider_admin_token(), ENT_QUOTES, 'UTF-8') . '">';
    if (defined('CSRF_TOKEN_SYSTEM') && CSRF_TOKEN_SYSTEM === 'true' && isset($_SESSION['CSRFName'], $_SESSION['CSRFToken'])) {
        $fields .= '<input type="hidden" name="' . htmlspecialchars((string)$_SESSION['CSRFName'], ENT_QUOTES, 'UTF-8') . '" value="' . htmlspecialchars((string)$_SESSION['CSRFToken'], ENT_QUOTES, 'UTF-8') . '">';
    }
    return $fields;
}

function mits_imageslider_admin_check_csrf(): bool
{
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST' || !isset($_POST['mits_imageslider_admin_csrf']) || !is_string($_POST['mits_imageslider_admin_csrf'])) {
        return false;
    }
    if (!hash_equals(mits_imageslider_admin_token(), $_POST['mits_imageslider_admin_csrf'])) {
        return false;
    }
    if (defined('CSRF_TOKEN_SYSTEM') && CSRF_TOKEN_SYSTEM === 'true') {
        if (!isset($_SESSION['CSRFName'], $_SESSION['CSRFToken'])) {
            return false;
        }
        $name = (string)$_SESSION['CSRFName'];
        return $name !== '' && isset($_POST[$name]) && is_string($_POST[$name]) && hash_equals((string)$_SESSION['CSRFToken'], $_POST[$name]);
    }
    return true;
}
