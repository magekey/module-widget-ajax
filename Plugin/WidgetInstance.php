<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\WidgetAjax\Plugin;

use MageKey\WidgetAjax\Block\Widget\AjaxContainer;

class WidgetInstance
{
    const WIDGET_AJAX_BLOCK = AjaxContainer::class;

    /**
     * @var \Magento\Framework\View\FileSystem
     */
    protected $_viewFileSystem;

    /**
     * @var \Magento\Framework\Math\Random
     */
    protected $mathRandom;

    /**
     * @param \Magento\Framework\View\FileSystem $viewFileSystem
     * @param \Magento\Framework\Math\Random $mathRandom
     */
    public function __construct(
        \Magento\Framework\View\FileSystem $viewFileSystem,
        \Magento\Framework\Math\Random $mathRandom
    ) {
        $this->_viewFileSystem = $viewFileSystem;
        $this->mathRandom = $mathRandom;
    }

    /**
     * Around dispatch
     *
     * @param \Magento\Widget\Model\Widget\Instance $subject
     * @param callable $proceed
     * @param string $container
     * @param string $templatePath
     * @return string
     */
    public function aroundGenerateLayoutUpdateXml(
        \Magento\Widget\Model\Widget\Instance $subject,
        callable $proceed,
        $container,
        $templatePath
    ) {
        if ($subject->getIsAjax()) {
            return $this->_generateLayoutUpdateXmlAjax($subject, $container, $templatePath);
        } else {
            return $proceed($container, $templatePath);
        }
    }

    /**
     * Generate layout for ajax
     *
     * @param \Magento\Widget\Model\Widget\Instance $subject
     * @param string $container
     * @param string $templatePath
     * @return string
     */
    protected function _generateLayoutUpdateXmlAjax(
        \Magento\Widget\Model\Widget\Instance $subject,
        $container,
        $templatePath
    ) {
        $templateFilename = $this->_viewFileSystem->getTemplateFileName(
            $templatePath,
            [
                'area' => $subject->getArea(),
                'themeId' => $subject->getThemeId(),
                'module' => \Magento\Framework\View\Element\AbstractBlock::extractModuleName($subject->getType())
            ]
        );
        if (!$subject->getId() && !$subject->isCompleteToCreate() || $templatePath && !is_readable($templateFilename)) {
            return '';
        }

        $arguments = [
            'template' => $templatePath,
        ];

        $xml = '<body><referenceContainer name="' . $container . '">';
        $hash = $this->mathRandom->getUniqueHash();
        $xml .= '<block class="' . static::WIDGET_AJAX_BLOCK . '" name="' . $hash . '">';
        $xml .= '<arguments>';
        $xml .= '<argument name="hash" xsi:type="string">' . $hash . '</argument>';
        $xml .= '<argument name="widget_id" xsi:type="string">' . $subject->getId() . '</argument>';
        $xml .= '<argument name="widget_parameters" xsi:type="array">';
        foreach ($arguments as $key => $value) {
            $xml .= '<item name="' . $key . '" xsi:type="string">' . $value . '</item>';
        }
        $xml .= '</argument>';
        $xml .= '</arguments>';
        $xml .= '</block></referenceContainer></body>';

        return $xml;
    }
}
