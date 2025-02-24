<?php

/**
 * Plugin Name: Anti-Brute Squad
 * Description: An WordPress must-use plugin that limits login attempts to mitigate brute force attacks.
 * Author URI: mailto:dashifen@dashifen.com
 * Author: David Dashifen Kees
 * Version: 3.1.0
 */

namespace Dashifen\WordPress\Plugins\MustUse;

use Dashifen\WPHandler\Handlers\HandlerException;
use Dashifen\WordPress\Plugins\MustUse\AntiBruteSquad\AntiBruteSquad;

if (!class_exists(AntiBruteSquad::class)) {
  require_once 'vendor/autoload.php';
}

(function () {
  try {
    $antiBruteSquad = new AntiBruteSquad();
    $antiBruteSquad->initialize();
  } catch (HandlerException $e) {
    AntiBruteSquad::catcher($e);
  }
})();
