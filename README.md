# Inchoo SEO Manager

## Overview
The Inchoo SEO Manager is a powerful Magento 2 extension that incorporates a wide range of SEO tools designed to enhance your website’s search engine optimization. It provides various features to optimize your store, making it easier to improve rankings and visibility in search results.

Learn more at: https://inchoo.net/online-marketing/introducing-inchoo-seo-manager/

## Installation
```sh
# Install composer package
composer require inchoo/seo-manager

# Install Magento 2 module
php bin/magento setup:upgrade --keep-generated

# Generates DI configuration
php bin/magento setup:di:compile

# Deploy static view files
php bin/magento setup:static-content:deploy

# Flush cache storage
php bin/magento cache:flush
```

## Usage

### System Configuration
> Stores > Settings > Configuration > Catalog > Catalog > Inchoo SEO Manager

| Field                                      | Description                                                                                                                                                                                            |
|--------------------------------------------|--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| Enabled                                    | Enable or disable module functionalities.                                                                                                                                                              |
| Use Canonical Link Meta Tag For Categories | Enable or disable the category page canonical URL.                                                                                                                                                     |
| Use Canonical Link Meta Tag For Products   | Enable or disable the product page canonical URL.                                                                                                                                                      |
| Use Canonical Link Meta Tag For CMS Pages  | Enable or disable the CMS page canonical URL.                                                                                                                                                          |
| Filter Canonical Link Query String         | Enable or disable removing not allowed parameters from the canonical URL query string.                                                                                                                 |
| Allowed Canonical Link Query Strings       | Allowed parameters in the canonical URL query string.                                                                                                                                                  |
| Non-Default Toolbar Meta Robots            | Apply the selected meta robots tag to the category page if non-default toolbar query string parameters are used (`product_list_order`, `product_list_dir`, `product_list_mode`, `product_list_limit`). |
| Catalog Search Meta Robots                 | Apply the selected meta robots tag to the search results page.                                                                                                                                         |
| Category Meta Rules                        | Enable or disable the Category Meta Rules.                                                                                                                                                             |
| Category Meta Rules Sitemap                | Enable or disable the Category Meta Rules sitemap items.                                                                                                                                               |
| Excluded Attributes                        | Excluded attributes are ignored by the Category Meta Rules.                                                                                                                                            |
| Product Meta Rules                         | Enable or disable the Product Meta Rules.                                                                                                                                                              |

> Stores > Settings > Configuration > Catalog > XML Sitemap > Category Meta Options

| Field     | Description                         |
|-----------|-------------------------------------|
| Frequency |                                     |
| Priority  | Valid values range from 0.0 to 1.0. |

### Category Meta Rules
Category Meta Rules can be used to modify the metadata of category pages based on a combination of the store, category, and applied attribute filters.

> Marketing > Inchoo SEO Manager > Category Meta Rules

#### Category Meta Rule Form
| Field                                    | Description                                                                                                                                                                           |
|------------------------------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| Enabled                                  | Enable or disable the Category Meta Rule.                                                                                                                                             |
| Store View                               | Apply the Category Meta Rule only on the selected store view.                                                                                                                         |
| Category                                 | Apply the Category Meta Rule only to the selected category.                                                                                                                           |
| Can Be A Fallback                        | Allow the Category Meta Rule to serve as a fallback for child categories.                                                                                                             |
| Attributes                               | Apply the Category Meta Rule if the category is filtered by the selected attributes or options.                                                                                       |
| Allow Additional Attributes              | Allow a rule to be matched even if additional, non-excluded attributes are used in the layered navigation filter.                                                                     |
| Add To Sitemap                           | Include the Category Meta Rule in the sitemap. Only rules with a selected category and a single attribute filter can be included in the sitemap.                                      |
| Meta Title                               | Modify the meta title tag of the category page. Placeholders can be used.                                                                                                             |
| Meta Description                         | Modify the meta description tag of the category page. Placeholders can be used.                                                                                                       |
| Meta Keywords                            | Modify the meta keywords tag of the category page. Placeholders can be used.                                                                                                          |
| Canonical URL                            | Modify the canonical URL of the category page. A canonical URL can be entered without the base URL. If the URL contains query parameters, they will be included in the canonical URL. |
| Use Rule Attributes in the Canonical URL | Create a canonical URL using rule attributes. This works only if a custom "Canonical URL" is not set.                                                                                 |
| Meta Robots                              | Modify the meta robots tag of the category page.                                                                                                                                      |
| Page H1 Title                            | Modify the category page title (category name). Placeholders can be used.                                                                                                             |
| Description                              | Modify the category page description (category description). Placeholders can be used.                                                                                                |

