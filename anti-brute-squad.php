<?php

/**
 * Plugin Name: Anti Brute Squad
 * Description: An WordPress must-use plugin that limits login attempts to mitigate brute force attacks.
 * Author URI: mailto:dashifen@dashifen.com
 * Author: David Dashifen Kees
 * Version: 1.0.2
 *
 * @noinspection PhpStatementHasEmptyBodyInspection
 * @noinspection PhpIncludeInspection
 */

use Dashifen\AntiBruteSquad\AntiBruteSquad;
use Dashifen\WPHandler\Handlers\HandlerException;

// the following snippet finds the appropriate autoloader starting from a
// where Dash likes to put it and ending at a reasonable (but unlikely)
// default.  note that the $autoloader variable is set in the if-conditionals,
// so they don't need statement bodies; all their work is done during each
// test.  then, we try requiring what we end up with.

if (file_exists($autoloader = dirname(ABSPATH) . '/deps/vendor/autoload.php'));
elseif ($autoloader = file_exists(dirname(ABSPATH) . '/vendor/autoload.php'));
elseif ($autoloader = file_exists(ABSPATH . 'vendor/autoload.php'));
else $autoloader = 'vendor/autoload.php';
require_once $autoloader;

try {
    (new AntiBruteSquad())->initialize();
} catch (HandlerException $exception) {
    wp_die('Unable to initialize Anti Brute Squad.');
}
