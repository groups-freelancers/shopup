<?php
class Aurigait_CustomCheckout_Block_Checkout_Onepage extends Mage_Checkout_Block_Onepage
{
    
     public function getSteps()
    {
        $steps = array();
         $stepCodes =array('login','single','payment','review');
         if ($this->isCustomerLoggedIn()) {
            $stepCodes = array_diff($stepCodes, array('login'));
        }
        foreach ($stepCodes as $step) {
            $steps[$step] = $this->getCheckout()->getStepData($step);
        }
        
        return $steps;
    }
     public function getActiveStep()
    {
        return $this->isCustomerLoggedIn() ? 'single' : 'login';
    }
}
			