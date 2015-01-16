<?php

class Ip_Contactforms_Model_System_Config_Source_Template extends Varien_Object
{
    /**
     * Config xpath to email template node
     *
     */
    const XML_PATH_TEMPLATE_EMAIL = 'global/template/email/';

    /**
     * Generate list of email templates
     *
     * @return array
     */
    public function toArray(array $arrAttributes = array())
    {
        if(!$collection = Mage::registry('config_system_email_template')) {
            $collection = Mage::getResourceModel('core/email_template_collection')
                ->load();

            Mage::register('config_system_email_template', $collection);
        }
        $templateName = Mage::helper('adminhtml')->__('Default Template from Locale');
        $nodeName = $this->getPath();
        $templateLabelNode = Mage::app()->getConfig()->getNode(self::XML_PATH_TEMPLATE_EMAIL . $nodeName . '/label');
        if ($templateLabelNode) {
            $templateName = Mage::helper('adminhtml')->__((string)$templateLabelNode);
            $templateName = Mage::helper('adminhtml')->__('%s (Default Template from Locale)', $templateName);
        }
        $options = array(
            -1 => "Do not send email",
            0 => $templateName
        );
        foreach($collection as $item){
            if($item->getOrigTemplateCode() == $nodeName){
                $options[$item->getTemplateId()] = $item->getTemplateCode();
            }
        }
        return $options;
    }

}
