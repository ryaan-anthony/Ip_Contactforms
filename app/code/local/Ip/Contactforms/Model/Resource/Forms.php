<?php

class Ip_Contactforms_Model_Resource_Forms extends Mage_Core_Model_Resource_Db_Abstract
{

    protected function _construct()
    {
        $this->_init('contactforms/forms', 'id');
    }

}