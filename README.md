#Joomla! Bootstrap Template Framework.

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


