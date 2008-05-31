<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2007 Sebastian Fischer <sebastian@icefox.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * View 'List' for the 'sf_langswitch' extension.
 *
 * @author	Sebastian Fischer <sebastian@icefox.de>
 * @package	TYPO3
 * @subpackage	tx_sflangswitch
 */

tx_div::load('tx_lib_phpTemplateEngine');

class tx_sflangswitch_views_entry extends tx_lib_phpTemplateEngine {
	function isTranslated() {
		return ($this->get('ITEM_STATE') == 'NO' || $this->get('ITEM_STATE') == 'ACT' ? true : false);
	}
	
	function isCurrent() {
		return ($this->get('ITEM_STATE') == 'ACT' ? true : false);
	}
	
	function isFirst($key) {
		if ($key == 0) {
			return true;
		}
		return false;
	}
	
	function printLangUrl() {
		$cObject = $this->findCObject();
		$controller = $this->controller();

		if($this->isTranslated())
			return $cObject->getTypoLink_URL($GLOBALS['TSFE']->id, $this->get('_ADD_GETVARS'));
		else
			return $cObject->getTypoLink_URL($controller->configurations->get('pageIdIfNoTranslation'), $this->get('_ADD_GETVARS'));
	}
	
	function asTitle($key) {
		$controller = $this->controller();
		$this->configurations = $controller->configurations;
		if(is_object($this->configurations)) {
			$parseFuncKey = $parseFuncKey	? $parseFuncKey	: $this->parseFuncTextKey;
			$parseFunc = $this->configurations->get($parseFuncKey);
		}
		
		if ($this->configurations->get('fields.'. $key .'.')) {
			$setup['stdWrap.'] = $this->configurations->get('fields.'. $key .'.');
		}

		if(is_array($parseFunc)) {
			$setup['parseFunc.'] = $parseFunc;
		} elseif($parseFunc) {
			$setup['parseFunc'] = $parseFunc;
		} else {
			$setup['parseFunc'] = '< lib.parseFunc';
		}
		$setup['value'] = htmlspecialchars($this->get($key));
		$cObject = $this->findCObject();
		return $cObject->cObjGetSingle('TEXT', $setup);
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_langswitch/views/class.tx_sflangswitch_views_entry.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_langswitch/views/class.tx_sflangswitch_views_entry.php']);
}
?>