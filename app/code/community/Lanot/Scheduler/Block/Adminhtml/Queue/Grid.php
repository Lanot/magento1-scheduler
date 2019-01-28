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

class Lanot_Scheduler_Block_Adminhtml_Queue_Grid
    extends Lanot_Core_Block_Adminhtml_Grid_Abstract
{
    protected $_gridId = 'queueGrid';
    protected $_entityIdField = 'schedule_id';
    protected $_itemParam = 'schedule_id';
    protected $_formFieldName = 'queue';
    protected $_eventPrefix = 'queue_';

    /**
     * Checks when this block is readonly
     *
     * @return boolean
     */
    public function isReadonly()
    {
        return !$this->_getAclHelper()->isActionAllowed('view_queue');
    }

    /**
     * @return Mage_Cron_Model_Schedule
     */
    protected function _getItemModel()
    {
        return Mage::getModel('cron/schedule');
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        parent::_prepareCollection();

        $options = Mage::getSingleton('lanot_scheduler/job')->getTypeOptions();

        /** @var $item Mage_Cron_Model_Schedule */
        foreach($this->getCollection() as $item) {

            $type= (strpos($item->getJobCode(), 'lanot_scheduler_job_id_') === 0) ?
                Lanot_Scheduler_Model_Job::TYPE_CUSTOM : Lanot_Scheduler_Model_Job::TYPE_SYSTEM;

            $item->setTypeLabel($options[$type]);
        }
        return $this;
    }

    /**
     * Prepare Grid columns
     *
     * @return Lanot_Scheduler_Block_Adminhtml_Queue_Grid
     */
    protected function _prepareColumns()
    {
        Mage::dispatchEvent($this->_eventPrefix . 'lanot_grid_prepare_columns_before', array('grid' => $this));

        $this->addColumn($this->_entityIdField, array(
            'header' => $this->_getHelper()->__('ID'),
            'index'  => $this->_entityIdField,
            'type'   => 'number',
            'width'  => '50px',
        ));

        $this->addColumn('status', array(
            'header' => $this->_getHelper()->__('Status'),
            'index'  => 'status',
            'frame_callback' => array($this, 'decorateStatus'),
            'width' => '90',
        ));

        $this->addColumn('type_label', array(
            'header' => $this->_getHelper()->__('Job Type'),
            'index'  => 'type_label',
            'width'  => '50px',
            'filter' => false,
            'align' => 'center',
        ));

        $this->addColumn('job_code', array(
            'header' => $this->_getHelper()->__('Job Code'),
            'index'  => 'job_code',
        ));

        $this->addColumn('messages', array(
            'header' => $this->_getHelper()->__('Messages'),
            'index'  => 'messages',
            'frame_callback' => array($this, 'decorateMessages'),
            'width'  => '30%',
        ));

        $this->addColumn('scheduled_at', array(
            'header'  => $this->_getHelper()->__('Scheduled At'),
            'index'   => 'scheduled_at',
            'width'   => '150px',
            'align' => 'center',
        ));

        $this->addColumn('executed_at', array(
            'header'  => $this->_getHelper()->__('Executed At'),
            'index'   => 'executed_at',
            'width'   => '150px',
            'align' => 'center',
        ));

        $this->addColumn('finished_at', array(
            'header'  => $this->_getHelper()->__('Finished At'),
            'index'   => 'finished_at',
            'width'   => '150px',
            'align' => 'center',
        ));

        $this->addColumn('action',
            array(
                'header'    => $this->_getHelper()->__('Action'),
                'width'     => '70px',
                'align'     => 'center',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption' => $this->_getHelper()->__('View'),
                        'url'     => array('base' => '*/*/view'),
                        'field'   => 'job_code'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'banner',
            ));

        Mage::dispatchEvent($this->_eventPrefix . 'lanot_grid_prepare_columns', array('grid' => $this));

        return Mage_Adminhtml_Block_Widget_Grid::_prepareColumns();
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

    /**
     * Decorate status column values
     *
     * @param string $value
     * @param $row
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @param bool $isExport
     * @return string
     */
    public function decorateStatus($value, $row, $column, $isExport)
    {
        $class = '';
        switch ($row->getStatus()) {
            case Mage_Cron_Model_Schedule::STATUS_PENDING:
                $class = 'grid-severity-minor';
                break;
            case Mage_Cron_Model_Schedule::STATUS_RUNNING:
                $class = 'grid-severity-major';
                break;
            case Mage_Cron_Model_Schedule::STATUS_ERROR:
            case Mage_Cron_Model_Schedule::STATUS_MISSED:
                $class = 'grid-severity-critical';
                break;
            case Mage_Cron_Model_Schedule::STATUS_SUCCESS:
                $class = 'grid-severity-notice';
                break;
        }
        return '<span class="'.$class.'"><span>'.$value.'</span></span>';
    }

    /**
     * Decorate status column values
     *
     * @param string $value
     * @param $row
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @param bool $isExport
     * @return string
     */
    public function decorateMessages($value, $row, $column, $isExport)
    {
        return nl2br($row->getMessages());
    }

    /**
     * @param $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/view', array('job_code' => $row->getJobCode()));
    }
}
