=== WordPress User Login Notifier ===
Contributors: buddydev, sbrajesh
Tags: login, user, failed login, admin notification, login notification, email login notification
Requires at least: 5.0
Tested up to: 6.2
Stable tag: 1.0.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WordPress User Login Notifier plugin notifies site admin and users of the successful and failed login attempts via email.

== Description ==

WordPress User Login notifier plugin notifies site admin and the users on a WordPress site when a user logs in succesfully or the login attempt is failed.

=== Features ===

*   Notify site admin of a user login
*	Notify the user of their successful login to a site
*	Notify Site admin when a user login attempt is failed
*	Notify User when his/her account login is failed
*   Admin can setup if the user gets notified or not from the settings page

The purpose of this plugin is to keep track of all the successful and failed login attempts on a WordPress Site.

Please visit [BuddyDev](https://buddydev.com/wordpress/introducing-wordpress-user-login-notification-plugin/ "Plugin release post") for more details

== Installation ==
1. Login to dashboard & click Add New Plugin
1. Search for 'WP User Login Notifier'
1. Click Install & Then activate
1. Visit Settings-> Wp User Login Notifier to update the settings

Or you can install it manually as

1. Download the zip file
2. Extract the zip file 
1. Upload `wp-user-login-notifier` folder to the `/wp-content/plugins/` directory
1. Activate "WP User Login Notifier"  plugin through the 'Plugins' menu in WordPress
1. Visit Settings-> WP User Login Notifier" to update settings
 
== Frequently Asked Questions ==

= Is this plugin compatible with Multisite? =
Yes

= Is this plugin compatible with BuddyPress? =

Yes

== Screenshots ==

1. Failed Login notification to admin screenshot-1.png
2. Failed Login Notification to the user
3. Successful Login notification to admin
4. Successful Login Notification to the user
5. Configuration settings

== Changelog ==

= 1.0.6 =
* Adds a Link to the user profile in the admin notification emails.
* Tested with WordPress 6.2
* Code cleanup to follow WordPress Coding Standards.

= 1.0.5 =
* Tested with WordPress 5.5

= 1.0.4 =
* PHP7+ compatibility.
* Added filters to allow filtering all emails.

= 1.0.3 =
* Added partial translation support(for emails etc).
* Code cleanup to follow WordPress Coding Standards.

= 1.0.2 =
* Little cleanup and compatibility with ajax login.

= 1.0.1 =
* Add admin option to allow extra emails to be notified.

= 1.0.0 =
* Initial release.


