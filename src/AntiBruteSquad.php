<?php

namespace Dashifen\AntiBruteSquad;

use Dashifen\WPHandler\Handlers\HandlerException;
use Dashifen\WPHandler\Handlers\Plugins\AbstractPluginHandler;

class AntiBruteSquad extends AbstractPluginHandler
{
    /**
     * initialize
     *
     * Uses addAction() and addFilter() to connect WordPress to the methods
     * of this object's child which are intended to be protected.
     *
     * @return void
     * @throws HandlerException
     */
    public function initialize (): void
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
    protected function startSession (): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        
        if (!isset($_SESSION['anti-brute-squad-failure-count'])) {
            $this->resetFailedLoginCount();
        }
    }
    
    protected function resetFailedLoginCount (): void
    {
        // once we successfully log in, or if our count isn't set when we start
        // our session, we reset the count.  likely, no one will log out and
        // attempt to re-log in during the same session, but just in case, we
        // want to give them their same count the second time.
        
        $_SESSION['anti-brute-squad-failure-count'] = 0;
    }
    
    /**
     * countFailedLogin
     *
     * Upon a failed attempt to login, counts it.
     *
     * @return void
     */
    protected function countFailedLogin (): void
    {
        $_SESSION['anti-brute-squad-failure-count']++;
    }
    
    /**
     * preventBruteForceAttacks
     *
     * If our count reaches a threshold (default: 5), emits a HTTP 401
     * Unauthorized status code and dies.
     *
     * @return void
     */
    protected function preventBruteForceAttacks (): void
    {
        if ($_SESSION['anti-brute-squad-failure-count'] >= $this->getLimit()) {
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
        // it's floor and explicitly cast it so that we are guaranteed to
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
        $message = 'You are not authorized to access this site.';
        
        // it might be overkill to both offer the ability to internationalize
        // our message and filter it, but another dev might prefer one over the
        // other with respect to how best to change the default message into
        // one that works best for them.  so, we'll do both.
    
        return apply_filters(
            'anti-brute-squad-access-blocked-message',
            __($message, 'anti-brute-squad')
        );
    }
}
