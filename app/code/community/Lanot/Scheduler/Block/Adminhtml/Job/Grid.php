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

class Lanot_Scheduler_Block_Adminhtml_Job_Grid
    extends Lanot_Core_Block_Adminhtml_Grid_Abstract
{
    protected $_gridId = 'jobGrid';
    protected $_entityIdField = 'job_id';
    protected $_itemParam = 'job_id';
    protected $_formFieldName = 'job';
    protected $_eventPrefix = 'job_';

    /**
     * Checks when this block is readonly
     *
     * @return boolean
     */
    public function isReadonly()
    {
        return !$this->_getAclHelper()->isActionAllowed('manage_jobs');
    }

    /**
     * @return Lanot_Scheduler_Model_Job
     */
    protected function _getItemModel()
    {
        return Mage::getModel('lanot_scheduler/job');
    }

    /**
     * Prepare Grid columns
     *
     * @return Lanot_Scheduler_Block_Adminhtml_Job_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumnAfter('job_code', array(
            'header'  => $this->_getHelper()->__('Job Code'),
            'index'   => 'job_id',
            'width' => '15%',
            'frame_callback' => array($this, 'prepareJobCode'),
        ), 'title');

        $this->addColumnAfter('schedule', array(
            'header'  => $this->_getHelper()->__('Schedule'),
            'index'   => 'job_id',
            'filter'  => false,
            'width' => '15%',
            'frame_callback' => array($this, 'prepareSchedule'),
        ), 'job_code');

        $this->addColumnAfter('model', array(
            'header'  => $this->_getHelper()->__('Model'),
            'index'   => 'model',
            'width' => '20%',
        ), 'schedule');

        parent::_prepareColumns();

        $this->removeColumn('is_active');
    }

    /**
     * Decorate status column values
     *
     * @return string
     */
    public function prepareSchedule($value, $row, $column, $isExport)
    {
        return $row->getSchedule();
    }

    /**
     * Decorate status column values
     *
     * @return string
     */
    public function prepareJobCode($value, $row, $column, $isExport)
    {
        return $row->getJobCode();
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
     * Checks when this block is not available
     *
     * @return boolean
     */
    public function isMassActionAllowed()
    {
        return false;
    }
}
