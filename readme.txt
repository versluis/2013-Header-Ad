=== P2 Header Ad ===
Contributors: versluis
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=34B76TPRWMWAE
Tags: p2, advert, ad, header, code
Requires at least: 3.3
Tested up to: 4.0
Stable tag: 1.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Places a 468x80 pixel advert inside the header of Automattic's wonderful P2 Theme.

== Description ==

Once installed, head over to Appearance - P2 Header Ad and paste your ad code into the box. 

Features:

* preview your ad in the admin interface (see screenshots)
* option to hide the ad for logged-in users
* option to hide the ad for logged-in WP eMember users
* works with and without a header image
* option to show the same ad after the post content

I've optimised the plugin for a 468x80 pixel ad. Other sizes will work but may require your own CSS tweaks to make them look right.


== Installation ==

1. Either: Upload the entire folder to `/wp-content/plugins/` directory
1. Or: download the ZIP file, then head over to Plugins - Add New - Install, then browse to your file
1. Or: from Plugins - Add New, search for this plugin and hit "install"
1. Then: Activate it through the 'Plugins' menu in WordPress
1. Under Appearance - P2 Header Ad you'll find an admin interface to paste your HTML code


== Frequently Asked Questions ==

= I'm using a different ad size than 468x80 pixels. How do I tweak the CSS? =
 
P2 Header Ad will wrap your code inside a DIV tag with the ID "p2HeaderAd". Simply re-position by targeting this ID.

You can do this either in your Theme's styles.css file, or tweak p2-header-ad-styles.css inside the plugin's main directory.


= Will this plugin work with a custom header graphic? =

Yes, it sits on top of your custom header graphic. It will adjust its height automatically so it should always look nice.


= I'm using Google Adsense ads, but they don't show up. What gives? =

It takes about half an hour for fresh Google ads to appear. Until then you'll only see a blank space. Grab a coffee and check back a little later.


= I'm showing ads on the front page after the post content - but now the header ad is gone. What's up with that? =

Google ads can only be shown up to 5 times on a single page. If your front page shows 5 posts or more, then the ad can no longer be displayed in the header (which is called last for performance reasons).

If you want to use the after-content-front-page-ad feature, and you want the header to show first, you can tweak line 293 of the main plugin file (p2-header-ad.php) from this

add_action ('get_footer', 'p2DisplayAdvert');

to

add_action ('get_header', 'p2DisplayAdvert');

I may make this an option in the admin interface in a future update.


== Screenshots ==

1. see your ad displayed on the P2 front page
1. the Admin Interface let's you paste HTML code quickly and easily (under Appearance - P2 Header ad)


== Changelog ==

= 1.5 =
1. added option to display the same ad at the end of single posts
1. added option to hide ads at the end of front-page/blog-page posts

= 1.4 =
1. added the option to hide ad when users are logged into eMember (plugin by Tips and Tricks HQ)

= 1.3 =
1. added translation readiness
1. added German Translation
1. added Spanish Translation (thanks to Andrew Kurtis)

= 1.2 =
1. tweaked placement of advert: now it's fixed to the header
1. added funky fade-in effect when the site loads

= 1.1 =
1. added option to remove the ad for logged-in users

= 1.0 =
1. initial Release
