=== Autostock ===
Contributors: dollar_dad
Donate link: http://kevinphillips.co.nz/plugin-support/
Tags:  auto dealer, automotive, car dealer, car lots, car sales
Requires at least: 4.1
Tested up to: 4.1
Stable tag: 1.0.2
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl.html

AutoStock is a Plugin for car dealers, or developers/designers that build websites for clients that host car dealer websites.

== Description ==

AutoStock is "Work-in-progress" for websites that are used for car dealers. I plan on adding additional functionality
that allows for motorcycles, caravans, motor-homes and boats shortly.

This plugin creates custom content types for motor vehicle with taxonomies for makes, models and features. Note that your
hosting server must be running minimum of PHP 5.3.6 as the code includes namespaces.

You should be able to use this plugin as a custom post type for vehicle sales. (See the screenshots)

I have decided not to incorporate any image gallery as we have access to hundreds, if not thousands through the WordPress Repository.
I am using the built in WordPress Gallery to manage images for each vehicle and a simple plugin to add a light box plugin (Lightbox Galleries EWSEL).

If you feel this plugin is useful and you would like me to continue developing it please consider a small donation :-)

This plugin is far from finished as I intend to add some additional features.

Please contact me if you have any request or features that would be useful.

Latest builds ( unstable ) can be taken from my github account dollardad->autostock

== Installation ==

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Visit your settings/permalinks and reload if the new custom post type is not displaying.

== Frequently Asked Questions ==

= Where is the image upload function =

I have not added any custom image or gallery function as we have so many, hundreds if not thousands to choose from it would seem wrong to limit this plugin by a single imaage/gallery option.
With my demo site I amd using the built in WordPress gallery and I've added the Lightbox Galleries EWSEL plugin to give me a simple light box.

== Screenshots ==

1. Setup vehicle options, vehicle year range and kilometres vs Miles.
2. Vehicle makes, features and types are categories ( so we can use them for indexing), here you can load some defaults.
3. Add new car just like any other post/page. Enter in the vehicle details which can be displayed via your theme.
4. Select vehicle features, which are categories so you can add/edit them like any other category.
5. In the body add vehicle description and images. You can optionally use one of the many hundreds of plugins to create a gallery or use the built in WordPress gallery library.
6. Some countries require you to display fuel economy information (I have copied the New Zealand badge from the government website).
7. Add vehicle make, model and badge, here I have used three levels as I intended to use these later in my indexing.
8. Manage your categories just like any other.

== Changelog ==

1.0.1. Added settings options and taxonomy for makes and models
1.0.2. Added custom car features to car post types as taxonomy with checkboxes.
1.0.3. Added Vehicle Details and Features category to post type

== Upgrade Notice ==
