<?php


class Ip_Contactforms_Block_Adminhtml_Forms extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    protected $_addButtonLabel = 'Add New Form';

    public function __construct()
    {
        parent::__construct();
        $this->_controller = 'adminhtml_forms';
        $this->_blockGroup = 'contactforms';
        $this->_headerText = Mage::helper('adminhtml')->__('Contact Forms');
    }

}