#### Category Meta Rules Priority Weight
Category Meta rules matched by priority, from highest to lowest:

- attribute and option + category (store)
- attribute and option + category (default store)
- attribute + category (store)
- attribute + category (default store)
- attribute and option + category + additional attributes (store)
- attribute and option + category + additional attributes (default store)
- attribute + category + additional attributes (store)
- attribute + category + additional attributes (default store)
- category (store)
- category (default store)
- attribute and option (store)
- attribute and option (default store)
- attribute (store)
- attribute (default store)
- attribute and option + additional attributes (store)
- attribute and option + additional attributes (default store)
- attribute + additional attributes (store)
- attribute + additional attributes (default store)
- attribute and option + parent category (store)
- attribute and option + parent category (default store)
- attribute + parent category (store)
- attribute + parent category (default store)
- attribute and option + parent category + additional attributes (store)
- attribute and option + parent category + additional attributes (default store)
- attribute + parent category + additional attributes (store)
- attribute + parent category + additional attributes (default store)

### Product Meta Rules
Product Meta Rules can be used to modify the metadata of product pages based on a combination of the store, category, product type, and assigned attribute options.

> Marketing > Inchoo SEO Manager > Product Meta Rules

#### Product Meta Rule Form
| Field            | Description                                                                                              |
|------------------|----------------------------------------------------------------------------------------------------------|
| Enabled          | Enable or disable the Product Meta Rule.                                                                 |
| Store View       | Apply the Product Meta Rule only on the selected store view.                                             |
| Category         | Apply the Product Meta Rule only to products in the selected category.                                   |
| Product Type     | Apply the Product Meta Rule only to selected product types.                                              |
| Attributes       | Apply the Product Meta Rule only to products that have the selected attribute options assigned.          |
| Priority         | Used to prioritize meta rules with the same priority weight. A lower number indicates a higher priority. |
| Meta Title       | Modify the meta title tag of the product page. Placeholders can be used.                                 |
| Meta Description | Modify the meta description tag of the product page. Placeholders can be used.                           |
| Meta Keywords    | Modify the meta keywords tag of the product page. Placeholders can be used.                              |
| Meta Robots      | Modify the meta robots tag of the product page.                                                          |
| Page H1 Title    | Modify the product page title (product name). Placeholders can be used.                                  |

#### Product Meta Rules Priority Weight
Product Meta rules matched by priority, from highest to lowest:

- category + product type + attribute (store)
- category + product type + attribute (default store)
- category + product type (store)
- category + product type (default store)
- category + attribute (store)
- category + attribute (default store)
- category match (store)
- category match (default store)
- product type + attribute (store)
- product type + attribute (default store)
- product type (store)
- product type (default store)
- attribute (store)
- attribute (default store)

### Placeholders
Placeholders can be used in form fields to generate generic metadata.

#### Syntax
- `{placeholder}` - A **placeholder** string with curly brackets will be replaced with an arbitrary value.
- `[{placeholder}]` - The **placeholder** inside the square brackets is optional and will only be used if there is value for it. If more than one placeholder is in square brackets, they must all have a value.

