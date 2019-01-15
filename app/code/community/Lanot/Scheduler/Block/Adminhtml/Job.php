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

