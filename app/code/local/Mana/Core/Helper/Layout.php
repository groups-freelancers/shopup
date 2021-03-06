<?php
/**
 * @category    Mana
 * @package     Mana_Core
 * @copyright   Copyright (c) http://www.manadev.com
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * @author Mana Team
 *
 */
class Mana_Core_Helper_Layout extends Mage_Core_Helper_Abstract {
    protected $_delayPrepareLayoutBlocks = array();
    /**
     * @param Mage_Core_Block_Abstract $block
     */
    public function delayPrepareLayout($block, $sortOrder = 0) {
        if (Mage::registry('m_page_is_being_rendered')) {
            $block->delayedPrepareLayout();
        }
        else {
            $this->_delayPrepareLayoutBlocks[$block->getNameInLayout()] = compact('block', 'sortOrder');
        }
    }
    public function prepareDelayedLayoutBlocks() {
        uasort($this->_delayPrepareLayoutBlocks, array($this, '_compareBlocks'));
        foreach ($this->_delayPrepareLayoutBlocks as $block) {
            $block['block']->delayedPrepareLayout();
        }
    }

    public function _compareBlocks($a, $b) {
        if ($a['sortOrder'] < $b['sortOrder']) return -1;
        if ($a['sortOrder'] > $b['sortOrder']) return 1;
        return 0;
    }

    public function renderBlock($blockName) {
        /* @var $layout Mage_Core_Model_Layout */
        $layout = Mage::getSingleton('core/layout');

        /* @var $url Mage_Core_Model_Url */
        $url = Mage::getSingleton('core/url');
        if ($block = $layout->getBlock($blockName)) {
            return $url->sessionUrlVar($block->toHtml());
        }
        else {
            return '';
        }
    }
}