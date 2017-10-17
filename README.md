#Joomla! Bootstrap Template Framework.

## Documentation

User documentation is available at https://www.gitbook.com/book/knowledgearc/bootstrapbase/.

## Build the template with Phing

You will need phing and composer installed to make these next steps work (installing these PHP programs and using them are not covered here).

Before you run phing you will need to copy the build.properties.example file to build.properties and then edit the build.properties:

- <b>src</b>; In most situations this it should remain unchanged,
- <b>path.joomla</b>; The location of a development Joomla installation. This path will be used by phing's "install" target to quickly deploy a copy of the template to a valid joomla site,
- <b>composer.phar</b>; The location of the composer executable. Composer is used to download BootstrapBase from github.

Once edited, you will be able to package the template for installation via the Joomla Extension Manager, or, if your path.joomla is pointing to a valid and accessible local Joomla installation, deploy it on-the-fly.

Run phing help for all available targets.

## Upgrading Bootstrap

- Download latest Source Code zip from http://getbootstrap.com/getting-started/,

- Extract dist/fonts to templates/bootstrapbase/fonts

- Extract dist/js/bootstrap*.js to templates/bootstrap/js/jui

- Extract less/* to templates/bootstrapbase/less/bootstrap

For Sass:

- Download latest Sass tar archive from from http://getbootstrap.com/getting-started/,

- Extract /bootstrap-sass-x.y.z/assets/stylesheets/ to templates/bootstrapbase/scss

## Upgrading JQuery

- Download the Bootstrap recommended version from https://jquery.com/download/,

- Save the compressed and uncompressed files to templates/bootstrap/js/jui,

- Remove the old jquery js files and rename the saved files to jquery.js and jquery.min.js.
