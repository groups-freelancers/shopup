<?php
/**
 * @category   PSystem
 * @package    PSystem_Base
 * @author     Pascal System <info@pascalsystem.pl>
 * @version    1.1.2
 */

/**
 * Pascal Catalog Ajax config block
 * 
 * @category   PSystem
 * @package    PSystem_Base
 * @author     Pascal System <info@pascalsystem.pl>
 * @version    1.1.2
 */
class PSystem_Base_Block_Headerjs extends Mage_Core_Block_Abstract {
/**
 * Prepare additional js to head
 * 
 * @return string
 */
	public function _toHtml() {
		if (Mage::getStoreConfig('psbase/quickimages/disable'))
			return '';
		
		if (!$selector = Mage::getStoreConfig('psbase/quickimages/selector')) {
			$selector = 'a.product-image';
		}
		
		//outer div - ie7,ie8 - bug
		$html = '<div style="display:none;">&nbsp;<script type="text/javascript">';
		$html.= 'PS.catalogQuickViewImage.init(\''.$selector.'\');';
		$html.= '</script></div>';
		return $html;
	}
}