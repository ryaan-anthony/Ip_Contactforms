<?php

class Ip_Contactforms_Model_Resource_Forms_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('contactforms/forms');
    }
}