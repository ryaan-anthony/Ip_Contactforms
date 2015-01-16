<?php

class Ip_Contactforms_FormsController extends Mage_Core_Controller_Front_Action
{
    const XML_PATH_EMAIL_RECIPIENT  = 'contacts/email/recipient_email';
    const XML_PATH_EMAIL_SENDER     = 'contacts/email/sender_email_identity';

    public function indexAction()
    {
        if($form_id = $this->getRequest()->getParam('id', null)){
            $form = Mage::getModel('contactforms/forms')->load($form_id);
            $fields = Mage::getModel('contactforms/fields')->getCollection()
                ->addFieldToFilter('form_id', $form->getId())
                ->setOrder('position', 'ASC');
            $form->setFields($fields);
            Mage::register('forms_data', $form);
            $this->loadLayout();
            $this->renderLayout();
        } else {
            $this->norouteAction();
        }
    }


    public function postAction()
    {
        if($form_id = $this->getRequest()->getParam('id', null)){
            $form = Mage::getModel('contactforms/forms')->load($form_id);
            if ($post = $this->getRequest()->getPost()) {
                $translate = Mage::getSingleton('core/translate');
                /* @var $translate Mage_Core_Model_Translate */
                $translate->setTranslateInline(false);
                try {
                    $fields = array();
                    $error = false;
                    foreach($post['field'] as $key => $value){
                        $field = Mage::getModel('contactforms/fields')->load($key);
                        if($field->getId()){
                            if($field->getRequired()){
                                if (!Zend_Validate::is(trim($value) , 'NotEmpty')) {
                                    $error = true;
                                }
                            }
                            if($field->getType() == 'email'){
                                if (!Zend_Validate::is(trim($value), 'EmailAddress')) {
                                    $error = true;
                                }
                            }
                            $fields[$field->getCode()] = $value;
                        }
                    }
                    if (Zend_Validate::is(trim($post['hideit']), 'NotEmpty')) {
                        $error = true;
                    }

                    // handle validation errors
                    if ($error) {
                        throw new Exception();
                    }

                    //set post data for email transactionals
                    $postObject = new Varien_Object();
                    $postObject->setData($fields);

                    $sender = array('name' => Mage::getStoreConfig('trans_email/ident_general/name'), 'email' => Mage::getStoreConfig('trans_email/ident_general/email'));
                    $recipient = Mage::getStoreConfig('trans_email/ident_general/email');

                    //send request email
                    if($form->getRequestTemplate() >= 0){
                        $template = $form->getRequestTemplate() ?
                            $form->getRequestTemplate() :
                            $form::DEFAULT_REQUEST_TEMPLATE;
                        $mailTemplate = Mage::getModel('core/email_template');
                        /* @var $mailTemplate Mage_Core_Model_Email_Template */
                        $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                            ->setReplyTo($fields['email'])
                            ->sendTransactional(
                                $template,
                                $sender,
                                $recipient,
                                null,
                                array('data' => $postObject)
                            );
                        if (!$mailTemplate->getSentSuccess()) {
                            throw new Exception();
                        }
                    }

                    //send response email
                    if($form->getResponseTemplate() >= 0){
                        $template = $form->getResponseTemplate() ?
                            $form->getResponseTemplate() :
                            $form::DEFAULT_RESPONSE_TEMPLATE;
                        $mailTemplate = Mage::getModel('core/email_template');
                        /* @var $mailTemplate Mage_Core_Model_Email_Template */
                        $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                            ->setReplyTo($recipient)
                            ->sendTransactional(
                                $template,
                                $sender,
                                $fields['email'],
                                null,
                                array('data' => $postObject)
                            );
                        if (!$mailTemplate->getSentSuccess()) {
                            throw new Exception();
                        }
                    }



                    $translate->setTranslateInline(true);

                    Mage::getSingleton('core/session')->addSuccess(Mage::helper('contactforms')->__('Your inquiry was submitted and will be responded to as soon as possible. Thank you for contacting us.'));
                    session_write_close();
                    $this->_redirectReferer();

                    return;
                } catch (Exception $e) {
                    $translate->setTranslateInline(true);
                    Mage::getSingleton('core/session')->addError(Mage::helper('contactforms')->__('Unable to submit your request. Please, try again later'));
                    session_write_close();
                    $this->_redirectReferer();
                    return;
                }
            } else {
                session_write_close();
                $this->_redirectReferer();
            }
        } else {
            session_write_close();
            $this->_redirectReferer();
        }
        exit;
    }

}