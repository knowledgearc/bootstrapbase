#Joomla! Bootstrap Template Framework.

BootstrapBase provides a base for developers who need a fast, light-weight responsive Joomla!-based template. BoostrapBase incorporates the latest JQuery and Bootstrap features so developers can create their templates on the latest web technologies.

## Installation

1. Install BootstrapBase template and JLess system plugin using the extension manager,
2. Browse to Template Manager and set default site template to BootstrapBase. Alternatively, click on BootstrapBase and use the Menus Assignment to assign the BootstrapBase template to specific pages,
3. Browse to Plugin Manager, locate System - JLess and select it,
4. Select the appropriate template from the template dropdown (this should be BootstrapBase or a derivative) then select Enable. Save the plugin.

Load your web site home page (if BootstrapBase is the default) or browse to a menu item which is assigned BootstrapBase as its style. When the page loads it will create the appropriate CSS files using the BootstrapBase LESS styles.

## JLess

JLess is a Joomla! system plugin which compiles LESS files on-the-fly. It can be configured to only compile when LESS files have changed and can be configured to use either server or client-side compilation.

## Templates
All templates are located in the /templates directory.

### BootstrapBase Template
The BootstrapBase template provides a simple Joomla! template which has been completely "boostrapified". It includes all the files required for responsive web design using Bootstrap (http://www.getbootstrap.com).

### Bootstrap Integration

#### Javascript
The Bootstrap framework includes a single bootstrap.js file designed to combined all Bootstrap Javascript functionality (tab.js, scrollspy.js, tooltip.js, dropdown.js, etc).

#### LESS
It is strongly recommended all Bootstrap Template Framework templates use LESS to manage and maintain Joomla!'s CSS. LESS improves CSS readability, code re-use and allows for the use of 3rd party tools to cutomize the template via easy-to-use UI tools.

Using LESS also results in a single CSS file being created which reduces traffic to and from the site.

### HTML5 Best Practices

There are a number of new elements in HTML5 which provide a great way to mark up a web page in a more semantic way. Elements such as section, header, article, aside and footer provide a lot of power over how you structure your web pages and ensure your content is easier to maintain and that search engines can more readily index your web site's information. However, it is important to not overuse these new elements, instead using them only when needed.

Below is a quick guide to when and where you should use HTML elements as well as when you should use existing elements over the new HTML5 semantic elements. The guide also provides information about how to incorporate this into your Joomla site, either through template overrides or LESS/CSS.

#### Headings

Headings provide a quick summary or grab for a page's content. Certain pages can have multiple headings with each heading having a certain amount of importance or significance. As the heading becomes more specific it should decrease in its level.

##### h1

- Use sparingly,
- If the page is an Article, use h1 for the Article Title. This may identified by the "Page Heading" field of the "Single Article" type's menu item or by the Article's Title,
- Multiple h1's can be used within the same page but only when there are multiple sections of entire content or when a section contains a number of items with h2 titles.

##### h2

- A sub-heading of h1,
- Use for articles if there are multiple article summaries on a single page (E.g. a list of blog posts).

## Joomla! Integration

### Modules

#### Navbar Menu

BootstrapBase provides functionality to convert the main menu (or any menu) into a Bootstrap navbar.

To convert a menu module to a navbar:

- Select the menu module from Module Manager,
- Navigate to Advanced Settings,
- Specify the appropriate navbar CSS class(es) to the Menu Class Suffix setting. See the [Bootstrap Navbar section](http://getbootstrap.com/components/#navbar) for more information and available CSS classes.

Ideally you will want to create your own semantic LESS classes and apply appropriate Bootstrap classes there.

### 3rd Party LESS Addons

There are now a number of CSS developers such as Font Awesome who are providing LESS-based distributions of their libraries. BootstrapBase provides a special area for these LESS files under BootstrapBase/less/3rdparty.

To add a 3rd party LESS library:

- Create a directory in BootstrapBase/less/3rdparty to hold the LESS files (E.g. BootstrapBase/less/3rdparty/fontawesome),
- Copy the LESS files into this new directory,
- Include the LESS files in the template.less file so that they are included during LESS compilation (check for a LESS file which includes all other LESS files in the 3rd party library ; this "global" include is part of almost all distributions). Use the @import tag to include the appropriate file(s).

If the 3rd party library includes icons, images or fonts, make sure you copy them to the appropriate location in the BootstrapBase template (for example, fonts are placed in the /fonts directory). However, please note that this will break almost all includes within the LESS files so you will need to override the importing CSS classes using one of the override less files. For your convenience, font locations can be overridden in overrides/fonts.less. Check this file for more information on how to correctly reference other files from CSS.