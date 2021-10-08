<?php

/**
 * Plugin Name: Anti Brute Squad
 * Description: An WordPress must-use plugin that limits login attempts to mitigate brute force attacks.
 * Author URI: mailto:dashifen@dashifen.com
 * Author: David Dashifen Kees
 * Text Domain: anti-brute-squad
 * Version: 3.0.1
 *
 * @noinspection PhpStatementHasEmptyBodyInspection
 * @noinspection PhpIncludeInspection
 */

use Dashifen\AntiBruteSquad\AntiBruteSquad;
use Dashifen\WPHandler\Handlers\HandlerException;

if (!class_exists('Dashifen\AntiBruteSquad\AntiBruteSquad')) {
  require_once 'vendor/autoload.php';
}

(function() {
    try {
        $antiBruteSquad = new AntiBruteSquad();
        $antiBruteSquad->initialize();
    } catch (HandlerException $e) {
        wp_die($e->getMessage());
    }
})();
