<?php
/*
 * Copyright (c) 2024 Noorakram.
 * Developed By: Jodx.
 * Contact: in@jodx.dev
 */

$uri = urldecode(
  parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

// This file allows us to emulate Apache's "mod_rewrite" functionality from the
if ($uri !== '/' && file_exists(__DIR__ . '/public' . $uri)) {
  return false;
}

require_once __DIR__ . '/public/index.php';
