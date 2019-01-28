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
