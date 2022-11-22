=== Filterable Portfolio ===
Contributors: sayful
Tags: portfolio, filterable portfolio, images portfolio, portfolio gallery, portfolio plugin, filtrable portfolio, responsive portfolio, wordpress portfolio, wp portfolio, wordpress portfolio plugin, sortable portfolio, project portfolio
Requires at least: 5.5
Tested up to: 6.1
Requires PHP: 7.0
Stable tag: 1.6.3
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt

A WordPress Portfolio plugin to display portfolio/project images to your site.

== Description ==

A WordPress Portfolio plugin to display portfolio/project images to your site.

= Features =

* **Fully responsive**
* **Portfolio Showcase** with sorting images by groups.
* **Supported Browsers:** Chrome, Firefox, Edge, IE 11, Safari
* **Two portfolio themes with css3 effect**
* **Single portfolio page template**
* **Slide of portfolio images on single portfolio page**
* **Related Projects on single portfolio page**
* **Setting page with many options**
* **Custom Meta box** for Project Images, Client Name, Project Date and Project URL

= Usages =

If you are using Gutenberg Block Editor (WordPress 5.0 or later), search 'Filterable Portfolio'. There is
a dedicated 'Filterable Portfolio' block with live preview for Gutenberg Block Editor.

After installing and activating the plugin, a new custom post type called "Portfolios" will appear at your WordPress Admin area. Just create your Portfolio from the "Portfolios" menu & paste the following shortcode where you want to display this Portfolio:

`[filterable_portfolio]`

The shortcode can include following attributes.

* `featured`: Default value `no`. Value can be `yes` or `no`.
* `show_filter`: Default value `yes`. Value can be `yes` or `no`.
* `filter_by`: Default value `categories`. Value can be `categories` or `skills`.
* `theme`: Default value `one`. Value can be `one` or `two`.
* `buttons_alignment`: Default value `center`. Value can be `start` or `center` or `end`.

Example 1:

`[filterable_portfolio featured='yes' show_filter='no']`

= Theme Integration =

Filterable Portfolio works with most theme out of the box. But the following theme support its full features like single, archive and taxonomy template. Filterable Portfolio is fully integrated with the following themes.

