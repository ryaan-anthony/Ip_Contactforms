<?php

class Ip_Contactforms_Model_Forms extends Mage_Core_Model_Abstract
{

    const DEFAULT_REQUEST_TEMPLATE = "contactforms_request_template";
    const DEFAULT_RESPONSE_TEMPLATE = "contactforms_response_template";

    protected function _construct()
    {
        $this->_init('contactforms/forms');
    }

    public function setRequestPath()
    {
        $frontName = Mage::getConfig()->getNode('frontend/routers/contactforms/args/frontName');
        $id_path = $frontName.'/'.$this->getId();
        $target_path = $frontName.'/forms/index/id/'.$this->getId();
        //delete if already exists
        $exists = Mage::getModel('core/url_rewrite')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->loadByIdPath($id_path);

        if($exists && $exists->getIdPath()){
            $exists->delete();
        }
        //save new
        Mage::getModel('core/url_rewrite')->setIsSystem(0)
            ->setOptions('')
            ->setIdPath($id_path)
            ->setTargetPath($target_path)
            ->setRequestPath($this->getUrlKey())
            ->save();
    }

}