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

class Lanot_Scheduler_Block_Adminhtml_Job_Edit
	extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Initialize edit form container
     *
     */
    public function __construct()
    {
        $this->_blockGroup = 'lanot_scheduler';
        $this->_controller = 'adminhtml_job';

        parent::__construct();

        //check permissions
        if (Mage::helper('lanot_scheduler/admin')->isActionAllowed('manage_jobs')) {
            $this->_updateButton('save', 'label', Mage::helper('lanot_scheduler')->__('Save Job Item'));
            $this->_addButton('saveandcontinue', array(
                'label'   => Mage::helper('adminhtml')->__('Save and Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ), -100);

            $this->_updateButton('delete', 'label', Mage::helper('lanot_scheduler')->__('Delete Job Item'));

            $this->_formScripts[] = "
            	function saveAndContinueEdit(){
            		editForm.submit($('edit_form').action+'back/edit/');
            	}";
        } else {
            $this->_removeButton('save');
            $this->_removeButton('delete');
        }
    }

    public function getHeaderText()
    {
    	$header = Mage::helper('lanot_scheduler')->__('New  Cron Job');
        $model = Mage::helper('lanot_scheduler')->getJobItemInstance();
        if ($model->getId()) {
        	$title = $this->escapeHtml($model->getTitle());
            $header = Mage::helper('lanot_scheduler')->__("Edit Cron Job '%s'", $title);
        }        
        return $header;
    }
}
