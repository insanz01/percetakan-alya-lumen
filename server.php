<?php

/**
 * Router script for `php -S`.
 *
 * Without this, PHP's built-in server serves requests for recognized
 * static extensions (.png, .jpg, .css, ...) by checking the docroot
 * directly and returning its own 404 if missing - it never falls back
 * to index.php for those, only for extensionless paths. That breaks
 * /uploads/{path} and /storage/{path}, which serve files that live
 * outside public/ (in storage/app/public) and so never exist under the
 * docroot. Passing this script as the router forces every request
 * through Lumen, which then serves those paths itself.
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

if ($uri !== '/' && file_exists(__DIR__ . '/public' . $uri)) {
    return false;
}

require_once __DIR__ . '/public/index.php';
