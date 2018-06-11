# Products module

A PrestaShop module to demonstrate all the available customizations in Product pages.

## Screenshots

![product_catalog_page](https://user-images.githubusercontent.com/1247388/41238053-5811dace-6d95-11e8-89f9-a85698a93422.png)

## Requirements

You need a Shop with PrestaShop 1.7.4+ and PHP 7.1 at least.

## Installation

Get the module here and move it into the `modules` folder of your Shop.
Then install it using the command line:

``
php bin/console prestashop:module install products
``

Or using the Back Office.

## Features demonstrated in the module

### Catalog of products page.

We'll see how you can override the template, to re-order columns for instance.

We'll see also how to manage the available fields and following data to make it available
in templates. We'll add a new field to product page called "alternative_description" and make it
available in the catalog view.

### Product Page

We need to make the new field "alternative_description" available in Product Page.
We'll discover all the templates of Product page and how to choice which one should
ne overridden. We also try the Modules tabs feature, available for people
who need complex forms to be added to product page. 