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

/**
 * Job resource model
 *
 * @author Lanot
 */
class Lanot_Scheduler_Model_Mysql4_Job extends Mage_Core_Model_Mysql4_Abstract
{
     /**
     * Initialize connection and define main table and primary key
     */
    protected function _construct()
    {
        $this->_init('lanot_scheduler/job', 'job_id');
    }

    /**
     * @param Mage_Core_Model_Abstract $object
     * @return $this
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        parent::_afterSave($object);
        $this->_saveConfigRelations($object);
        return $this;
    }

    /**
     * @param Mage_Core_Model_Abstract $object
     * @return $this
     */
    protected function _afterDelete(Mage_Core_Model_Abstract $object)
    {
        $this->_removeConfigRelations($object);
        return parent::_afterDelete($object);
    }

    /**
     * @param Mage_Core_Model_Abstract $object
     * @return $this
     */
    protected function _saveConfigRelations(Mage_Core_Model_Abstract $object)
    {
        $data = array(
            array(
                'scope' => 'default',
                'scope_id' => '0',
                'path' => sprintf(Lanot_Scheduler_Helper_Data::XPATH_PATTERN_MODEL, $object->getId()),
                'value' => $object->getModel(),
            ),
            array(
                'scope' => 'default',
                'scope_id' => '0',
                'path' => sprintf(Lanot_Scheduler_Helper_Data::XPATH_PATTERN_EXPR, $object->getId()),
                'value' => $object->getSchedule(),
            ),
        );

        $this->_getWriteAdapter()->insertOnDuplicate(
            $this->getTable('core/config_data'),
            $data,
            array('value')
        );
        return $this;
    }

    /**
     * @param Mage_Core_Model_Abstract $object
     * @return $this
     */
    protected function _removeConfigRelations(Mage_Core_Model_Abstract $object)
    {
        $paths = array(
            sprintf(Lanot_Scheduler_Helper_Data::XPATH_PATTERN_MODEL, $object->getId()),
            sprintf(Lanot_Scheduler_Helper_Data::XPATH_PATTERN_EXPR, $object->getId())
        );
        $this->_getWriteAdapter()->delete(
            $this->getTable('core/config_data'),
            $this->_getWriteAdapter()->quoteInto('`path` IN (?)', $paths)
        );
        return $this;
    }
}
