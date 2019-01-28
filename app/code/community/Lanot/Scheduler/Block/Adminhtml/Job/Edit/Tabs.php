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

class Lanot_Scheduler_Block_Adminhtml_Job_Edit_Tabs
	extends Mage_Adminhtml_Block_Widget_Tabs
{
	/**
	 * Initialize tabs and define tabs block settings
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->setId('page_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle($this->_getHelper()->__('Job Info'));
	}

    /**
     * @return Lanot_Scheduler_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('lanot_scheduler');
    }
}
