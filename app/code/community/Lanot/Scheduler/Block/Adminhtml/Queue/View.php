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

class Lanot_Scheduler_Block_Adminhtml_Queue_View
    extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Prepare form elements for tab
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $data = Mage::registry('cron.job.item.data');

        $form = new Varien_Data_Form(array(
            'id'      => 'view_form',
            'action'  => '#',
            'method'  => 'post',
        ));
        $form->setUseContainer(false);

        //prepare fieldsets
        $fieldset = $form->addFieldset('main_fieldset', array(
            'legend' => $this->_getHelper()->__('Cron Job Info')
        ));

        $fieldset->addField('job_code', 'label', array(
            'name'  => 'job_code',
            'label' => $this->_getHelper()->__('Job Code'),
            'bold'  => true,
        ));

        if (isset($data['job_id'])) {
            $fieldset->addField('job_id', 'note', array(
                'name'  => 'job_id',
                'label' => '',
                'bold'  => true,
                'after_element_html' => sprintf('<a href="%s">%s</a>',
                    $this->getUrl('*/adminhtml_job/edit', array('id' => $data['job_id'])),
                    $this->_getHelper()->__('Edit custom cron job')
                ),
            ));
        }

        $fieldset->addField('type_label', 'label', array(
            'name'  => 'type_label',
            'label' => $this->_getHelper()->__('Type'),
        ));

        $fieldset->addField('cron_expr', 'label', array(
            'name'  => 'cron_expr',
            'label' => $this->_getHelper()->__('Cron schedule'),
        ));

        $fieldset->addField('model', 'label', array(
            'name'     => 'model',
            'label'    => $this->_getHelper()->__('Model'),
        ));

        Mage::dispatchEvent('adminhtml_lanot_scheduler_queue_view_form', array('form' => $form));

        $form->setValues($data);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * @return Lanot_Scheduler_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('lanot_scheduler');
    }
}
