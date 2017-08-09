<?php
/**
 * Copyright © MageKey. All rights reserved.
 */
namespace MageKey\WidgetAjax\Observer\Adminhtml;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class WidgetInstanceSavePostDispatch implements ObserverInterface
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @param \Magento\Framework\Registry $coreRegistry
     */
    public function __construct(
        \Magento\Framework\Registry $coreRegistry
    ) {
        $this->_coreRegistry = $coreRegistry;
    }

    /**
     * @return void
     */
    public function execute(Observer $observer)
    {
        if ($widgetInstance = $this->_coreRegistry->registry('current_widget_instance')) {
            $request = $observer->getEvent()->getRequest();
            try {
                $resource = $widgetInstance->getResource();
                $connection = $resource->getConnection();
                $connection->update(
                    $resource->getMainTable(),
                    ['is_ajax' => (int)(bool)$request->getPost('is_ajax')],
                    [$resource->getIdFieldName() . ' = ?' => (int)$widgetInstance->getId()]
                );
            } catch (\Exception $e) {
                echo $e;exit;
                return;
            }
        }
    }
}
