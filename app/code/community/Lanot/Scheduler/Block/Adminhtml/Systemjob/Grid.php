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

class Lanot_Scheduler_Block_Adminhtml_Systemjob_Grid
    extends Lanot_Scheduler_Block_Adminhtml_Job_Grid
{
    protected $_eventPrefix = 'systemjob_';
    protected $_filterVisibility = false;
    protected $_pagerVisibility = false;

    protected function _construct()
    {
        parent::_construct();
        $this->setDefaultLimit(1000);//@todo: fix
    }

    /**
     * @return Lanot_Core_Block_Adminhtml_Grid_Abstract
     */
    protected function _prepareCollection()
    {
        $collection = new Varien_Data_Collection();
        Mage::dispatchEvent($this->_eventPrefix . 'lanot_grid_prepare_collection', array(
                'grid' => $this,
                'collection' => $collection
        ));
        $this->setCollection($collection);
        return Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
    }

    /**
     * Prepare Grid columns
     *
     * @return Lanot_Scheduler_Block_Adminhtml_Job_Grid
     */
    protected function _prepareColumns()
    {
        parent::_prepareColumns();

        $this->removeColumn('updated_at');
        $this->removeColumn('action');
        $this->removeColumn('title');
        $this->removeColumn('job_id');
    }

    /**
     * Checks when this block is readonly
     *
     * @return boolean
     */
    public function isReadonly()
    {
        return true;
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

    /**
     * @param $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return '#';
    }
}
