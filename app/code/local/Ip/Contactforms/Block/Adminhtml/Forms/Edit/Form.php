<?php

class Ip_Contactforms_Block_Adminhtml_Forms_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $data = Mage::registry('forms_data');

        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method' => 'post',
            'enctype' => 'multipart/form-data',
        ));


        $form->setUseContainer(true);

        $this->setForm($form);

        $fieldset = $form->addFieldset('contact_form_details', array(
            'legend' =>Mage::helper('adminhtml')->__('Contact Form: Form Details'),
        ));


        $fieldset->addField('title', 'text', array(
            'label'     => Mage::helper('adminhtml')->__('Title'),
            'class'     => 'required-entry',
            'style'     =>  'width:700px;',
            'required'  => true,
            'name'      => 'title',
        ));

        $fieldset->addField('url_key', 'text', array(
            'label'     => Mage::helper('adminhtml')->__('Url Key'),
            'style'     =>  'width:700px;',
            'name'      => 'url_key',
        ));

        $fieldset->addField('meta_title', 'text', array(
            'label'     => Mage::helper('adminhtml')->__('Meta Title'),
            'style'     =>  'width:700px;',
            'name'      => 'meta_title',
        ));

        $email_templates = Mage::getSingleton('contactforms/system_config_source_template');

        $fieldset->addField('request_template', 'select', array(
            'label'     => Mage::helper('adminhtml')->__('Request Template'),
            'style'     =>  'width:700px;',
            'name'      => 'request_template',
            'options'    => $email_templates->setPath('contactforms_request_template')->toArray()
        ));

        $fieldset->addField('response_template', 'select', array(
            'label'     => Mage::helper('adminhtml')->__('Response Template'),
            'style'     =>  'width:700px;',
            'name'      => 'response_template',
            'options'    => $email_templates->setPath('contactforms_response_template')->toArray()
        ));

        $fieldset->addField('content', 'editor', array(
            'label'     => Mage::helper('adminhtml')->__('Page Content'),
            'config'    => Mage::getSingleton('cms/wysiwyg_config')->getConfig(),
            'required'  => false,
            'name'      => 'content',
            'style'     => 'width:700px; height:500px;',
            'wysiwyg'   => true,
        ));



        $fieldset = $form->addFieldset('contact_form_fields', array(
            'legend' =>Mage::helper('adminhtml')->__('Contact Form: Form Fields'),
        ));
        $fieldset->addType('form_builder','Ip_Contactforms_Model_System_Form_Element_Builder');

        $fieldset->addField('fields', 'form_builder', array(
            'label'     => Mage::helper('adminhtml')->__('Form Fields'),
            'name'      => 'fields'
        ));

        $fields = Mage::getModel('contactforms/fields')->getCollection()
            ->addFieldToFilter('form_id', $this->getRequest()->getParam('id'))
            ->setOrder('position', 'ASC');
        $data['fields'] = $fields;

        $form->setValues($data);

        return parent::_prepareForm();
    }

}