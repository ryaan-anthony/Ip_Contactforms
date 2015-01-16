<?php

class Ip_Contactforms_Model_Resource_Fields_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('contactforms/fields');
    }

    public function delete()
    {
        foreach($this as $item){
            $item->delete();
        }
    }

}