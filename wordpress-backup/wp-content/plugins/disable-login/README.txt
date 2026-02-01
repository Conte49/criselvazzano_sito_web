=== Disable Login ===
Contributors: ENDif Media
Donate link: http://endif.media
Tags: login, disable, maintenance mode, admin
Requires at least: 3.0.1
Tested up to: 4.9.1
Stable tag: 1.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Disable login by certain users. Useful when you are moving a client site and don't want anyone to make any edits to the WordPress database.

== Description ==

This plugin is a good tool to have in your developer arsenal. An advanced maintenance mode  tool that will prevent all users (except you, the developer) from logging into the WordPress backend. Your users will see a nice message just below the WordPress logo informing them that you are in the process of fixing their website. Now with WordPress Multisite support!

The basic version of Disable Login includes the following features:

* Prevent all users from logging into the admin area except for 1 user account.
* Adds a default message and sub-message to WordPress login screen.

Pro version available of Disable Login includes the following features: 

* Prevent some users from logging into the admin area.
* Ability to allow multiple users access to the admin area via a multiselect input!
* Adds a customizable message and sub-message to the WordPress login screen.
* 1 year of Support and Updates!
* License activation on up to 3 sites!
* For more features and to upgrade to the Pro version of Disable Login, go to:
[http://endif.media/disable-login-premium/](http://endif.media/disable-login-premium/)

== Installation ==

1. Upload disable-login to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enable the plugin and set the allowed user to your username.

= With WP-CLI =
`wp plugin install disable-login --activate`

== Frequently Asked Questions ==

= Help! I’ve locked myself out! =

Let’s hope you have ssh access to the server. You can re-name the plugin folder to `disable-login-letmein` (or anything really). This will cause WordPress to deactivate the plugin.

== Screenshots ==

1. The results!

== Changelog ==

= 1.1.1 =

* [Fix] Plugin text unable to be translated properly
* [Fix] Undefined index error when saving plugin settings

= 1.1.0 =

* Added Multisite Support!

= 1.0.2 =

* Refactor code 
* Cleanup comments
* Fix: typos

= 1.0.1 =

* Added support for WordPress version 4.5.2. 

= 1.0 =
* Initial release.