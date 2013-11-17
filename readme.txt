=== Plugin Name ===
Contributors: nathanrice, studiopress
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5553118
Tags: seo, genesis, genesiswp, thesis, thesiswp, headway, headwaywp, builder, frugal, hybrid, woothemes, all in one seo, headspace, platinum seo
Requires at least: 3.0.1
Tested up to: 3.0.1
Stable tag: 0.9.3

This plugin allows you to transfer your inputs SEO data from one theme/plugin to another.

== Description ==

This plugin allows you to transfer your inputs SEO data from one theme/plugin to another. We all know how difficult it can be to switch themes or dump plugins. The fact that themes and plugins store their inputs SEO data differently makes it even harder. This plugin remedies that.

Just choose what platform your moving away from, and what platform you want to move to. Click "analyze" to see what records and elements are compatible, and click "convert" to make the conversion.

**Supported Themes**

* Builder
* Frugal
* Genesis
* Headway
* Hybrid
* Thesis
* WooFramework

**Supported Plugins**

* All in One SEO
* Headspace2
* Platinum SEO
* WordPress SEO

== Installation ==

1. Upload the entire `seo-data-transporter` folder to the `/wp-content/plugins/` directory
1. DO NOT change the name of the `seo-data-transporter` folder
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Navigate to the `Tools > SEO Data Transport` menu

== Frequently Asked Questions ==

= The plugin or theme I use (or want to use) isn't included. Will you include it? =

Probably. If it is a paid theme or plugin, the author will have to provide a copy for us to analyze. If it's free, and demand is high enough, we'll include it.

= How stable is this plugin? I don't want to lose all my SEO. =

It's relatively stable, but is still in beta. Be sure to make a proper database backup before converting.

== Screenshots ==
1. The SEO Data Transporter UI, including the dropdown with all the supported platforms 

== Changelog ==

= 0.9 =
* Initial Release

= 0.9.1 =
* minor fixes

= 0.9.2 =
* split the platform array into themes and plugins.
* added `<optgroup></optgroup>` wrappers in the `<select>` dropdowns

= 0.9.3 =
* Added support for new WordPress SEO plugin by Yoast
* Fixed entries for Headspace2 plugin