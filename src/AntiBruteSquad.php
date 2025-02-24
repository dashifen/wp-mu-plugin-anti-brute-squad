<?php

namespace Dashifen\WordPress\Plugins\MustUse\AntiBruteSquad;

use Dashifen\WPHandler\Handlers\HandlerException;
use Dashifen\WPHandler\Handlers\Plugins\AbstractPluginHandler;

class AntiBruteSquad extends AbstractPluginHandler
{
  private string $sessionId = 'anti-brute-squad-failure-count';
  
  /**
   * Uses addAction and/or addFilter to attach protected methods of this object
   * to the ecosystem of WordPress action and filter hooks.
   *
   * @return void
   * @throws HandlerException
   */
  public function initialize(): void
  {
    if (!$this->isInitialized() && php_sapi_name() !== 'cli') {
      if (!is_admin()) {
        $this->addAction('init', 'startSession');
        $this->addAction('wp_login_failed', 'countFailedLogin');
        $this->addAction('wp_login', 'resetFailedLoginCount');
        $this->addAction('login_init', 'preventBruteForceAttacks');
      }
    }
  }
  
  /**
   * startSession
   *
   * Starts the session if it has not already been started.  Also, sets our
   * count to zero if it's currently unavailable.
   *
   * @return void
   */
  protected function startSession(): void
  {
    $this->sessionId = apply_filters('anti-brute-squad-session-id', $this->sessionId);
    
    if (session_status() !== PHP_SESSION_ACTIVE) {
      session_start();
    }
    
    if (!isset($_SESSION[$this->sessionId])) {
      $this->resetFailedLoginCount();
    }
  }
  
  protected function resetFailedLoginCount(): void
  {
    // once we successfully log in, or if our count isn't set when we start
    // our session, we reset the count.  likely, no one will log out and
    // attempt to re-log in during the same session, but just in case, we
    // want to give them their same count the second time.
    
    $_SESSION[$this->sessionId] = 0;
  }
  
  /**
   * countFailedLogin
   *
   * Upon a failed attempt to login, counts it.
   *
   * @return void
   */
  protected function countFailedLogin(): void
  {
    $_SESSION[$this->sessionId]++;
  }
  
  /**
   * preventBruteForceAttacks
   *
   * If our count reaches a threshold (default: 5), emits an HTTP 401
   * Unauthorized status code and dies.
   *
   * @return void
   */
  protected function preventBruteForceAttacks(): void
  {
    if ($_SESSION[$this->sessionId] >= $this->getLimit()) {
      header('HTTP/1.0 401 Unauthorized');
      wp_die($this->getMessage());
    }
  }
  
  /**
   * getLimit
   *
   * Returns the number of attempts a visitor has before they're blocked from
   * continuing to access the login form during this session.
   *
   * @return int
   */
  private function getLimit(): int
  {
    $limit = apply_filters('anti-brute-squad-login-limit', 5);
    
    // one would think that might be enough.  but, some people just want to
    // watch the world burn, and such people might return something other
    // than an integer when filtering our limit.  so, if what we have now
    // isn't even a number, then we'll revert to the default.  we also find
    // its floor and explicitly cast it so that we are guaranteed to
    // return an int.
    
    return (int) floor(is_numeric($limit) ? $limit : 5);
  }
  
  /**
   * getMessage
   *
   * Returns the on-screen message displayed when access to the login screen
   * is blocked.
   *
   * @return string
   */
  private function getMessage(): string
  {
    return apply_filters('anti-brute-squad-access-blocked-message',
      'You are not authorized to access this site.');
  }
}
