<?php

class Ip_Contactforms_Model_Resource_Fields extends Mage_Core_Model_Resource_Db_Abstract
{

    protected function _construct()
    {
        $this->_init('contactforms/fields', 'id');
    }

}