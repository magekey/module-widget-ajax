<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\WidgetAjax\Controller\Load;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;

use MageKey\WidgetAjax\Model\WidgetLoader;
use MageKey\WidgetAjax\Block\Widget\AjaxContainer;

class Ajax extends \Magento\Framework\App\Action\Action
{
    /**
     * @var WidgetLoader
     */
    protected $_widgetLoader;

    /**
     * @param Context $context
     * @param WidgetLoader $widgetLoader
     */
    public function __construct(
        Context $context,
        WidgetLoader $widgetLoader
    ) {
        parent::__construct($context);
        $this->_widgetLoader = $widgetLoader;
    }

    /**
     * @return void
     */
    public function execute()
    {
        try {
            $hash = $this->getRequest()->getParam('hash');
            if (!$hash) {
                throw new LocalizedException(
                    __('Widget not found')
                );
            }

            $this->_view->loadLayout();
            $ajaxBlock = $this->_view->getLayout()->getBlock($hash);
            if (!$ajaxBlock || !($ajaxBlock instanceof AjaxContainer)) {
                throw new LocalizedException(
                    __('Widget not found')
                );
            }
            $html = $this->_widgetLoader->render($ajaxBlock->getWidgetId(), $ajaxBlock->getWidgetParameters());
        } catch (\Exception $e) {
            $html = '';
        }
        $this->getResponse()->setBody($html);
    }
}
