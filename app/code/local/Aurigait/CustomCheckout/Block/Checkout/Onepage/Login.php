<?php
class Aurigait_CustomCheckout_Block_Checkout_Onepage_Login extends Mage_Checkout_Block_Onepage_Login
{
    protected function _construct()
    {
        if (!$this->isCustomerLoggedIn()) {
            $this->getCheckout()->setStepData('login', array('label'=>Mage::helper('checkout')->__('Login'), 'allow'=>true));
        }
       // parent::_construct();
    }
}
			