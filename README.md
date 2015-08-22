#Joomla! Bootstrap Template Framework.

## Documentation

User documentation is available at https://www.gitbook.com/book/knowledgearc/bootstrapbase/.

## Build the template with Phing

You will need phing and composer installed to make these next steps work (installing these PHP programs and using them are not covered here).

Before you run phing you will need to copy the build.properties.example file to build.properties and then edit the build.properties:

- <b>src</b>; In most situations this it should remain unchanged,
- <b>path.joomla</b>; The location of a development Joomla installation. This path will be used by phing's "install" target to quickly deploy a copy of the template to a valid joomla site,
- <b>composer.phar</b>; The location of the composer executable. Composer is used to download BootstrapBase from github.
- <b>template.name</b>; The name of your template. All language files, language constants and installation configuration will be renamed template.name,
- <b>template.description</b>; The template's description. This will be used by the template's installation manifest to describe the template within the Joomla administration.

Once edited, you will be able to package the template for installation via the Joomla Extension Manager, or, if your path.joomla is pointing to a valid and accessible local Joomla installation, deploy it on-the-fly.

Run phing help for all available targets.
