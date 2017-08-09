<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\WidgetAjax\Block\Widget;

use Magento\Framework\View\Element\Template\Context;

class AjaxContainer extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    /**
     * string
     */
    protected $_template = 'MageKey_WidgetAjax::widget/ajax_container.phtml';

    /**
     * @var \Magento\Framework\Encryption\EncryptorInterface
     */
    protected $_encryptor;

    /**
     * @param Template\Context $context
     * @param \Magento\Framework\Encryption\EncryptorInterface $encryptor
     * @param array $data
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor,
        array $data = []
    ) {
        $this->_encryptor = $encryptor;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve load widget url
     *
     * @return string
     */
    public function getLoadUrl()
    {
        return $this->getUrl('magekey/widget/ajax');
    }

    /**
     * Retrieve widget hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->_encryptor->encrypt($this->getWidgetId());
    }
}
