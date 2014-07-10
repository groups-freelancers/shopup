<?php
require_once "Mage/Checkout/controllers/OnepageController.php";  
class Aurigait_CustomCheckout_Checkout_OnepageController extends Mage_Checkout_OnepageController{

    
    
    public function saveBillingShippingAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        if ($this->getRequest()->isPost()) {
            
            
            //save billing info
            $data = $this->getRequest()->getPost('billing', array());
            $customerAddressId = $this->getRequest()->getPost('billing_address_id', false);

            if (isset($data['email'])) {
                $data['email'] = trim($data['email']);
            }
            
            $result = $this->getOnepage()->saveBilling($data, $customerAddressId);
            
            //save shipping
            if (!isset($result['error'])) {
                /* check quote for virtual */
                if ($this->getOnepage()->getQuote()->isVirtual()) {
                    $result['goto_section'] = 'payment';
                    $result['update_section'] = array(
                        'name' => 'payment-method',
                        'html' => $this->_getPaymentMethodsHtml()
                    );
                } elseif (isset($data['use_for_shipping']) && $data['use_for_shipping'] == 1) {

                	$method = 'freeshipping_freeshipping';
                	$result = $this->getOnepage()->saveShippingMethod($method);
	                if (!isset($result['error'])) {
	    
	    				Mage::dispatchEvent('checkout_controller_onepage_save_shipping_method',
                        array('request'=>$this->getRequest(),
                            'quote'=>$this->getOnepage()->getQuote()));
		                $this->getOnepage()->getQuote()->collectTotals();
		                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
		
		                $result['goto_section'] = 'payment';
		                $result['update_section'] = array(
		                    'name' => 'payment-method',
		                    'html' => $this->_getPaymentMethodsHtml()
		                );
	    			}

                   
                } else {
                    //$result['goto_section'] = 'shipping';
                    $data = $this->getRequest()->getPost('shipping', array());
                    $customerAddressId = $this->getRequest()->getPost('shipping_address_id', false);
                    
                    $result = $this->getOnepage()->saveShipping($data, $customerAddressId);

                	if (!isset($result['error'])) {
    					$method = 'freeshipping_freeshipping';
    					$result = $this->getOnepage()->saveShippingMethod($method);
    
		    			if (!isset($result['error'])) {
		    
		    				Mage::dispatchEvent('checkout_controller_onepage_save_shipping_method',
                        	array('request'=>$this->getRequest(),
                            'quote'=>$this->getOnepage()->getQuote()));
			                $this->getOnepage()->getQuote()->collectTotals();
			                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
			
			                $result['goto_section'] = 'payment';
			                $result['update_section'] = array(
			                    'name' => 'payment-method',
			                    'html' => $this->_getPaymentMethodsHtml()
			                );
		    			}
    				}
                    
                    

                }
            }
            $this->getOnepage()->getQuote()->collectTotals()->save();
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
         
            
        }
        
    }
    
    public function saveShippingAction()
    {
    	if ($this->_expireAjax()) {
    		return;
    	}
    	if ($this->getRequest()->isPost()) {
    		$data = $this->getRequest()->getPost('shipping', array());
    		$customerAddressId = $this->getRequest()->getPost('shipping_address_id', false);
    		$result = $this->getOnepage()->saveShipping($data, $customerAddressId);
    
    		if (!isset($result['error'])) {
    			$method = 'freeshipping_freeshipping';
    			$result = $this->getOnepage()->saveShippingMethod($method);
    
    			if (!isset($result['error'])) {
    
    				$result['goto_section'] = 'payment';
    				$result['update_section'] = array(
    						'name' => 'payment-method',
    						'html' => $this->_getPaymentMethodsHtml()
    				);
    			}
    		}
    		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    	}
    }
  
}
?>
