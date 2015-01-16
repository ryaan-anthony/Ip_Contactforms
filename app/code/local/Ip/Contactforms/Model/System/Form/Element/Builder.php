<?php

class Ip_Contactforms_Model_System_Form_Element_Builder extends Varien_Data_Form_Element_Abstract
{

    public function __construct($data) {
        parent::__construct($data);
        $this->setType('form_builder');
    }

    public function getElementHtml()
    {
        /** @var Mage_Core_Model_Layout $layout */
        $layout = Mage::app()->getLayout();
        $formbuilder = $layout->createBlock('core/template')
            ->setTemplate('contactforms/formbuilder.phtml');
        $field_template = $layout->createBlock('core/template')
            ->setTemplate('contactforms/formbuilder/field.phtml')
            ->setName($this->getData('name'));
        $formbuilder->setFieldTemplate($field_template);
        $formbuilder->setEmptyTemplate(
            $field_template->addData(
                array(
                    'id'=>'#{id}',
                    'code'=>'#{code}',
                    'label'=>'#{label}',
                    'type'=>'#{type}',
                    'position'=>'#{position}',
                    'required'=>'#{required}',
                )
            )->toHtml()
        );
        $formbuilder->setFields($this->getValue());
        return $formbuilder->toHtml();
    }


}