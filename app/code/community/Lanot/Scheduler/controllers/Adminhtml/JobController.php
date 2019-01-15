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

class Lanot_Scheduler_Adminhtml_JobController
	extends Lanot_Core_Controller_Adminhtml_AbstractController
{
    protected $_msgTitle = 'Jobs';
    protected $_msgHeader = 'Manage Jobs';
    protected $_msgItemDoesNotExist = 'The job item does not exist.';
    protected $_msgItemNotFound = 'Unable to find the job item #%s.';
    protected $_msgItemEdit = 'Edit Job Type Item';
    protected $_msgItemNew = 'New Job Type Item';
    protected $_msgItemSaved = 'The job item has been saved.';
    protected $_msgItemDeleted = 'The job item has been deleted.';
    protected $_msgError = 'An error occurred while edit the job item.';
    protected $_msgErrorItems = 'An error occurred while edit the job items %s.';
    protected $_msgItems = 'The job items (#%s) has been';

    protected $_menuActive = 'lanot/lanot_scheduler';
    protected $_aclSection = 'manage_jobs';

    /**
     * @return Lanot_Scheduler_Model_Job
     */
    protected function _getItemModel()
    {
        return Mage::getModel('lanot_scheduler/job');
    }

    /**
     * @param Lanot_Scheduler_Model_Job $model
     * @return $this
     */
    protected function _registerItem(Mage_Core_Model_Abstract $model)
    {
        Mage::register('lanot.cron.job.item', $model);
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
