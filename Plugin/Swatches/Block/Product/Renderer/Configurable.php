<?php

declare(strict_types=1);

/**
 * File: Configurable.php
 *
 * @author Bartosz Kubicki b.w.kubicki@gmail.com>
 * Github: https://github.com/bartoszkubicki
 */

namespace BKubicki\ConfigurableProductAttributeHandles\Plugin\Swatches\Block\Product\Renderer;

use BKubicki\ConfigurableProductAttributeHandles\Plugin\MutatedTypeBuilder;
use Magento\Swatches\Block\Product\Renderer\Configurable as MagentoConfigurable;

class Configurable
{
    use MutatedTypeBuilder;

    /**
     * @var string
     */
    private const CONFIGURABLE_SUPER_TEMPLATES = 'configurable_super_templates';

    /**
     * @param MagentoConfigurable $subject
     * @param string $template
     * @return string[]|null
     * @SuppressWarnings(PHPMD.UnusedformalParameter)
     */
    public function beforeSetTemplate(MagentoConfigurable $subject, string $template): ?array
    {
        $superTemplates = $subject->getData(self::CONFIGURABLE_SUPER_TEMPLATES);

        if ($superTemplates) {
            $mutatedType = $this->buildMutatedType($subject->getProduct());
            $template = $this->pickTemplate($superTemplates, $mutatedType);
            if (is_string($template)) {
                return [$template];
            }
        }

        return null;
    }

    /**
     * @param array $superTemplates
     * @param string $mutatedType
     * @return string|null
     */
    private function pickTemplate(array $superTemplates, string $mutatedType): ?string
    {
        if (!empty($superTemplates[$mutatedType])) {
            return (string) $superTemplates[$mutatedType];
        }

        return null;
    }
}
