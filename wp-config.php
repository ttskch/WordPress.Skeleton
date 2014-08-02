<?php
// =======================
//  Load local parameters
// =======================
require __DIR__ . '/local-config.php';

define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

// ================================================================
//  Salts, for security
//  Grab these from: https://api.wordpress.org/secret-key/1.1/salt
// ================================================================
define('AUTH_KEY',         'put your unique phrase here');
define('SECURE_AUTH_KEY',  'put your unique phrase here');
define('LOGGED_IN_KEY',    'put your unique phrase here');
define('NONCE_KEY',        'put your unique phrase here');
define('AUTH_SALT',        'put your unique phrase here');
define('SECURE_AUTH_SALT', 'put your unique phrase here');
define('LOGGED_IN_SALT',   'put your unique phrase here');
define('NONCE_SALT',       'put your unique phrase here');

// ================================================================
//  Table prefix
//  Change this if you have multiple installs in the same database
// ================================================================
$table_prefix  = 'wp_';

// ==================================
//  Language
//  Leave blank for American English
// ==================================
define('WPLANG', 'ja');

// =====================
//  Bootstrap WordPress
// =====================
if (!defined('ABSPATH'))
    define('ABSPATH', dirname(__FILE__) . '/');
require_once ABSPATH . 'wp-settings.php';
