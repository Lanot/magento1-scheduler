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
 * @package     Lanot_Core
 * @copyright   Copyright (c) 2012 Lanot
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
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