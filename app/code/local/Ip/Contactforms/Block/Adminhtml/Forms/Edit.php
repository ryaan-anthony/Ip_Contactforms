<?php

class Ip_Contactforms_Block_Adminhtml_Forms_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {

        //do this before parent constructor to enable delete button
        $this->_objectId = 'id';

        parent::__construct();

        $this->_blockGroup = 'contactforms';
        $this->_controller = 'adminhtml_forms';
        $this->_mode = 'edit';

        $this->_addButton('save_and_continue', array(
            'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('form_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'edit_form');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'edit_form');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if (Mage::registry('forms_data') && Mage::registry('forms_data')->getId())
        {
            return Mage::helper('adminhtml')->__('Contact Form: "%s"', $this->htmlEscape(Mage::registry('forms_data')->getTitle()));
        } else {
            $this->_removeButton('save_and_continue');
            return Mage::helper('adminhtml')->__('New Contact Form');
        }
    }

}