<?php

declare(strict_types=1);

/**
 * File: View.php
 *
 * @author Bartosz Kubicki b.w.kubicki@gmail.com>
 * Github: https://github.com/bartoszkubicki
 */

namespace BKubicki\ConfigurableProductAttributeHandles\Plugin\Catalog\Helper\Product;

use Magento\Catalog\Helper\Product\View as MagentoView;
use Magento\Catalog\Model\Product;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\DataObject;
use Magento\Framework\View\Result\Page as ResultPage;

class View
{
    /**
     * @param MagentoView $subject
     * @param ResultPage $resultPage
     * @param Product $product
     * @param DataObject|null $params
     * @return null
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD.LongVariable)
     * @codeCoverageIgnore
     */
    public function beforeInitProductLayout(
        MagentoView $subject,
        ResultPage $resultPage,
        Product $product,
        ?DataObject $params = null
    ) {
        if ($product->getTypeId() === Configurable::TYPE_CODE) {
            $type = $product->getTypeInstance();
            /** @var $type Configurable */
            $superAttributeCollection = $type->getConfigurableAttributeCollection($product);
            $layoutUpdate = $resultPage->getLayout()->getUpdate();

            $superAttributesCodes = [];

            foreach ($superAttributeCollection as $attribute) {
                /** @var $attribute Configurable\Attribute */
                $superAttributesCodes[] = $attribute->getProductAttribute()->getAttributeCode();
            }

            $resultingHandleSuffix = implode('_', $superAttributesCodes);
            $layoutUpdate->addHandle(
                sprintf('catalog_product_view_type_configurable_super_%s', $resultingHandleSuffix)
            );
        }

        return null;
    }
}
