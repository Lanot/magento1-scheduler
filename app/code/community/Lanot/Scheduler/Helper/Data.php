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

class Lanot_Scheduler_Helper_Data extends Mage_Core_Helper_Abstract
{
    const SCHEDULE_DELIMITER = ',';
    const XPATH_PATTERN_MODEL = 'crontab/jobs/lanot_scheduler_job_id_%d/run/model';
    const XPATH_PATTERN_EXPR = 'crontab/jobs/lanot_scheduler_job_id_%d/schedule/cron_expr';
    const XPATH_PATTERN_JOB_CODE = 'lanot_scheduler_job_id_%d';
    const XPATH_CLEAN_CACHE_NODE = 'lanot_scheduler/settings/enabled_cache_clean';
    const XPATH_CLEAN_QUEUE_NODE = 'lanot_scheduler/settings/enabled_queue_clean';
    const XPATH_GENERATE_QUEUE_NODE = 'lanot_scheduler/settings/enabled_queue_generation';
    const DATETIME_PHP_FORMAT       = 'Y-m-d H:i:s';

    protected $_translatedLimits = array(
        'day_of_week' => array(
            '0' => 'Sunday',
            '1' => 'Monday',
            '2' => 'Tuesday',
            '3' => 'Wednesday',
            '4' => 'Thursday',
            '5' => 'Friday',
            '6' => 'Saturday',
        ),
        'month' => array(
            '1'  => 'January',
            '2'  => 'February',
            '3'  => 'March',
            '4'  => 'April',
            '5'  => 'May',
            '6'  => 'June',
            '7'  => 'July',
            '8'  => 'August',
            '9'  => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        ),
    );

    /**
     * @return array
     */
    public function getScheduleDescription()
    {
        return array(
            'min'          => $this->__('Minute'),
            'hour'         => $this->__('Hour'),
            'day_of_month' => $this->__('Day Of Month'),
            'month'        => $this->__('Month'),
            'day_of_week'  => $this->__('Day Of Week'),
        );
    }

    /**
     * @return array
     */
    public function getScheduleLimits()
    {
        return array(
            'min'          => array('min' => 0, 'max' => 59),
            'hour'         => array('min' => 0, 'max' => 23),
            'day_of_month' => array('min' => 1, 'max' => 31),
            'month'        => array('min' => 1, 'max' => 12),
            'day_of_week'  => array('min' => 0, 'max' => 6),
        );
    }

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    public function getScheduleTranslatedLimits($name, $value)
    {
        return isset($this->_translatedLimits[$name][$value]) ? $this->_translatedLimits[$name][$value] : $value;
    }

    /**
     * @return string
     */
    public function getCronJobExamples()
    {
        return '<br />Run once a year - 0 0 1 1 *' .
        '<br />Run once a month - 0 0 1 * *' .
        '<br />Run once a week - 0 0 * * 0' .
        '<br />Run once a day - 0 0 * * *' .
        '<br />Run once an hour - 0 * * * *'.
        '<br />Run each 6 hours  - 0 0,6,12,18 * * *'.
        '<br />Run each 10 minutes - 0,10,20,30,40,50 * * * *';
    }

    /**
     * @param $key
     * @param bool $each
     * @return array
     */
    public function prepareCronJobOptions($key, $each = false)
    {
        $options = array();
        if ($each) {
            $schedules = $this->getScheduleDescription();
            $options['*'] = array('value' => '*', 'label' => $this->__('Every %s', $schedules[$key]));
        }
        $limits = $this->getScheduleLimits();
        for($i = $limits[$key]['min']; $i <= $limits[$key]['max'];$i++) {
            $options["$i"] = array('value' => $i, 'label' => $this->getScheduleTranslatedLimits($key, $i));
        }
        return $options;
    }

    /**
     * @return Lanot_Scheduler_Model_job
     */
    public function getJobItemInstance()
    {
        $item = Mage::registry('lanot.cron.job.item');
        if (!$item->getId()) {
            foreach($this->getScheduleDescription() as $key => $label) {
                if (!$item->hasData($key)) {
                    $item->setData($key, $key == 'min' ? '0' : '*');
                }
            }
        }
        return $item;
    }

    /**
     * @return bool
     */
    public function isAutoCacheCleanEnabled()
    {
        return (bool) Mage::app()->getStore()->getConfig(self::XPATH_CLEAN_CACHE_NODE);
    }

    /**
     * @return bool
     */
    public function isAutoQueueCleanEnabled()
    {
        return (bool) Mage::app()->getStore()->getConfig(self::XPATH_CLEAN_QUEUE_NODE);
    }

    /**
     * @return bool
     */
    public function isAutoQueueGenerationEnabled()
    {
        return (bool) Mage::app()->getStore()->getConfig(self::XPATH_GENERATE_QUEUE_NODE);
    }

    /**
     * Retrieve current date in internal format
     * @return string
     */
    public static function now()
    {
        return date(self::DATETIME_PHP_FORMAT);
    }

    /**
     * @param $jobCode
     * @return array|bool|string
     */
    public function getCronData($jobCode)
    {
        $node = Mage::getConfig()->getNode('crontab/jobs/' . $jobCode);
        if (!$node) {
            $node = Mage::getConfig()->getNode('default/crontab/jobs/' . $jobCode);
        }
        $data = array();
        if ($node) {
            $options = Mage::getSingleton('lanot_scheduler/job')->getTypeOptions();
            $data = $node->asArray();

            $data['job_code']  = $jobCode;
            $data['cron_expr'] = $data['schedule']['cron_expr'];
            $data['model']     = $data['run']['model'];

            $data['type']      = (strpos($jobCode, 'lanot_scheduler_job_id_') === 0) ?
                Lanot_Scheduler_Model_Job::TYPE_CUSTOM : Lanot_Scheduler_Model_Job::TYPE_SYSTEM;
            $data['type_label']  = $options[$data['type']];

            if ($data['type'] == Lanot_Scheduler_Model_Job::TYPE_CUSTOM) {
                $data['job_id']    = (int) str_replace('lanot_scheduler_job_id_', '', $jobCode);
            }
        }
        return $data;
    }
}
