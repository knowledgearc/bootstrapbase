#Joomla! Bootstrap Template Framework.

BootstrapBase provides a base for developers who need a fast, light-weight responsive Joomla!-based template. BoostrapBase incorporates the latest JQuery and Bootstrap features so developers can create their templates on the latest web technologies.

- [BoostrapBase Plugin](#bootstrapbase-plugin)
    - [LESS CSS](#less-css)
    - [Javascript](#javascript)
- [BoostrapBase Template](#bootstrapbase-template)
    - [Plugin Installation](#plugin-installation)
    - [Installing BootstrapBase](#installing-boostrapbase)
    - [Creating a Template using the BootstrapBase build script (Recommended)](#creating-a-template-using-bootstrap-build-script-recommended)
        - [The skel Directory](#the-skel-directory)
        - [Build the template with Phing](#build-the-template-with-phing)
        - [Things to Remember](#things-to-remember)
- [Joomla! Integration](#joomla-integration)
    - [Modules](#modules)
        - [Navbar Menu](#navbar-menu)
- [HTML5 Best Practices](#html5-best-practices)
    - [Headings](#headings)
        - [h1](#h1)
        - [h2](#h2)

## <a name="bootstrapbase-plugin"></a>BootstrapBase Plugin

The BootstrapBase plugin is a Joomla! system plugin which compiles LESS files and minifies Javascript on-the-fly.
build.xml
### <a name="less-css"></a>LESS CSS
It is strongly recommended all Bootstrap Template Framework templates use LESS to manage and maintain Joomla!'s CSS. LESS improves CSS readability, code re-use and allows for the use of 3rd party tools to cutomize the template via easy-to-use UI tools.

Using LESS also results in a single CSS file being created which reduces traffic to and from the site.

### <a name="javascript"></a>Javascript
The Bootstrap framework includes a single bootstrap.js file designed to combined all Bootstrap Javascript functionality (tab.js, scrollspy.js, tooltip.js, dropdown.js, etc). The BoostrapBase plugin will attempt to combine all Javscript files into a single, minified Javscript file for quicker loading although BootstrapBase can be configured to ignore certain Javscript files via the Plugin Manager.

The BoostrapBase plugin will load any Javascript included via the JDocument addScript and addScriptDeclaration methods into the bottom of the web page (before the closing body tag), so it is important to use this method to include Javascript within components, plugins, modules and template overrides.

## <a name="bootstrapbase-template"></a>BootstrapBase Template
The BootstrapBase template provides a simple Joomla! template which has been completely "boostrapified". It includes all the files required for responsive web design using Bootstrap (http://www.getbootstrap.com).

### <a name="plugin-installation"></a>Plugin Installation

1. Install BootstrapBase system plugin using the Extension Manager,
2. Browse to Plugin Manager, locate System - BoostrapBase and enable it.

There are additional BoostrapBase settings which will provide you with more control over what the plugin will do:

### <a name="installing-boostrapbase"></a>Installing BootstrapBase

1. Install BootstrapBase template using the Extension Manager,
2. Browse to Template Manager and set default site template to BootstrapBase. Alternatively, click on BootstrapBase and use the Menus Assignment to assign the BootstrapBase template to specific pages,

Load your web site home page (if BootstrapBase is the default) or browse to a menu item which is assigned BootstrapBase as its style. When the page loads it will create the appropriate CSS files using the BootstrapBase LESS styles.

You can use Joomla's Template Manager file browser/editor to make changes to the LESS files and, depending on how the BoostrapBase system plugin has been configured, it will re-compile your changes and add them to the template's CSS file when you reload a Joomla! web page.

Alternatively, you can create your LESS, Javscript and HTML overrides remotely and copy these to the BoostrapBase template directory, located in /path/to/joomla/templates/boostrapbase/.

### <a name="creating-a-template-using-bootstrap-build-script-recommended"></a>Creating a Template using the BootstrapBase build script (Recommended)

The recommended method of creating templates based on BootstrapBase is to use the phing build scripts included in the templates/skel directory.

To build a template based on BootstrapBase:

1. Get a copy of the [BootstrapBase skel directory](#the-skel-directory) which is located under /boostrapbase/templates/. Copy it into your working area,
1. Rename "skel" to the name you wish to use for your template. It is recommended the name you choose has no spaces or special characters expect "-" or "_",
1. Copy build.xml.dist to build.xml,
1. Copy build.properties.example to build.properties. [Edit the properties](#build-the-template-with-phing) to match your template and environment settings,
1. Run [phing package](#build-the-template-with-phing) to build a Joomla!-compatible template package.

You should now have an installable template in your working area's build directory. Use the Joomla! Extension Manager to install it. The BoostrapBase system plugin will also be available in case you have not installed it.

#### <a name="the-skel-directory"></a>The skel Directory

The "skel" directory will have a number of directories already set up:
<pre>
|-- skel
    |-- fonts
    |-- html
    |-- images
    |-- js
        |-- jui
    |-- less
        |-- overrides
</pre>

##### fonts

If you have any custom fonts you need to use, add them to this directory.

##### html

Use for Joomla-based HTML overrides. https://docs.joomla.org/Understanding_Output_Overrides covers this topic in-depth.

##### images

Place images associated with the template (for example arrow buttons or file type icons) in this directory. Images such as logos should go in the Joomla /images directory as these types of images may apply to multiple templates.

##### js/jui

Custom Javscript files associated with the template go here.

##### less/overrides

Use the less/overrides directory for overriding BoostrapBase less files.

##### less/3rdparty

One diretory that is not included but can be used for including 3rd party less libraries is the 3rdparty directory. Create a directory called 3rdparty under the less directory and drop your 3rdparty less files here. To complete the inclusion, override the BootstrapBase template.less file so your less directory looks like the following:
<pre>
|-- less
    |-- 3rdparty
    |-- overrides
    template.less
</pre>

In the template.less, import your new library before the /overrides/style.less import statement. For example, if you are importing the 3rd party less library TableAwesome which has a parent import file called tableawseome.less, your template.less file would look like:

<pre>
<code>/*!
 * Bootstrap v3.3.1
 */

// Core variables and mixins
@import "./bootstrap/bootstrap.less";	// The bootstrap css.

// 3rd party
@import "./3rdparty/font-awesome/font-awesome.less";   // font awesomeness
@import "./3rdparty/tableawesome/tableawesome.less";   // table awesomeness

// Overrides
@import "./overrides/style.less";</code>
</pre>

#### <a name="build-the-template-with-phing"></a>Build the template with Phing

You will need phing and composer installed to make these next steps work (installing these PHP programs and using them are not covered here).

Before you run phing you will need to copy the build.properties.example file to build.properties and then edit the build.properties:

- <b>src</b>; In most situations this it should remain unchanged,
- <b>path.joomla</b>; The location of a development Joomla installation. This path will be used by phing's "install" target to quickly deploy a copy of the template to a valid joomla site,
- <b>composer.phar</b>; The location of the composer executable. Composer is used to download BootstrapBase from github.
- <b>template.name</b>; The name of your template. All language files, language constants and installation configuration will be renamed template.name,
- <b>template.description</b>; The template's description. This will be used by the template's installation manifest to describe the template within the Joomla administration.

Once edited, you will be able to package the template for installation via the Joomla Extension Manager, or, if your path.joomla is pointing to a valid and accessible local Joomla installation, deploy it on-the-fly.

Run phing help for all available targets.

#### <a name="things-to-remember"></a>Things to Remember

1. It is important to remember that your customizations will override anything in BootstrapBase when using the build script. So if you create a file in js/jui called bootstrap.js, this will override the default bootstrap Javascript file,

1. Try not to override too many files. This will cause problems in the future when BootstrapBase is updated,

1. DO NOT EDIT index.php AND initialize.php. Only BootstrapBase's version should be used. If you can't do something with LESS and HTML Overrides then refactor your code.

## <a name="joomla-integration"></a>Joomla! Integration

### <a name="modules"></a>Modules

#### <a name="navbar-menu"></a>Navbar Menu

BootstrapBase provides functionality to convert the main menu (or any menu) into a Bootstrap navbar.

To convert a menu module to a navbar:

- Select the menu module from Module Manager,
- Navigate to Advanced Settings,
- Specify the appropriate navbar CSS class(es) to the Menu Class Suffix setting. See the [Bootstrap Navbar section](http://getbootstrap.com/components/#navbar) for more information and available CSS classes.

Ideally you will want to create your own semantic LESS classes and apply appropriate Bootstrap classes there.

## <a name="html5-best-practices"></a>HTML5 Best Practices

There are a number of new elements in HTML5 which provide a great way to mark up a web page in a more semantic way. Elements such as section, header, article, aside and footer provide a lot of power over how you structure your web pages and ensure your content is easier to maintain and that search engines can more readily index your web site's information. However, it is important to not overuse these new elements, instead using them only when needed.

Below is a quick guide to when and where you should use HTML elements as well as when you should use existing elements over the new HTML5 semantic elements. The guide also provides information about how to incorporate this into your Joomla site, either through template overrides or LESS/CSS.

### <a name="headings"></a>Headings

Headings provide a quick summary or grab for a page's content. Certain pages can have multiple headings with each heading having a certain amount of importance or significance. As the heading becomes more specific it should decrease in its level.

#### <a name="h1"></a>h1

- Use sparingly,
- If the page is an Article, use h1 for the Article Title. This may identified by the "Page Heading" field of the "Single Article" type's menu item or by the Article's Title,
- Multiple h1's can be used within the same page but only when there are multiple sections of entire content or when a section contains a number of items with h2 titles.

#### <a name="h2"></a>h2

- A sub-heading of h1,
- Use for articles if there are multiple article summaries on a single page (E.g. a list of blog posts).