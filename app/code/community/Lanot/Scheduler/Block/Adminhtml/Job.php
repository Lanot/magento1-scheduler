<?php
/**
 * Private Entrepreneur Anatolii Lehkyi (aka Lanot)
 *
 * @category    Lanot
 * @package     Lanot_Scheduler
 * @copyright   Copyright (c) 2010 Anatolii Lehkyi
 * @license     http://opensource.org/licenses/osl-3.0.php
 * @link        http://www.lanot.biz/
 */

class Lanot_Scheduler_Block_Adminhtml_Job
	extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Block constructor
     */
    public function __construct()
    {
        $this->_blockGroup = 'lanot_scheduler';
        $this->_controller = 'adminhtml_job';
        $this->_headerText = Mage::helper('lanot_scheduler')->__('Manage Custom Cron Jobs');

        parent::__construct();

        if (Mage::helper('lanot_scheduler/admin')->isActionAllowed('manage_jobs')) {
            $this->_updateButton('add', 'label', Mage::helper('lanot_scheduler')->__('Add New Job'));
        } else {
            $this->_removeButton('add');
        }
    }
}

