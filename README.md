# BKubicki Configurable Product Attribute Handles #

## Overview ##

Module adds custom frontend layout handles\extension points for easier frontend customization of configurable product.

### Features ###
* product card - handles basing on product super attributes, pattern: `catalog_product_view_type_configurable_super_{{attribute_code}}`
* product card - specific templates for configurable options block. Block `vendor/magento/module-swatches/Block/Product/Renderer/Configurable.php:443` allows
  only two templates (one from `Magento_Swatches` and the default one from `Magento_ConfigurableProduct`), ignoring any layout instructions.
  By adding some data in layout, we can change template for custom one, based on product super attributes:
```
<referenceBlock name="product.info.options.swatches">
    <arguments>
        <argument name="configurable_super_templates" xsi:type="array">
            <item name="configurable_super_colour" xsi:type="string">Vendor_Module::product/view/type/options/configurable-super-colour.phtml</item>
        </argument>
    </arguments>
</referenceBlock>
```
Such an instruction can be combined with product super attributes handles, but it doesn't have to be.
* product listing - possibility to define additional renderers for configurable super products, pattern: `configurable_super_{{attribute_code}}'`. Usage example:
```
<block name="category.product.type.details.renderers.configurable_super_example_attribute" as="configurable_super_example_attribute"
       template="Vendor_Module::product/listing/super-example-attribute-renderer.phtml"/>
```

Don't forget to add alias for renderer, as it is used for renderer picking.

Please see [attached module](examples/BKubicki_ConfigurableProductAttributeHandlesExample.zip) to fully understand possible usage of these features.
## Prerequisites
* PHP 7.3|7.4


## Installation ###

To install the extension use the following commands:

```bash
 composer require bkubicki/module-configurable-product-attribute-handles
 ```


## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/bartoszkubicki/magento2-configurable-product-attribute-handles/tags).


## Changelog

See changelog [here](CHANGELOG.md).


## Authors

* [Bartosz Kubicki](https://github.com/bartoszkubicki)


## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE.md) file for details.