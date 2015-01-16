<?php

class Ip_Contactforms_Block_Form extends Mage_Core_Block_Template
{

    function _construct()
    {
        parent::_construct();
        $this->setForm(Mage::registry('forms_data'));
    }


    protected function _prepareLayout()
    {
        if ($meta_title = Mage::registry('forms_data')->getMetaTitle()) {
            $this->getLayout()->getBlock('head')->setTitle($meta_title);
        }
    }

    public function getFormAction()
    {
        return Mage::getUrl('contactforms/forms/post', array('id' => Mage::registry('forms_data')->getId()));
    }

}