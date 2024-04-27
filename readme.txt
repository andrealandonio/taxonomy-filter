=== Taxonomy Filter ===
Contributors: lando1982
Tags: usability, filter, admin, category, tag, term, taxonomy, hierarchy, organize, manage
Requires at least: 4.0
Tested up to: 6.5.2
Stable tag: 2.2.13
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

Taxonomy Filter is a plugin which allow users to filter hierarchical taxonomy terms inside admin pages and provides a way to hide terms for each user

== Description ==

Taxonomy Filter is a simple and flexible plugin which allow users to filter hierarchical term taxonomies inside admin pages. If you need to simplify your tags and categories research on admin pages, this plugin will make it easier for you. It adds a custom input field (only for configured taxonomies) that you can use to filter a every taxonomy list.

Imagine having too many tags on your post admin page and having to lose so much time scrolling a long list of items or having to search for a tag with the classic browser search box. With "Taxonomy Filter" plugin you can search, choice and select tags in a very short time, a great gain!

In addition, you have to setup which taxonomies should have "Taxonomy Filter" activated. When you install and activate the plugin, an admin page is added on settings section. In this page are automatically listed all valid taxonomies, you have two options:

* enable on post management pages (allow you to turn on/off filter field)
* hide filter field if taxonomy is empty

You can also manage taxonomy filters into bulk edit section if you want to perform a quick edit.

If in your theme you have changed post columns using the 'manage_edit-post_columns' filter, you need to add another filter to apply the taxonomy filter bulk section.
For example:
`
add_filter('manage_edit-post_columns', 'taxonomy_filter_manage_bulk_columns', 99, 1);
add_filter('manage_edit-<CUSTOM-TYPES>_columns', 'taxonomy_filter_manage_bulk_columns', 99, 1);
`

It works only with hierarchical taxonomies (both default categories and [custom taxonomies](http://codex.wordpress.org/Custom_Taxonomies)).

When you enable a taxonomy filter, a section for choosing hidden taxonomy terms is displayed in user profile page and in term edit pages. In user profile page you can select (for each user) a list of taxonomy terms that are removed from hierarchical term taxonomies inside admin pages.
By default, all taxonomy terms are visible in the hierarchical term taxonomies sections inside admin pages. You can choose only from max 2 nested levels but all the children of a hidden term are automatically removed from admin pages. Keep in mind that the hidden terms are not searchable and filterable.
In edit term page you can select (for all users) if term should be removed (make hidden) from hierarchical term taxonomies inside admin pages.

= Usage =

1. Go to `WP-Admin -> Posts -> Add New`.
2. Find the input filter field on page sidebar.
3. Select tags filtering list.

Links: [Author's Site](http://www.andrealandonio.it)

== Installation ==

1. Unzip the downloaded `taxonomy-filter` zip file
2. Upload the `taxonomy-filter` folder and its contents into the `wp-content/plugins/` directory of your WordPress installation
3. Activate `taxonomy-filter` from Plugins page

== Frequently Asked Questions ==

= Works on multisite? =

Yes, you have only to enable valid taxonomies on settings page for every site.

= Works on hierarchical taxonomies? =

Yes, you can filter items over taxonomies with multiple child/parent levels.

= Works on with custom post types? =

Yes, keep in mind to add 'manage_edit-post_columns' filter if you want show filter in bulk edit section.

= Works on with custom taxonomies? =

Yes, by default you can filter all the taxonomies that you have enabled in Taxonomy Filter settings page.

== Screenshots ==

1. Settings admin page
2. Filter tags (initial list before filtering and filtered list)
3. Filter categories (initial list before filtering and filtered list)
4. Bulk edit section
5. User hidden taxonomy terms selection

== Changelog ==

= 2.2.13 - 2024-04-27 =
* Rename plugin name following WordPress standard

= 2.2.12 - 2024-04-23 =
* Added taxonomy rewrite field check for avoid warnings

= 2.2.11 - 2024-04-19 =
* Added hidden option check for avoid warnings

= 2.2.10 - 2024-02-06 =
* Added nonce management on settings page

= 2.2.9 - 2022-11-15 =
* Added "taxonomy_filter_profile_table" for hiding user profile fields

= 2.2.8 - 2021-03-14 =
* Tested up with WordPress 5.7 release

= 2.2.7 - 2020-12-09 =
* Tested up to latest WordPress releases

= 2.2.6 - 2020-10-27 =
* Added conversion to iterable objects

= 2.2.5 - 2019-11-21 =
* Restored hide items

= 2.2.4 - 2019-11-17 =
* Remove hide items

= 2.2.3 - 2019-05-22 =
* Updated WordPress requirements

= 2.2.2 - 2019-03-13 =
* Fixed invalid object save settings error

= 2.2.1 - 2018-08-24 =
* Bug fixing

= 2.2.0 - 2018-03-09 =
* Keep showing the children of a searched element

= 2.1.1 - 2018-02-21 =
* Bug fixing

= 2.1.0 - 2017-01-15 =
* Add hide taxonomy terms feature

= 2.0.0 - 2016-05-18 =
* Add hide user taxonomy terms feature
* JS DOM selectors review
* JS enqueue review
* Bug fixing

= 1.1.1 - 2015-10-25 =
* Extend filters selection to all the categories in bulk edit section

= 1.1.0 - 2015-10-24 =
* Add filters to bulk edit section

= 1.0.2 - 2014-12-11 =
* Increased plugin's compatibility to older WordPress versions

= 1.0.1 - 2014-11-15 =
* Fixed hierarchical filter search

= 1.0.0 - 2014-11-12 =
* First release

== Upgrade Notice ==

= 1.0.0 =
This version requires PHP 5.3.3+
