# Anti Brute Squad

Anti-Brute Squad is a very straightforward way to block access to your 
WordPress Dashboard after a certain number of failed login attempts.  By 
default, that number is five, but you can change it with a filter (see below).

It's setup to become a composer dependency, but if that's not your way of 
handling WordPress plugins, feel free to simply copy the logic out of the
`src/AntiBruteSquad.php` file.  If you do use composer, then as long as you
are using both the `composer/installers` and `lkwdwrd/wp-muplugin-loader` 
packages, Anti-Brute Squad should load up as an MU plugin.

As written, this plugin requires at least PHP 8.2.  It has been tested up to
PHP 8.4.

## Filters

1. `anti-brute-squad-login-limit` - changes the number of failed login attempts
a visitor has before they're locked out.  The default is five.

2. `anti-brute-squad-access-blocked-message` - the default message that appears
on-screen accompanying an HTTP 401 Unauthorized header when a visitor exceeds
that limit.  The default is "You are not authorized to access this site."  This
can also be changed via the WordPress internationalization capabilities if it's
easier for you to do so.   
