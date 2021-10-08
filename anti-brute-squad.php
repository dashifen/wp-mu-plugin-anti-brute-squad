<?php

/**
 * Plugin Name: Anti Brute Squad
 * Description: An WordPress must-use plugin that limits login attempts to mitigate brute force attacks.
 * Author URI: mailto:dashifen@dashifen.com
 * Author: David Dashifen Kees
 * Text Domain: anti-brute-squad
 * Version: 2.0.0
 *
 * @noinspection PhpStatementHasEmptyBodyInspection
 * @noinspection PhpIncludeInspection
 */

use Dashifen\AntiBruteSquad\AntiBruteSquad;
use Dashifen\WPHandler\Handlers\HandlerException;

if (file_exists($autoloader = ABSPATH . 'wp-content/vendor/autoload.php'));
else $autoloader = 'vendor/autoload.php';
require_once $autoloader;

(function() {
    try {
        $antiBruteSquad = new AntiBruteSquad();
        $antiBruteSquad->initialize();
    } catch (HandlerException $e) {
        wp_die($e->getMessage());
    }
})();
