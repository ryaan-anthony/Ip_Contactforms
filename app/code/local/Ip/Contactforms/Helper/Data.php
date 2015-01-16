<?php

class Ip_Contactforms_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function makeUrlKey($title)
    {
        return str_replace(' ','-',
            preg_replace("/[^0-9a-zA-Z ]/", "",
                strtolower(trim($title))
            )
        );
    }

    public function filter($content)
    {
        $_processor = Mage::helper('cms')->getBlockTemplateProcessor();
        return $_processor->filter($content);
    }


}