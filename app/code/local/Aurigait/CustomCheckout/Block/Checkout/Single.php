<?php
class Aurigait_CustomCheckout_Block_Checkout_Single extends Mage_Checkout_Block_Onepage_Abstract
{
    protected $_shippingaddress = null;
    protected $_billingaddress;
    protected function _construct()
    {
        $this->getCheckout()->setStepData('single', array(
            'label'     => Mage::helper('checkout')->__('Billing/Shipping'),
            'is_show'   => 1
        ));

        if ($this->isCustomerLoggedIn()) {
            $this->getCheckout()->setStepData('single', 'allow', true);
        }
        parent::_construct();
    } 
    
    public function isShow()
    {
        return true;
    }
    public function getBillingAddress()
    {
        if (is_null($this->_billingaddress)) {
            if ($this->isCustomerLoggedIn()) {
                $this->_billingaddress = $this->getQuote()->getBillingAddress();
                if(!$this->_billingaddress->getFirstname()) {
                    $this->_billingaddress->setFirstname($this->getQuote()->getCustomer()->getFirstname());
                }
                if(!$this->_billingaddress->getLastname()) {
                    $this->_billingaddress->setLastname($this->getQuote()->getCustomer()->getLastname());
                }
            } else {
                $this->_billingaddress = Mage::getModel('sales/quote_address');
            }
        }

        return $this->_billingaddress;
    }
    
    public function isUseBillingAddressForShipping()
    {
        if (($this->getQuote()->getIsVirtual())
            || !$this->getQuote()->getShippingAddress()->getSameAsBilling()) {
            return false;
        }
        return true;
    }
    
    public function getAddressesHtmlSelect($type)
    {
        if ($this->isCustomerLoggedIn()) {
            $options = array();
            foreach ($this->getCustomer()->getAddresses() as $address) {
                $options[] = array(
                    'value' => $address->getId(),
                    'label' => $address->format('oneline')
                );
            }

            if ($type=='billing') {
                 $addressId = $this->getBillingAddress()->getCustomerAddressId();
            }
            else
            {
              $addressId = $this->getShippingAddress()->getCustomerAddressId();  
            }
           
            if (empty($addressId)) {
                if ($type=='billing') {
                    $address = $this->getCustomer()->getPrimaryBillingAddress();
                } else {
                    $address = $this->getCustomer()->getPrimaryShippingAddress();
                }
                if ($address) {
                    $addressId = $address->getId();
                }
            }

            $select = $this->getLayout()->createBlock('core/html_select')
                ->setName($type.'_address_id')
                ->setId($type.'-address-select')
                ->setClass('address-select')
                ->setExtraParams('onchange="'.$type.'.newAddress(!this.value)"')
                ->setValue($addressId)
                ->setOptions($options);

            $select->addOption('', Mage::helper('checkout')->__('New Address'));

            return $select->getHtml();
        }
        return '';
    }
    
     public function getCountryHtmlSelect($type)
    {
          if ($type=='billing') {
            $countryId = $this->getBillingAddress()->getCountryId();
          }
          else
          {
             $countryId = $this->getShippingAddress()->getCountryId(); 
          }
        
        if (is_null($countryId)) {
            $countryId = Mage::helper('core')->getDefaultCountry();
        }
        $select = $this->getLayout()->createBlock('core/html_select')
            ->setName($type.'[country_id]')
            ->setId($type.':country_id')
            ->setTitle(Mage::helper('checkout')->__('Country'))
            ->setClass('validate-select')
            ->setValue($countryId)
            ->setOptions($this->getCountryOptions());
        if ($type === 'shipping') {
            $select->setExtraParams('onchange="if(window.shipping)shipping.setSameAsBilling(false);"');
        }

        return $select->getHtml();
    }
     public function getCountries()
    {
        return Mage::getResourceModel('directory/country_collection')->loadByStore();
    }
    
    public function canShip()
    {
        return !$this->getQuote()->isVirtual();
    }
    public function getShippingAddress()
    {
        if (is_null($this->_shippingaddress)) {
            if ($this->isCustomerLoggedIn()) {
                $this->_shippingaddress = $this->getQuote()->getShippingAddress();
            } else {
                $this->_shippingaddress = Mage::getModel('sales/quote_address');
            }
        }

        return $this->_shippingaddress;
    }
    
    protected function _getTaxvat()
    {
        if (!$this->_taxvat) {
            $this->_taxvat = $this->getLayout()->createBlock('customer/widget_taxvat');
        }

        return $this->_taxvat;
    }

    /**
     * Check whether taxvat is enabled
     *
     * @return bool
     */
    public function isTaxvatEnabled()
    {
        return $this->_getTaxvat()->isEnabled();
    }

    public function getTaxvatHtml()
    {
        return $this->_getTaxvat()
            ->setTaxvat($this->getQuote()->getCustomerTaxvat())
            ->setFieldIdFormat('billing:%s')
            ->setFieldNameFormat('billing[%s]')
            ->toHtml();
    }
}
?>
