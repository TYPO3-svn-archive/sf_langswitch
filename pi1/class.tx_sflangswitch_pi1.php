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

require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * Plugin 'Language Switch' for the 'sf_langswitch' extension.
 *
 * @author	Sebastian Fischer <sebastian@icefox.de>
 * @package	TYPO3
 * @subpackage	tx_sflangswitch
 */
class tx_sflangswitch_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_sflangswitch_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_sflangswitch_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'sf_langswitch';	// The extension key.
	var $pi_checkCHash = true;
	
	function init($conf) {
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		
		if(!isset($this->conf['langTitle'])) {
			$this->conf['langTitle'] = $this->pi_getLL('title');
		}
		if(!isset($this->conf['submitTitle'])) {
			$this->conf['submitTitle'] = $this->pi_getLL('submit');
		}
		if(!isset($this->conf['outputStyle'])) {
			$this->conf['outputStyle'] = 'SELECT';
		}
	}
	
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content,$conf)	{
		$this->init($conf);
		
		$this->conf['languages'] = $this->getLanguages($this->conf['langIds']);
		$this->conf['translations'] = $this->getTranslations();

		$content = $this->renderSwitch();

		return $this->pi_wrapInBaseClass($content);
	}

	function renderSwitch() {
		$content = '';
		reset($this->conf['languages']);

		switch ($this->conf['outputStyle']) {
			case 'LIST':
				$content = $this->renderList();
				break;
			case 'SELECT':
			default:
				$content = $this->renderSelectbox();
		}
		return $content;
	}
	
	function renderList() {
		$items = array();
		$content = '';
		
		if ($this->conf['showTitle'] && (!isset($this->conf['list.']['titleInsideList']) || $this->conf['list.']['titleInsideList'] != 1)) {
			$content .= $this->cObj->wrap($this->conf['langTitle'], $this->conf['list.']['titleWrap']);
		}
		elseif ($this->conf['showTitle'] && isset($this->conf['list.']['titleInsideList']) && $this->conf['list.']['titleInsideList'] == 1) {
			$items[] = $this->cObj->wrap($this->conf['langTitle'], $this->conf['list.']['itemNoTransWrap']);
		}

		while(list($id, $language) = each($this->conf['languages'])) {
			$linkConf = $this->conf['list.']['typolink.'];
			$linkConf['parameter'] = $GLOBALS['TSFE']->id;
			$linkConf['additionalParams'] = '&L=';
			
			$wrap = $this->conf['list.']['itemWrap'];

			if($id == $this->conf['defaultId']) {
				$link = $this->cObj->typoLink($language['title'], $linkConf);
			}
			elseif($language['translations'] == 1) {
				$linkConf['additionalParams'] = '&L='. $id;
				$link = $this->cObj->typoLink($language['title'], $linkConf);
			}
			elseif(isset($this->conf['pageIfNoTrans']) && $this->conf['pageIfNoTrans'] > 0) {
				$linkConf['parameter'] = $this->conf['pageIfNoTrans'];
				$link = $this->cObj->typoLink($language['title'], $linkConf);
			}
			else {
				$link = $language['title'];
				$wrap = $this->conf['list.']['itemNoTransWrap'];
			}
			$items[] = $this->cObj->wrap($link, $wrap);
		}

		if ($this->conf['list.']['stdWrap']) {
			$content .= $this->cObj->wrap(implode($items), $this->conf['list.']['stdWrap']);
		}
		return $content;
	}
	
	function renderSelectbox() {
		$items = array();
		$content = '';
		
		if($this->conf['showTitle'] && (!isset($this->conf['select.']['titleInsideSelect']) || $this->conf['select.']['titleInsideSelect'] != 1)) {
			$content .= $this->cObj->wrap($this->conf['langTitle'], $this->conf['select.']['titleWrap']);
		}
		elseif($this->conf['showTitle'] && isset($this->conf['select.']['titleInsideSelect']) && $this->conf['select.']['titleInsideSelect'] == 1) {
			$items[] = $this->cObj->wrap($this->conf['langTitle'], $this->conf['select.']['itemNoTransWrap']);
		}
	
		while(list($id, $language) = each($this->conf['languages'])) {
			$linkConf = $this->conf['list.']['typolink.'];
			$linkConf['parameter'] = $GLOBALS['TSFE']->id;
			$linkConf['additionalParams'] = '&L=';
			$linkConf['returnLast'] = 'url';
			
			$markerArray = array();
			$markerArray['###SELECTED###'] = '';
			$wrap = $this->conf['select.']['itemWrap'];
			
			if($id == $this->conf['defaultId']) {
				$markerArray['###VALUE###'] = $this->cObj->typoLink($language['title'], $linkConf);
			}
			elseif($language['translations'] == 1) {
				$linkConf['additionalParams'] = '&L='. $id;
				$markerArray['###SELECTED###'] = (t3lib_div::GPvar('L') == $id ? ' selected="selected"' : '');
				$markerArray['###VALUE###'] = $this->cObj->typoLink($language['title'], $linkConf);
			}
			elseif(isset($this->conf['pageIfNoTrans']) && $this->conf['pageIfNoTrans'] > 0) {
				$linkConf['parameter'] = $this->conf['pageIfNoTrans'];
				$markerArray['###SELECTED###'] = (t3lib_div::GPvar('L') == $id ? ' selected="selected"' : '');
				$markerArray['###VALUE###'] = $this->cObj->typoLink($language['title'], $linkConf);
			}
			else {
				$wrap = $this->conf['select.']['itemNoTransWrap'];
			}
			$item = $this->cObj->wrap($language['title'], $wrap);
			$items[] = $this->cObj->substituteMarkerArrayCached($item, $markerArray, $subpartArray);
		}

		$content .=	$this->cObj->wrap(implode($items), $this->conf['select.']['stdWrap']);

		$markerArray = array();
		$markerArray['###SUBMIT###'] = ($this->conf['select.']['showSubmit'] ? '<input type="submit" value="'. $this->conf['submitTitle'] .'" />' : '');
		$content = $this->cObj->substituteMarkerArrayCached($content, $markerArray, $subpartArray);
		
		return $content;
	}
	
	function getTranslations() {
		while(list($id, $language) = @each($this->conf['languages'])) {
			$select = '*';
			$from_tables = 'pages_language_overlay';
			$where_clause = 'pid = '. $GLOBALS["TSFE"]->id .' AND sys_language_uid='. $id
						. $this->cObj->enableFields('pages_language_overlay');

			$res = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows($select, $from_tables, $where_clause);

			$this->conf['languages'][$id]['translations'] = count($res);
		}
	}
	
	function getLanguages($langIds) {
		$languages = array();
		$parts = t3lib_div::intExplode(',', $langIds);
		while(list($id, $language) = each($parts)) {
			$languages[$language] = array();
		}
		
		$select = 'uid, title, flag, static_lang_isocode';
		$from_tables = 'sys_language';
		$where_clause = 'uid IN ('. $langIds .')'
						. $this->cObj->enableFields('sys_language');

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows($select, $from_tables, $where_clause);
		
		while(list($uid, $lang) = @each($res)) {
			$languages[$lang['uid']] = $lang;
			unset($languages[$lang['uid']]['uid']);
		}
		
		return $languages;
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_langswitch/pi1/class.tx_sflangswitch_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_langswitch/pi1/class.tx_sflangswitch_pi1.php']);
}

?>