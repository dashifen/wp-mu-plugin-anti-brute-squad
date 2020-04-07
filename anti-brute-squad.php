<?php

/**
 * Plugin Name: Anti Brute Squad
 * Description: An WordPress must-use plugin that limits login attempts to mitigate brute force attacks.
 * Author URI: mailto:dashifen@dashifen.com
 * Author: David Dashifen Kees
 * Version: 1.2.0
 *
 * @noinspection PhpStatementHasEmptyBodyInspection
 * @noinspection PhpIncludeInspection
 */

use Dashifen\AntiBruteSquad\AntiBruteSquad;
use Dashifen\WPHandler\Handlers\HandlerException;

if (file_exists($autoloader = dirname(ABSPATH) . '/deps/vendor/autoload.php'));
elseif ($autoloader = file_exists(dirname(ABSPATH) . '/vendor/autoload.php'));
elseif ($autoloader = file_exists(ABSPATH . 'vendor/autoload.php'));
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
