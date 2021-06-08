<?php

declare(strict_types=1);

/**
 * File: ListProduct.php
 *
 * @author Bartosz Kubicki b.w.kubicki@gmail.com>
 * Github: https://github.com/bartoszkubicki
 */

namespace BKubicki\ConfigurableProductAttributeHandles\Plugin\Catalog\Block\Product;

use BKubicki\ConfigurableProductAttributeHandles\Plugin\MutatedTypeBuilder;
use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Catalog\Model\Product;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\RendererList;
use RuntimeException;

class ListProduct
{
    use MutatedTypeBuilder;

    /**
     * @var string
     */
    private const CONFIGURABLE_RENDERER_CODE = 'configurable';

    /**
     * @var string
     */
    private const DEFAULT_RENDERER_CODE = 'default';

    /**
     * @var Product
     */
    private $currentProduct;

    /**
     * @var RendererList
     */
    private $rendererListBlock;

    /**
     * @param AbstractProduct $subject
     * @param Product $product
     * @return null
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @codeCoverageIgnore
     */
    public function beforeGetProductDetailsHtml(AbstractProduct $subject, Product $product)
    {
        $this->currentProduct = $product;
        return null;
    }

    /**
     * @param AbstractProduct $subject
     * @param bool|AbstractBlock|null
     * @return AbstractBlock|null
     * @throws LocalizedException
     * @codeCoverageIgnore
     */
    public function afterGetDetailsRenderer(
        AbstractProduct $subject,
        $result
    ) {
        if (!$this->isConfigurableProduct()) {
            return $result;
        }

        $mutatedType = $this->buildMutatedType($this->currentProduct);
        $rendererList = $this->getDetailsRendererList($subject);

        if ($rendererList) {
            return $this->pickRenderer($rendererList, $mutatedType);
        }

        return null;
    }

    /**
     * @return bool
     */
    private function isConfigurableProduct(): bool
    {
        return $this->currentProduct instanceof Product
            && (string) $this->currentProduct->getTypeId() === Configurable::TYPE_CODE;
    }

    /**
     * @param AbstractProduct $subject
     * @return RendererList|null
     * @throws LocalizedException
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    private function getDetailsRendererList(AbstractProduct $subject): ?RendererList
    {
        $detailsRendererListName = $subject->getDetailsRendererListName();

        if (empty($this->rendererListBlock)) {
            $rendererListBlock = $detailsRendererListName
                ? $subject->getLayout()->getBlock($detailsRendererListName)
                : $subject->getChildBlock('details.renderers');

            $rendererListBlock instanceof RendererList
                ? $this->rendererListBlock = $rendererListBlock
                : $this->rendererListBlock = null;
        }

        return $this->rendererListBlock;
    }

    /**
     * @param RendererList $rendererList
     * @return AbstractBlock|null
     */
    private function getDefaultRenderer(RendererList $rendererList): ?AbstractBlock
    {
        return $rendererList->getRenderer(self::DEFAULT_RENDERER_CODE);
    }

    /**
     * @param RendererList $rendererList
     * @param string $mutatedType
     * @return AbstractBlock|null
     */
    private function pickRenderer(RendererList $rendererList, string $mutatedType): ?AbstractBlock
    {
        $defaultRender = $this->getDefaultRenderer($rendererList);
        try {
            $pickedRenderer = $rendererList->getRenderer($mutatedType, self::CONFIGURABLE_RENDERER_CODE);
            return $pickedRenderer instanceof AbstractBlock
                ? $pickedRenderer
                : $defaultRender;
        } catch (RuntimeException $exception) {
            return $defaultRender;
        }
    }
}