01. Shapla - [https://wordpress.org/themes/shapla/](https://wordpress.org/themes/shapla/)

== Installation ==

Installing the plugins is just like installing other WordPress plugins. If you don't know how to install plugins, please review the two options below:

= Install by Search =

* From your WordPress dashboard go to **Plugins > Add New**.
* Search for **Filterable Portfolio** in **Search Plugins** box.
* Find the WordPress Plugin named **Filterable Portfolio** by **Sayful Islam**.
* Click **Install Now** to install the **Filterable Portfolio** Plugin.
* The plugin will begin to download and install.
* Now just click **Activate** to activate the plugin.

If you still need help. visit [WordPress codex](https://codex.wordpress.org/Managing_Plugins#Installing_Plugins)


== Frequently Asked Questions ==

= After changing portfolio slug, portfolio link is not working. =
After change portfolio slug, you need to regenerate permalink.
To regenerate permalink, go to *Settings --> Permalinks* from WordPress admin and press on "Save Changes" button.

= How to use Filterable Portfolio in Gutenberg Block Editor (WordPress 5.0 or later) =
Filterable Portfolio is first class citizen in Gutenberg Block Editor. Just search 'Filterable Portfolio'. There is
a dedicated 'Filterable Portfolio' block with live preview for Gutenberg Block Editor.

= I want to remove Project Date. Is it possible? =
You can add, remove or modify any field using filter hook. *filterable_portfolio_meta_box_fields*
Here is an example to remove Project Date.

`function filterable_portfolio_remove_product_date( $fields ) {
    // Remove product date
    unset( $fields['_project_date'] );

    return $fields;
}
add_filter( 'filterable_portfolio_meta_box_fields', 'filterable_portfolio_remove_product_date' );`

Here is a list of all default meta fields: _project_images, _client_name, _project_date, _project_url

== Screenshots ==

1. Screenshot of Theme one.
2. Screenshot of Theme two.
3. Filterable Portfolio in Gutenberg Block Editor.
4. List of portfolios in admin dashboard.
5. Filterable Portfolio settings.
6. Filterable Portfolio edit page.

== Changelog ==

= version 1.6.3 - 2022-11-22 =
* Add filter hook to load single portfolio template and archive portfolio template from plugin.
* Add 'orderby' and 'order' shortcode and block attributes to change global option.

= version 1.6.2 - 2022-09-23 =
* Add default archive template for portfolio post type if it not exists in theme.
* Add attribute to change filter option from 'categories' to 'skills'.
* Add setting option to disable single portfolio category and skill archive page link.

= version 1.6.1 - 2022-07-22 =
* Add responsive setting on block option.
* Add block option to set maximum limit.
* Fix PHP Deprecated notice when finding invert color.
* Add background color on 'Filterable Portfolio' brand icon.
* Fixed design issue related to box-sizing on default 'Twenty Twenty-Two' theme.

= version 1.6.0 - 2022-07-17 =
* Feature   - Add block with options (toggle filter buttons, show only features projects, theme, filter buttons alignment and more)
* Added     - Add setting option to set project date as create datetime.
* Added     - Add two shortcode attribute 'theme' and 'buttons_alignment'

= version 1.5.2 - 2022-06-23 =
* Added     - Add setting option to set alignment for filter buttons.
* Dev       - Check compatibility with WordPress 6.0

= version 1.5.1 - 2021-08-01 =
* Dev   	- Add CLI command to add dummy data.
* Dev       - Check compatibility with WordPress 5.8
* Fix       - Fix 'permission_callback' php notice

= version 1.5.0 - 2019-11-17 =
* Dev       - Upgrade `isotope` to version 3.0.6 and `tiny-slider` to version 2.9.2
* Added     - Add portfolios REST endpoint to get portfolios.
* Added     - Add categories and skills REST endpoint.
* Added     - Add "Featured" attribute on shortcode and REST api.
* Tweak 	- Hide filter button if button quantity is one.
* Added 	- Add image size for single portfolio project images.
* Tweak 	- Removed alpha color picker for filter buttons.
* Dev   	- Removed shuffle js library.
* Dev   	- Update css inline style with css variable.
* Dev   	- Update shortcode html structure and refactor core code.

= version 1.4.0 - 2019-01-31 =
* Added     - Add support for Gutenberg editor introduced on WordPress 5.0
* Added     - Add CSS for Twenty Nineteen theme.
* Dev       - Check compatibility with WordPress 5.0
* Dev       - Update isotope to version 3.0.5
* Dev       - Update Shuffle to version 5.2.1
* Dev       - Update tiny-slider to version 2.9.1
* Dev       - Add `Filterable_Portfolio_Helper` PHP class

= version 1.3.2 - 2018-05-11 =
* Added 	- Add portfolio settings to change Portfolio Slug, Portfolio Category Slug, Portfolio Skill Slug.
* Dev       - Add filter hook *filterable_portfolio_category_args* for modifying portfolio_cat taxonomy arguments.
* Dev       - Add filter hook *filterable_portfolio_skill_args* for modifying portfolio_skill taxonomy arguments.
* Dev       - Update plugin core.

= version 1.3.1 - 2017-12-09 =
* Added 	- Add alpha color picker to choose button color.
* Fixed 	- Fixed images overlapping issue.
* Tweak 	- Upgrade Shuffle to version 5.0.3
* Tweak 	- Upgrade Isotope to version 3.0.4
* Tweak 	- Upgrade Tiny Slider to version 2.3.10
* Dev       - Load non-minified version when script debug is enabled.
* Dev       - Update plugin core.

= version 1.3.0 - 2017-10-12 =
* Update 	- Update plugin core
* Tweak 	- Replace [ResponsiveSlides](http://responsiveslides.com/) with [tiny-slider](https://github.com/ganlanyuan/tiny-slider)
* Tweak 	- Combine public facing custom scripts to one file

= version 1.2.2 - 2017-06-22 =
* Updated - Removed jQuery dependency for isotope and rewrite with vanilla JS.

= version 1.2.1 - 2017-03-27 =
* Added 	- New Filterable Portfolio Widget to add portfolio at widget. Especially helpful for page builder that use widget like "Page Builder by SiteOrigin" or "Elementor Page Builder".
* Added 	- Added archive, taxonomy, and single portfolio template for [Shapla](https://wordpress.org/themes/shapla/) Theme.

= version 1.2.0 - 2017-03-21 =
* Added 	- Added option to choose filter script from Shuffle or Isotope

= version 1.1.2 - 2017-03-20 =
* Fixed 	- Fixed “headers already sent” notice on plugin activation
* Added 	- Added portfolio_skill taxonomy
* Added 	- Added options to change meta label on portfolio single page

= version 1.1.1 - 2017-03-13 =
* Updated 	- To make portfolio_cat taxonomy hierarchical like categories
* Added 	- Added 'custom-fields' support on portfolio post type
* Added 	- Added option to customize shortcode from theme using 'filterable_portfolio.php' file
* Added 	- Added option to set order, orderby and posts per page

= version 1.1.0 =
* Updated 	- Upgraded shuffle javaScript to version 4.0.2
* Removed 	- prettyPhoto, fontawesome and modernizr
* Added 	- Added Project Images, Client Name, Project Date and Project URL.
* Added 	- Setting page with many sitting options.
* Added 	- Two Portfolio Theme.
* Added 	- Single page template.
* Added 	- Slide of images on single page.
* Added 	- Related Projects on single page.

= version 1.0.1 =
* Added support for comment on portfolio single post.

= version 1.0.0 =
* Implementation of basic functionality.

== CREDIT ==

2. [Isotope](http://isotope.metafizzy.co/)
3. [tiny-slider](https://github.com/ganlanyuan/tiny-slider)

== Upgrade Notice ==

Upgrade the plugin to get latest feature and faster speed and compatibility with new version.
