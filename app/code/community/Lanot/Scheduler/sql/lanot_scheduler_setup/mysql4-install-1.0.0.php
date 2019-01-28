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

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

//tables definition
$jobTable = $this->getTable('lanot_scheduler/job');

//create table for jobs
$this->run("
	DROP TABLE IF EXISTS `{$jobTable}`;
	CREATE TABLE `{$jobTable}` (
	    `job_id`        int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Cron Job ID',
        `model`         varchar(255) DEFAULT NULL,
	    `title`         varchar(255) DEFAULT NULL,
        `min`           varchar(255) DEFAULT NULL,
        `hour`          varchar(255) DEFAULT NULL,
        `day_of_month`  varchar(255) DEFAULT NULL,
        `month`         varchar(255) DEFAULT NULL,
        `day_of_week`   varchar(255) DEFAULT NULL,

		`created_at`    timestamp NULL DEFAULT NULL COMMENT 'Creation Time',
  		`updated_at`    timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Update Time',

		PRIMARY KEY (`job_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Cron Job Table';
");

$installer->endSetup();