#### Category Meta Rules Placeholders
- `{category}` | `{name}` - The name of the current category.
- `{parent_category}` | `{parent_name}` - The name of the parent category of the current category (root category excluded).
- `{parent_category_root}` | `{parent_name_root}` - The name of the parent category of the current category (root category included).
- `{grandparent_category}` | `{grandparent_name}` - The name of the grandparent category of the current category (root category excluded).
- `{grandparent_category_root}` | `{grandparent_name_root}` - The name of the grandparent category of the current category (root category included).
- `{grandgrandparent_name}` | `{...grandparent_name}` - The name of the great-grandparent category of the current category. Every `grand` is one level up. Can be used with `_root` suffix.
- `{attribute_code}` - The label of any filtered product attribute.
- `{store_view}` - The name of the current category store view.
- `{store}` - The name of the current category store (group).
- `{website}` - The name of the current category website.

Example: `Buy [{attribute_code_1} ][ {attribute_code_2} ]{category} at {website}`

#### Product Meta Rules Placeholders
- `{attribute_code}` - The label of any loaded product attribute.
- `{category}` - The name of the current category.
- `{store_view}` - The name of the current product store view.
- `{store}` - The name of the current product store (group).
- `{website}` - The name of the current product website.

Example: `Buy {name} at {website}`

### Custom Meta Robots
Custom meta robots can be used for full control over the meta robots tag by setting it based on the URL path.

> Marketing > Inchoo SEO Manager > Custom Meta Robots

| Field       | Description                                                                                                                                 |
|-------------|---------------------------------------------------------------------------------------------------------------------------------------------|
| Enabled     | Enable or disable the Custom Meta Robots.                                                                                                   |
| Store View  | Apply the Custom Meta Robots only on the selected store view.                                                                               |
| URL Path    | Use wildcards (`*`) to set meta robots. For example: `*/customer/*` will apply meta robots to URL paths that contain the word **customer**. |
| Meta Robots | Modify the meta robots tag of the matched page.                                                                                             |
| Priority    | Used to prioritize meta robots. A lower number indicates higher priority (it can be a negative value).                                      |

### Custom Canonicals
Set custom canonical URL by URL path.

> Marketing > Inchoo SEO Manager > Custom Canonicals

| Field            | Description                                                 |
|------------------|-------------------------------------------------------------|
| Enabled          | Enable or disable the Custom Canonical.                     |
| Store View       | Apply the Custom Canonical only on the selected store view. |
| Request URL Path | Example: `contact/`                                         |
| Target URL Path  | Example: `contact`                                          |

### Catalog Product Form
Additional SEO input fields on the Product edit form.

> Catalog > Products

| Field         | Description                                     |
|---------------|-------------------------------------------------|
| Meta Robots   | Modify the meta robots tag of the product page. |
| Page H1 Title | Modify the product page title (product name).   |

### Catalog Category Form
Additional SEO input fields on the Category edit form.

> Catalog > Categories

| Field         | Description                                      |
|---------------|--------------------------------------------------|
| Meta Robots   | Modify the meta robots tag of the category page. |
| Page H1 Title | Modify the category page title (category name).  |

### CMS Page Form
Additional SEO input fields on the CMS page edit form.

> Content > Elements > Pages

| Field         | Description                                                                                                                     |
|---------------|---------------------------------------------------------------------------------------------------------------------------------|
| Meta Robots   | Modify the meta robots tag of the CMS page.                                                                                     |
| Canonical Url | Modify the canonical URL of the CMS page. Absolute or relative URL can be used. If empty, the URL of the CMS page will be used. |

### Other
- The canonical URL is removed if the meta robots tag is set to `NOINDEX`.

## Issues
Report any issues [here](https://github.com/Inchoo/seo-manager/issues).

## License
This project is licensed under the [Open Software License (OSL 3.0)](https://opensource.org/licenses/osl-3.0.php) – Please see [LICENSE.txt](LICENSE.txt) for the full text of the OSL 3.0 license.

