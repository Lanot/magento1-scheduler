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
 * Observer model
 *
 * @author Lanot
 */
class Lanot_Scheduler_Model_Observer
{
    /**
     * @param $observer
     * @return $this
     */
    public function prepareSystemJobsCollection($observer)
    {
        /** @var Mage_Core_Model_Config_Element $jobsRoot */
        $jobsRoot = Mage::getConfig()->getNode('crontab/jobs');
        if (!$jobsRoot) {
            return $this;
        }

        /** @var Mage_Core_Model_Config_Element $defaultJobsRoot */
        $defaultJobsRoot = Mage::getConfig()->getNode('default/crontab/jobs');
        $defaultJobsRoot = $defaultJobsRoot? $defaultJobsRoot->asArray() : array();

        /** @var Varien_Data_Collection $collection */
        $collection = $observer->getEvent()->getCollection();

        foreach($jobsRoot->asArray() as $jobCode => $data) {
            $expr = isset($data['schedule']['cron_expr']) ?
                $data['schedule']['cron_expr'] : Mage::helper('lanot_scheduler')->__('Not scheduled');

            if (isset($defaultJobsRoot[$jobCode]['schedule']['cron_expr']) &&
                !empty($defaultJobsRoot[$jobCode]['schedule']['cron_expr'])
            ) {
                $expr = $defaultJobsRoot[$jobCode]['schedule']['cron_expr'];
            }

            $collection->addItem(
                new Varien_Object(array(
                    'job_code' => $jobCode,
                    'model'    => $data['run']['model'],
                    'schedule' => $expr,
                ))
            );
        }
        return $this;
    }
}
