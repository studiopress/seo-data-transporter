=== Plugin Name ===
Contributors: nathanrice, studiopress
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5553118
Tags: seo, genesis, genesiswp, thesis, thesiswp, headway, headwaywp, builder, frugal, hybrid, woothemes, all in one seo, headspace, platinum seo
Requires at least: 4.7.3
Tested up to: 4.7.3
Stable tag: 1.0.0

This plugin allows you to transfer your inputs SEO data from one theme/plugin to another.

== Description ==

This plugin allows you to transfer your inputs SEO data from one theme/plugin to another. We all know how difficult it can be to switch themes or dump plugins. The fact that themes and plugins store their inputs SEO data differently makes it even harder. This plugin remedies that.

Just choose what platform your moving away from, and what platform you want to move to. Click "analyze" to see what records and elements are compatible, and click "convert" to make the conversion.

**Supported Themes**
* Builder
* Catalyst
* Frugal
* Genesis
* Headway
* Hybrid
* Thesis 1.x
* WooFramework

**Supported Plugins**
* Add Meta Tags
* All in One SEO Pack
* Greg's High Performance SEO
* Headspace2
* Infinite SEO
* Jetpack Advanced SEO
* Meta SEO Pack
* Platinum SEO
* SEO Title Tag
* SEO Ultimate
* Yoast SEO

== Installation ==

1. Upload the entire `seo-data-transporter` folder to the `/wp-content/plugins/` directory
1. DO NOT change the name of the `seo-data-transporter` folder
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Navigate to the `Tools > Convert SEO Data` menu

== Frequently Asked Questions ==

= The plugin or theme I use (or want to use) isn't included. Will you include it? =

Probably. If it is a paid theme or plugin, the author will have to provide a copy for us to analyze. If it's free, and demand is high enough, we'll include it.

= How stable is this plugin? I don't want to lose all my SEO. =

It's relatively stable. This plugin has been in the repository since August, 2010, and no one has reported any problems. As always, be sure to keep a backup of your site, just to be safe.

== Screenshots ==
1. The SEO Data Transporter UI, including the dropdown with all the supported platforms

== Changelog ==

= 1.0.0 =
* Rewrite plugin based on new boilerplate
* Add supporte for Jetpack Advanced SEO
* Change "WordPress SEO" to "Yoast SEO"
* Fix "Add Meta Tags" custom field keys

= 0.9.10 =
* Remove support for Thesis 2.x
* Add hooks

= 0.9.9 =
* Added support for SEO Title Tag

= 0.9.8 =
* Added support for Thesis 2.x

= 0.9.7 =
* Added support for Add Meta Tags and Infinite SEO.

= 0.9.6 =
* Added support for Greg's High Performance SEO, Meta SEO Pack, and Yoast's meta keywords. Also, added some sanitization and addressed some notices.

= 0.9.5 =
* Added support for the Catalyst theme, and the SEO Ultimate plugin

= 0.9.4 =
* Fixed bug with Yoast's WordPress SEO fields

= 0.9.3 =
* Added support for new WordPress SEO plugin by Yoast
* Fixed entries for Headspace2 plugin

= 0.9.2 =
* split the platform array into themes and plugins.
* added `<optgroup></optgroup>` wrappers in the `<select>` dropdowns

= 0.9.1 =
* minor fixes

= 0.9 =
* Initial Release
