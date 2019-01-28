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
require_once('Lanot/Scheduler/controllers/Adminhtml/JobController.php');

class Lanot_Scheduler_Adminhtml_SystemjobController
	extends Lanot_Scheduler_Adminhtml_JobController
{
    protected $_msgTitle = 'System Jobs';
    protected $_msgHeader = 'View System Jobs';
    protected $_aclSection = 'view_systemjobs';
}
