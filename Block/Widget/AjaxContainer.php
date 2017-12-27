<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\WidgetAjax\Block\Widget;

class AjaxContainer extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    /**
     * string
     */
    protected $_template = 'MageKey_WidgetAjax::widget/ajax_container.phtml';

    /**
     * Retrieve load widget url
     *
     * @return string
     */
    public function getLoadUrl()
    {
        return $this->getUrl('mgk_widget/load/ajax');
    }

    /**
     * Retrieve widget hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->getData('hash');
    }

    /**
     * Retrieve widget parameters
     *
     * @return array
     */
    public function getWidgetParameters()
    {
        return $this->getData('widget_parameters') ?: [];
    }
}
