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

class Lanot_Scheduler_Block_Adminhtml_Job_Edit_Tab_Main
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Prepare form elements for tab
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $isElementDisabled = !$this->_getAclHelper()->isActionAllowed('manage_jobs');
        $model = $this->_getHelper()->getJobItemInstance();

        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('job_main_');

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend'   => $this->_getHelper()->__('Cron Job Info'),
        ));

        if ($model->getId()) {
            $fieldset->addField('job_id', 'hidden', array(
                'name' => 'id',
            ));

            $fieldset->addField('job_code', 'label', array(
                'name'  => 'view_job_code',
                'label' => $this->_getHelper()->__('Job Code'),
                'bold'  => true,
            ));
        }

        $fieldset->addField('title', 'text', array(
            'name'     => 'title',
            'label'    => $this->_getHelper()->__('Title'),
            'title'    => $this->_getHelper()->__('Title'),
            'required' => true,
            'disabled' => $isElementDisabled,
        ));

        $fieldset->addField('model', 'text', array(
            'name'     => 'model',
            'label'    => $this->_getHelper()->__('Model'),
            'title'    => $this->_getHelper()->__('Model'),
            'required' => true,
            'disabled' => $isElementDisabled,
            'after_element_html' => $this->_getHelper()->__('<span>Provide an existing Magento model, class and method that can be executed in format: <b>model/class::method</b>. Where model is an alias of your module.<br />Example: <b>catalog/observer::reindexProductPrices</b> </span>'),
        ));

        $scheduleFieldset = $form->addFieldset('schedule_fieldset', array(
            'legend' => $this->_getHelper()->__('Schedule'),
            'table_class' => 'custom-form-list',
            'after_element_html' =>
                $this->_getHelper()->__('<div>Press CTRL + Mouse click to choose several options</div>') .
                $this->_getHelper()->__('<br /><div>Schedule examples: %s</div>',
                    $this->_getHelper()->getCronJobExamples()
            )
        ))->setRenderer($this->_getRenderedFieldset());


        foreach($this->_getHelper()->getScheduleDescription() as $key => $label) {
            $scheduleFieldset->addField($key, 'multiselect', array(
                'name'     => $key,
                'label'    => $this->_getHelper()->__($label),
                'title'    => $this->_getHelper()->__($label),
                'required' => true,
                'disabled' => $isElementDisabled,
                'values'   => $this->_getHelper()->prepareCronJobOptions($key, true),
                'style'    => 'width: 120px;',
            ))->setRenderer($this->_getRenderedFieldsetElement());
        }

        $form->setValues($model->getData());

        Mage::dispatchEvent('adminhtml_lanot_scheduler_job_edit_tab_main_prepare_form', array(
            'form'    => $form,
            'sticker' => $model
        ));

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

    /**
     * @return Lanot_Scheduler_Helper_Admin
     */
    protected function _getAclHelper()
    {
        return Mage::helper('lanot_scheduler/admin');
    }

    public function getTabLabel()
    {
        return $this->_getHelper()->__('Job Info');
    }

    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return !$this->canShowTab();
    }

    /**
     * @return Mage_Adminhtml_Block_Widget_Form_Renderer_Fieldset_Element
     */
    protected function _getRenderedFieldsetElement()
    {
        return Mage::getBlockSingleton('adminhtml/widget_form_renderer_fieldset_element')
            ->setTemplate('lanot/scheduler/widget/form/renderer/fieldset/element.phtml');
    }

    /**
     * @return Mage_Adminhtml_Block_Widget_Form_Renderer_Fieldset
     */
    protected function _getRenderedFieldset()
    {
        return Mage::getBlockSingleton('adminhtml/widget_form_renderer_fieldset')
            ->setTemplate('lanot/scheduler/widget/form/renderer/fieldset.phtml');
    }
}
