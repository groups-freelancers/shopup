<?php
class Aurigait_CustomCheckout_Model_Checkout_Type_Onepage extends Mage_Checkout_Model_Type_Onepage
{
    
    public function saveCheckoutMethod($method)
    {
        if (empty($method)) {
            return array('error' => -1, 'message' => Mage::helper('checkout')->__('Invalid data.'));
        }

        $this->getQuote()->setCheckoutMethod($method)->save();
        $this->getCheckout()->setStepData('single', 'allow', true);
        return array();
    }
}
		