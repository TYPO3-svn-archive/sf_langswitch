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
 * Model 'Languages' for the 'sf_langswitch' extension.
 *
 * @author	Sebastian Fischer <sebastian@icefox.de>
 * @package	TYPO3
 * @subpackage	tx_sflangswitch
 */

class tx_sflangswitch_models_languages extends tx_lib_object {
	function tx_sflangswitch_models_languages($controller = null, $parameter = null) {
		parent::tx_lib_object($controller, $parameter);
	}
	
	function load($parameters = null) {
		$cObject = $this->findCObject();
		
		$currentPageWithNoOverlay = $GLOBALS['TSFE']->sys_page->getRawRecord('pages', $GLOBALS['TSFE']->page['uid']);
		$titles = t3lib_div::trimExplode('||', $this->controller->configurations->get('labels'));

		$languageItems = t3lib_div::intExplode(',', $this->controller->configurations->get('languageUids'));
		foreach($languageItems as $sUid) {
			if ($sUid) {
				$lRecs = $GLOBALS['TSFE']->sys_page->getPageOverlay($GLOBALS['TSFE']->page['uid'], $sUid);
			} else {
				$lRecs = array();
			}

			if ($this->controller->configurations->get('addQueryString')) {
				$getVars = $cObject->getQueryArguments($this->controller->configurations->get('addQueryString.'), array('L' => $sUid), true);
			} else {
				$getVars = array('L' => $sUid);
			}

			if ((t3lib_div::hideIfNotTranslated($GLOBALS['TSFE']->page['l18n_cfg']) && $sUid && !count($lRecs)) // Blocking for all translations?
				|| ($GLOBALS['TSFE']->page['l18n_cfg']&1 && (!$sUid || !count($lRecs))) // Blocking default translation?
				|| (!$this->conf['special.']['normalWhenNoLanguage'] && $sUid && !count($lRecs)))	{
				$iState = ($GLOBALS['TSFE']->sys_language_uid == $sUid ? 'USERDEF2' : 'USERDEF1');
			} else {
				$iState = ($GLOBALS['TSFE']->sys_language_uid == $sUid ? 'ACT' : 'NO');
			}

			$language = array_merge(
				array_merge($currentPageWithNoOverlay, $lRecs),
				array(
					'ITEM_STATE' => $iState,
					'_ADD_GETVARS' => $getVars,
					'_SAFE' => TRUE
				)
			);

			if (is_array($titles)) {
				$language['title'] = current($titles);
				next($titles);
				if ((key($titles)) == count($titles)) {
					reset($titles);
				}
			}

			$entry = new tx_lib_object($language);
			$this->append($entry);
		}
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_langswitch/models/class.tx_sflangswitch_models_languages.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_langswitch/models/class.tx_sflangswitch_models_languages.php']);
}
?>
