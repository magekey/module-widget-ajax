<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\WidgetAjax\Model;

use Magento\Framework\Exception\LocalizedException;

class WidgetLoader
{
    /**
     * @var \Magento\Framework\Escaper
     */
    protected $_escaper;

    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $layout;

    /**
     * @var \Magento\Widget\Model\Widget\InstanceFactory
     */
    protected $widgetFactory;

    /**
     * @var \Magento\Widget\Helper\Conditions
     */
    protected $conditionsHelper;

    /**
     * @param \Magento\Framework\Escaper $escaper
     * @param \Magento\Framework\View\LayoutInterface $layout
     * @param \Magento\Widget\Model\Widget\InstanceFactory $widgetFactory
     * @param \Magento\Widget\Helper\Conditions $conditionsHelper
     */
    public function __construct(
        \Magento\Framework\Escaper $escaper,
        \Magento\Framework\View\LayoutInterface $layout,
        \Magento\Widget\Model\Widget\InstanceFactory $widgetFactory,
        \Magento\Widget\Helper\Conditions $conditionsHelper
    ) {
        $this->escaper = $escaper;
        $this->layout = $layout;
        $this->widgetFactory = $widgetFactory;
        $this->conditionsHelper = $conditionsHelper;
    }

    /**
     * Render widget content
     *
     * @param int $widgetId
     * @param array $parameters
     * @return string
     */
    public function render($widgetId, $parameters = [])
    {
        $widget = $this->widgetFactory->create();
        $widget->load($widgetId);
        if (!$widget->getId()) {
            throw new LocalizedException(
                __('Widget not found')
            );
        }
        $block = $this->generateBlock($widget, $parameters);
        return $block->toHtml();
    }

    /**
     * Generate widget block
     *
     * @param \Magento\Widget\Model\Widget\Instance $widget
     * @param array $parameters
     * @return string
     */
    public function generateBlock(\Magento\Widget\Model\Widget\Instance $widget, array $parameters = [])
    {
        $parameters = array_merge($widget->getWidgetParameters(), $parameters);
        $arguments = [];
        foreach ($parameters as $name => $value) {
            if ($name == 'conditions') {
                $name = 'conditions_encoded';
                $value = $this->conditionsHelper->encode($value);
            } elseif (is_array($value)) {
                $value = implode(',', $value);
            }
            if ($name && strlen((string)$value)) {
                $arguments[$name] = $this->escaper->escapeHtml(
                    $value
                );
            }
        }
        
        return $this->layout->createBlock(
            $widget->getType(),
            '',
            ['data' => $arguments]
        );
    }
}
