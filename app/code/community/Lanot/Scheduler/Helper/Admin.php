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
class Lanot_Scheduler_Helper_Admin
	extends Mage_Core_Helper_Abstract
{
    /**
     * Check permission
     *
     * @return bool
     */
    public function isActionAllowed($section)
    {
        return Mage::getSingleton('admin/session')->isAllowed('lanot/lanot_scheduler/' . $section);
    }
}
