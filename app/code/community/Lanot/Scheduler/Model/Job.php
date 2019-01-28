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

/**
 * job item model
 *
 * @author Lanot
 */
class Lanot_Scheduler_Model_Job extends Mage_Core_Model_Abstract
{
    const TYPE_SYSTEM = 0;
    const TYPE_CUSTOM = 1;

    protected function _construct()
    {
        $this->_init('lanot_scheduler/job');
    }

    /**
     * @return $this
     */
    protected function _beforeSave()
    {
        $this->validateData();
        $this->validateSchedule();
        $this->prepareScheduleFromArray($this);
        $this->setData('updated_at', Lanot_Scheduler_Helper_Data::now());
        if ($this->isObjectNew()) {
            $this->setData('created_at', Lanot_Scheduler_Helper_Data::now());
        }
        return parent::_beforeSave();
    }

    /**
     * @return $this
     */
    protected function _afterLoad()
    {
        parent::_afterLoad();
        $this->prepareScheduleToArray($this);
        $this->setJobCode($this->_getJobCode());
        return $this;
    }

    /**
     * @return $this
     */
    protected function _afterSaveCommit()
    {
        parent::_afterSaveCommit();
        $this->cacheClean();
        $this->cleanObsoleteSchedules();
        $this->generateNewSchedules();
        return $this;
    }

    /**
     * @return $this
     */
    protected function _afterDeleteCommit()
    {
        parent::_afterDeleteCommit();
        $this->cleanObsoleteSchedules();
        $this->cacheClean();
        return $this;
    }

    /**
     * @return string
     */
    public function getSchedule()
    {
        return $this->prepareScheduleFromObject($this);
    }

    /**
     * @param Lanot_Scheduler_Model_Job $object
     * @return string
     */
    public function prepareScheduleFromObject($object)
    {
        $schedule = array();
        foreach($this->_getHelper()->getScheduleDescription() as $k => $v) {
            $schedule[] = $object->hasData($k) ? $object->getData($k) : '*';
        }
        return implode(' ', $schedule);
    }

    /**
     * @param Lanot_Scheduler_Model_Job $object
     * @return $this
     */
    public function prepareScheduleFromArray($object)
    {
        foreach($this->_getHelper()->getScheduleDescription() as $k => $description) {
            $val = $object->hasData($k) ? $object->getData($k) : '*';
            $val = is_array($val) ? implode(Lanot_Scheduler_Helper_Data::SCHEDULE_DELIMITER, $val) : $val;
            $this->setData($k, $val);
        }
        return $this;
    }

    /**
     * @param Lanot_Scheduler_Model_Job $object
     * @return $this
     */
    public function prepareScheduleToArray($object)
    {
        foreach($this->_getHelper()->getScheduleDescription() as $k => $description) {
            $val = $object->hasData($k) ? $object->getData($k) : '*';
            $val = !is_array($val) ? explode(Lanot_Scheduler_Helper_Data::SCHEDULE_DELIMITER, $val) : $val;
            $this->setData($k, $val);
        }
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
     * @return $this
     * @throws Mage_Core_Exception
     */
    public function validateSchedule()
    {
        $description = $this->_getHelper()->getScheduleDescription();
        foreach($this->_getHelper()->getScheduleLimits() as $key => $limit) {
            if (!$this->hasData($key) || !is_array($this->getData($key))) {
                Mage::throwException($this->_getHelper()->__('Please, fill the "%s" field', $description[$key]));
            }

            $cnt = count($this->getData($key));
            foreach($this->getData($key) as $value) {
                if ($value == '*' && $cnt > 1) {
                    Mage::throwException($this->_getHelper()->__('Please, unselect other values which are different from the "Every %s" field', $description[$key]));
                } elseif($value != '*' && $value > $limit['max']) {
                    Mage::throwException($this->_getHelper()->__('Value of the "%s" field if bigger than it is allowed. Max. value is "%s"', $description[$key], $limit['max']));
                } elseif($value != '*' && $value < $limit['min']) {
                    Mage::throwException($this->_getHelper()->__('Value of the "%s" field if smaller than it is allowed. Min. value is "%s"', $description[$key], $limit['min']));
                }
            }
        }
        return $this;
    }

    /**
     * @return $this
     * @throws Mage_Core_Exception
     */
    public function validateData()
    {
        if (!preg_match(Mage_Cron_Model_Observer::REGEX_RUN_MODEL, $this->getModel(), $run)) {
            Mage::throwException(Mage::helper('cron')->__('Invalid model/method definition, expecting "model/class::method".'));
        }

        if (!($model = Mage::getModel($run[1])) || !method_exists($model, $run[2])) {
            Mage::throwException(Mage::helper('cron')->__('Invalid callback: %s::%s does not exist', $run[1], $run[2]));
        }
        return $this;
    }

    /**
     * @return string
     */
    protected function _getJobCode()
    {
        return sprintf(Lanot_Scheduler_Helper_Data::XPATH_PATTERN_JOB_CODE, $this->getId());
    }

    /**
     * @return $this
     */
    public function cacheClean()
    {
        if (!$this->_getHelper()->isAutoCacheCleanEnabled()) {
            return $this;
        }
        Mage::app()->getCacheInstance()->cleanType('config');
        return $this;
    }

    /**
     * @return $this
     */
    public function cleanObsoleteSchedules()
    {
        if (!$this->_getHelper()->isAutoQueueCleanEnabled()) {
            return $this;
        }
        $obsoleteRecords = Mage::getModel('cron/schedule')->getCollection()
            ->addFieldToFilter('job_code', $this->getJobCode());
        foreach ($obsoleteRecords as $record) {
            $record->delete();
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function generateNewSchedules()
    {
        if (!$this->_getHelper()->isAutoQueueGenerationEnabled()) {
            return $this;
        }
        Mage::app()->saveCache(0,Mage_Cron_Model_Observer::CACHE_KEY_LAST_SCHEDULE_GENERATE_AT, array('crontab'), null);
        Mage::getSingleton('cron/observer')->generate();
        return $this;
    }

    /**
     * @return array
     */
    public function getTypeOptions()
    {
        return array(
            self::TYPE_SYSTEM => $this->_getHelper()->__('System'),
            self::TYPE_CUSTOM => $this->_getHelper()->__('Custom'),
        );
    }

    /**
     * @return mixed
     */
    public function getJobCode()
    {
        if (!$this->hasData('job_code')) {
            $this->setJobCode($this->_getJobCode());
        }
        return $this->getData('job_code');
    }
}
