# filterable-portfolio

A WordPress Portfolio plugin to display portfolio/project images to your site.

## Features

* **Fully responsive**
* **Portfolio Showcase** with sorting images by groups.
* **Supported Browsers:** Chrome, Firefox, Edge, IE 11, Safari
* **Two portfolio themes with css3 effect**
* **Single portfolio page template**
* **Slide of portfolio images on single portfolio page**
* **Related Projects on single portfolio page**
* **Setting page with many options**
* **Custom Meta box** for Project Images, Client Name, Project Date and Project URL

## Usages

After installing and activating the plugin, a new custom post type called "Portfolios" will appear at your Wordpress Admin area. Just create your Portfolio from the "Portfolios" menu & paste the following shortcode where you want to display this Portfolio:

**[filterable_portfolio]**


## Changelog

#### version 1.3.1 - 2017-12-09
* Added 	- Add imagesLoaded javaScript plugin to fix images overlapping issue.
* Added 	- Add alpha color picker to choose button color.
* Fixed 	- Fixed images overlapping issue.
* Dev       - Load non-minified version when script debug is enabled.
* Dev       - Update core code.

#### version 1.3.0 - 2017-10-12
* Update 	- Update plugin core
* Tweak 	- Replace [ResponsiveSlides](http://responsiveslides.com/) with [tiny-slider](https://github.com/ganlanyuan/tiny-slider)
* Tweak 	- Combine public facing custom scripts to one file
* Fixed 	- Fixed images overlapping issue


#### version 1.2.2 - 2017-06-22
* Updated - Removed jQuery dependency for isotope and rewrite with vanilla JS.

#### version 1.2.1 - 2017-03-27
* Added 	- New Filterable Portfolio Widget to add portfolio at widget. Especially helpful for page builder that use widget like "Page Builder by SiteOrigin" or "Elementor Page Builder".
* Added 	- Added archive, taxonomy, and single portfolio template for [Shapla](https://wordpress.org/themes/shapla/) Theme.

#### version 1.2.0 - 2017-03-21
* Added 	- Added option to choose filter script from Shuffle or Isotope

#### version 1.1.2 - 2017-03-20
* Fixed 	- Fixed “headers already sent” notice on plugin activation
* Added 	- Added portfolio_skill taxonomy
* Added 	- Added options to change meta label on portfolio single page

#### version 1.1.1 - 2017-03-13
* Updated 	- To make portfolio_cat taxonomy hierarchical like categories
* Added 	- Added 'custom-fields' support on portfolio post type
* Added 	- Added option to customize shortcode from theme using 'filterable_portfolio.php' file
* Added 	- Added option to set order, orderby and posts per page

#### version 1.1.0
* Updated 	- Upgraded shuffle javaScript to version 4.0.2
* Removed 	- prettyPhoto, fontawesome and modernizr
* Added 	- Added Project Images, Client Name, Project Date and Project URL.
* Added 	- Setting page with many sitting options.
* Added 	- Two Portfolio Theme.
* Added 	- Single page template.
* Added 	- Slide of images on single page.
* Added 	- Related Projects on single page.

#### version 1.0.1
* Added support for comment on portfolio single post.

#### version 1.0.0
* Initial release