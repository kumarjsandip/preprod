=== Portfolio for Elementor ===
Contributors: wppug, dotrex
Donate link: 
Tags: portfolio, filterable portfolio, portfolio gallery, responsive portfolio, gallery, elementor
Requires at least: 4.0
Tested up to: 4.9
Stable tag: 1.0.1
License: GPLv2

This plugin extend Elementor by adding the Portfolio functionality for free!



= Plugin Demo  =
[Click here to see the plugin demo](https://wppug.com/elementor-demos/portfolio-for-elementor/)

**PS: This plugin is also available as part of our main plugin: [Power-Ups for Elementor](https://wppug.com/elementor-demos/power-ups-for-elementor/).**

= Overview =

This plugin xtend Elementor by adding the Portfolio functionality for free! It allows you to create a creative portfolio to show your projects using the Drag&Drop interface of Elementor. 

It's very useful to create a portfolio of websites or web development projects and it is specially made for creative professionals such as designers, web developers and photographers. 

You can also enable the filterable portfolio option, separating your projects into categories that can be filtered.



= Features =
* Filterable Portfolio
* You can display only a Custom Portfolio Category if you want
* You can show the projects on a modal or on a single page
* Compatible with Elementor and King Composer Page Builders
* Masonry/Boxed Grid
* 2,3 or 4 collumns


= Installation Instructions =
1. Upload `elementor-portfolio` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Click on the "Portfolio" link from the main menu
4. Click on "Add New Item", create your first portfolio content and publish. Create as many posts you want.
5. Click on "Creative Portfolio" to set your color scheme.

= How to display the filterable portfolio grid =

The widget will be available in Elementor items. Just drag it to your website and select the customization options :)

You can also use dthe shortcode to display the portfolio grid on a page/post:

[elemenfolio]

You can customize it using these options:

* **postsperpage:** Set a number of posts to show (eg: postsperpage="12").
* **showfilter**: Show the category filter on the top of the grid. Options: yes/no. (eg: showfilter="yes").
* **style**: Set the grid style of the portfolio. Options: masonry/box. (eg: style="box").
* **linkto**: Set the link type of the portfolio item. If is set to image, it will open the Featured Image on a lightbox. Options: image/project. (eg: linkto="image").
* **columns**: Set the columns per row of the portfolio grid. Options: 2/3/4. (eg: columns="4").
* **margin**: Choose if you want a margin between the items or no. Options: yes/no. (eg: margin="no").

**Example of a complete shortcode:**

[elemenfolio postsperpage="12" type="no" showfilter="yes" style="masonry" linkto="image" columns="4" margin="no"]

**Example of a complete shortcode without the set properties:**
[elemenfolio postsperpage="" showfilter="" style="" linkto="" columns="" margin=""]

== Screenshots ==

1. **Portfolio Elementor Widget.** Showcase your work to your audience with a premium portfolio grid.


== Changelog ==
1.0.1 - Initial Release

