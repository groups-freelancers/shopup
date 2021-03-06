<?php

class Infortis_Ultimo_Block_Adminhtml_System_Config_Form_Fieldset_Cssgen extends Mage_Adminhtml_Block_System_Config_Form_Field
{
	/**
     * Generate CSS
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return String
     */
	protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
	{
		$elementOriginalData = $element->getOriginalData();
		if (isset($elementOriginalData['process']))
		{
			$name = $elementOriginalData['process'];
		}
		else
		{
			return '<div>Action was not specified</div>';
		}

		$website = Mage::app()->getRequest()->getParam('website');
		$store = Mage::app()->getRequest()->getParam('store');
		$url = $this->getUrl('ultimo/adminhtml_cssgen/' . $name, array('website'=>$website, 'store'=>$store));
		
		$buttonSuffix = '';
		if ($store)
			$buttonSuffix = ' for "' . Mage::app()->getStore($store)->getName() . '" store view';
		elseif ($website)
			$buttonSuffix = ' for "' . Mage::app()->getWebsite($website)->getName() . '" website';
		else
			$buttonSuffix = ' for Default Config';
			
		$html = $this->getLayout()->createBlock('adminhtml/widget_button')
			->setType('button')
			->setClass('generate-css')
			->setLabel('Refresh CSS' . $buttonSuffix)
			->setOnClick("setLocation('$url')")
			->toHtml();
		
        return $html;
    }
}
