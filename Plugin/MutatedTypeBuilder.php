<?php

declare(strict_types=1);

/**
 * File: MutatedTypeBuilder.php
 *
 * @author Bartosz Kubicki b.w.kubicki@gmail.com>
 * Github: https://github.com/bartoszkubicki
 */

namespace BKubicki\ConfigurableProductAttributeHandles\Plugin;

use Magento\Catalog\Model\Product;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;

trait MutatedTypeBuilder
{
    /**
     * @param Product $product
     * @return string
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function buildMutatedType(Product $product): string
    {
        $typeInstance = $product->getTypeInstance();
        /** @var $typeInstance Configurable */
        $supperAttributeCollection = $typeInstance->getConfigurableAttributeCollection($product);
        $superAttributesCodes = [];

        foreach ($supperAttributeCollection as $attribute) {
            /** @var $attribute Configurable\Attribute */
            $superAttributesCodes[] = $attribute->getProductAttribute()->getAttributeCode();
        }

        $resultingHandleSuffix = implode('_', $superAttributesCodes);
        return sprintf('configurable_super_%s', $resultingHandleSuffix);
    }
}
