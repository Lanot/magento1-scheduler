<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category    Lanot
 * @package     Lanot_Scheduler
 * @copyright   Copyright (c) 2012 Lanot
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Lanot_Scheduler_Adminhtml_QueueController
	extends Lanot_Core_Controller_Adminhtml_AbstractController
{
    protected $_msgTitle = 'Queue';
    protected $_msgHeader = 'Manage Queue';
    protected $_msgItemDoesNotExist = 'The queue item does not exist.';
    protected $_msgItemNotFound = 'Unable to find the queue item #%s.';
    protected $_msgItemEdit = 'Edit Queue Type Item';
    protected $_msgItemNew = 'New Queue Type Item';
    protected $_msgItemSaved = 'The queue item has been saved.';
    protected $_msgItemDeleted = 'The queue item has been deleted.';
    protected $_msgError = 'An error occurred while edit the queue item.';
    protected $_msgErrorItems = 'An error occurred while edit the queue items %s.';
    protected $_msgItems = 'The queue items (#%s) has been';

    protected $_menuActive = 'lanot/lanot_scheduler';
    protected $_aclSection = 'view_queue';

    public function viewAction()
    {
        $jobCode = $this->getRequest()->getParam('job_code');
        if (!$jobCode) {
            return $this->_forward('noRoute');
        }
        $data = $this->_getHelper()->getCronData($jobCode);
        if (empty($data)) {
            return $this->_forward('noRoute');
        }
        Mage::register('cron.job.item.data', $data);
        $this->_loadLayouts();
    }

    /**
     * @return Mage_Cron_Model_Schedule
     */
    protected function _getItemModel()
    {
        return Mage::getModel('cron/schedule');
    }

    /**
     * @param Mage_Cron_Model_Schedule $model
     * @return $this
     */
    protected function _registerItem(Mage_Core_Model_Abstract $model)
    {
        Mage::register('lanot.cron.queue.item', $model);
        return $this;
    }

    /**
     * @return Lanot_Scheduler_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('lanot_scheduler');
    }

    /**
     * @return Lanot_Scheduler_Helper_Admin
     */
    protected function _getAclHelper()
    {
        return Mage::helper('lanot_scheduler/admin');
    }

    /**
     * Check the permission to run it
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_getAclHelper()->isActionAllowed($this->_aclSection);
    }
}
