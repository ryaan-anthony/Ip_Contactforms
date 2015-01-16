<?php

class Ip_Contactforms_Block_Adminhtml_Forms_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('forms_grid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('desc');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('contactforms/forms')
            ->getCollection()
            ->addFieldToSelect('*');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header'    => Mage::helper('adminhtml')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'id',
        ));
        $this->addColumn('title', array(
            'header'    => Mage::helper('adminhtml')->__('Title'),
            'align'     =>'left',
            'index'     => 'title',
        ));
        $this->addColumn('url_key', array(
            'header'    => Mage::helper('adminhtml')->__('Url'),
            'align'     =>'left',
            'index'     => 'url_key',
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}