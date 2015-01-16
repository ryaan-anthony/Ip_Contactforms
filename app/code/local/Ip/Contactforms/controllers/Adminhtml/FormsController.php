<?php

class Ip_Contactforms_Adminhtml_FormsController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $model = Mage::getModel('contactforms/forms');
        if($id = $this->getRequest()->getParam('id', null)){
            $model->load((int) $id);
        }
        Mage::register('forms_data', $model);
        $this->loadLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    public function saveAction()
    {
        $_session = Mage::getSingleton('adminhtml/session');
        if ($data = $this->getRequest()->getPost()){
            /** @var $model Ip_Blogfeed_Model_Posts */
            $model = Mage::getSingleton('contactforms/forms');
            /* set all post data */
            $model->setData($data);
            /* set the id instead of loading for simplicity */
            if($entity_id = $this->getRequest()->getParam('id', null)){
                $model->setId($entity_id);
            }
            $_session->setFormData($data);
            try {
                if(!$model->getUrlKey()){
                    $url_key = Mage::helper('contactforms')->makeUrlKey($model->getTitle());
                    $model->setUrlKey($url_key);
                }
                $model->save();
                $model->setRequestPath();

                //delete previous fields
                $fields = Mage::getModel('contactforms/fields')->getCollection()
                    ->addFieldToFilter('form_id', $model->getId());
                $fields->delete();

                //add new fields
                if(isset($data['fields'])){
                    foreach($data['fields'] as $field){
                        $new_field = Mage::getModel('contactforms/fields');
                        $field['form_id'] = $model->getId();
                        $field['required'] = (int)(isset($field['required']) && $field['required'] == 'on');
                        $new_field->setData($field);
                        $new_field->save();
                    }
                }

                $_session->setFormData(false);
                $_session->addSuccess(Mage::helper('adminhtml')->__('Contact form was successfully saved.'));
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                } else {
                    $this->_redirect('*/*/');
                }
                return;
            }
            catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            $this->_redirectReferer();
        } else {
            $_session->addError(Mage::helper('adminhtml')->__('No contact form found.'));
        }
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if( $this->getRequest()->getParam('id') > 0 ) {
            try {
                $model = Mage::getSingleton('contactforms/forms');

                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Contact form was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

}