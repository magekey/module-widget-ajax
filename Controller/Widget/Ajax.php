<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\WidgetAjax\Controller\Widget;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use MageKey\WidgetAjax\Model\WidgetLoader;

class Ajax extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Encryption\EncryptorInterface
     */
    protected $_encryptor;

    /**
     * @var WidgetLoader
     */
    protected $_widgetLoader;

    /**
     * @param Context $context
     * @param \Magento\Framework\Encryption\EncryptorInterface $encryptor
     * @param WidgetLoader $widgetLoader
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor,
        WidgetLoader $widgetLoader
    ) {
        parent::__construct($context);
        $this->_encryptor = $encryptor;
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
                    __('Hash should be defined')
                );
            }

            $widgetId = $this->_encryptor->decrypt($hash);
            if (!$widgetId) {
                throw new LocalizedException(
                    __('Widget not found')
                );
            }
            $this->_view->loadLayout();
            $html = $this->_widgetLoader->load($widgetId);
        } catch (\Exception $e) {
            $html = '';
        }
        $this->getResponse()->setBody($html);
    }
}
