=== WP Instant Links ===
Tags: instant page, fast loading, conversions, page speed, prefetch, cdn
Contributors: kbat82
Donate link: https://www.paypal.me/kevinbatdorf/5
Requires at least: 4.1.0
Requires PHP: 5.3
Tested up to: 5.2
Stable tag: 1.1.0
License: MIT

Have your site’s pages load instantly without having to do any custom coding.

== Description ==

Works out of the box. After activating, this plugin will pre-fetch the next web page just before your user clicks, thus giving the experience of an instant page load. 

Better UX. Perfect for slower and shared web hosting plans. 

This plugin is a wrapper for instant.page with some additional options and designed to be ready-to-go out of the box. No configuration needed.

More from <a href="https://instant.page/">https://instant.page/</a> -

instant.page uses just-in-time preloading — it preloads a page right before a user clicks on it.

Before a user clicks on a link, they hover their mouse over that link. When a user has hovered for 65 ms there is one chance out of two that they will click on that link, so instant.page starts preloading at this moment, leaving on average over 300 ms for the page to preload.

**Easy on your server and your user’s data plan**

Pages are preloaded only when there’s a good chance that a user is going to visit them, and it preloads only the HTML of that page, being respectful of your users’ and servers’ bandwidth and CPU. It’s 1 kB and loads after everything else. And it’s free and open source (MIT license).

Some Technical Info: https://instant.page/tech

== Installation ==

The easy way:

1. Go to the Plugins Menu in WordPress
2. Click "Add New"
3. Search for "WP Instant Links"
4. Click "Install Now"
5. Click "Activate"

The not so easy way:

1. Upload the `wp-instant-links` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. You're all set!

== Frequently Asked Questions ==

= Do you preload every page? =

This will only preload an internal page when the user hovers over the link to the page for 65ms. To view which pages are being loaded, enable debugging in the settings and check the developer tools console for details

= Will it preload the logout page and log me out? =

Pages with a query string (a “?”) in their URL aren’t preloaded, which includes the WP logout URL. If you would like to include URLs with query strings, you check that option in the settings area.

= Can I override a link that I do not want preloaded? =

Yes, just add the "data-no-instant" attribute to the element, or add it to the exclusions list in the settings area.

== Screenshots ==

1. Convenient settings area to debug links, exclude links and override other settings.

== Changelog ==

= 1.1.0 - 2019/Apr/28 =

* FEATURE: Adds option to include all external links
* TWEAK: Updates instant.page to 1.2.2
* TWEAK: Adds a few extra debugging outputs

= 1.0.2 - 2019/Feb/14 =

* TWEAK: Updates content in various areas

= 1.0.1 - 2019/Feb/14 =

* FIX: Adds JS file forgotten by deploy script

= 1.0.0 - 2019/Feb/14 =

* Initial Release

== Upgrade Notice ==

= 1.1.0 =
Upgrade now to use the latest version of instant.page.