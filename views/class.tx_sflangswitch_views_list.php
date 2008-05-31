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

class tx_sflangswitch_views_list extends tx_lib_phpTemplateEngine {
	function showTitle() {
		$controller = $this->controller();
		return ($controller->configurations->get('showTitle') ? true : false);
	}
	
	function render($configurationKeyOrFileName) {
		$cObject = $this->findCObject();
		
		$this->checkController(__FILE__, __LINE__);
		$path = $this->getPathToTemplateDirectory();
		$path .= substr($path, -1, 1) == '/' ? '': '/';
		$path .= strlen($this->controller->configurations->get($configurationKeyOrFileName))
				? $this->controller->configurations->get($configurationKeyOrFileName) : $configurationKeyOrFileName;
		$path .= substr($path, -5, 5) == '.html' ? '' :  '.html';
		$page = $cObject->fileResource($path);

		$markers = array();
		$subPartMarkers = array();
		
		$subPartMarkers['###SHOWTITLE###'] = $this->showTitle() ? $cObject->getSubpart($page, '###SHOWTITLE###') : '';
		$subPartMarkers['###LISTITEM_LINK###'] = $cObject->getSubpart($page, '###LISTITEM_LINK###');
		$subPartMarkers['###LISTITEM_NOLINK###'] = $cObject->getSubpart($page, '###LISTITEM_NOLINK###');

		for($this->rewind(); $this->valid(); $this->next()){
			$key = $this->key();
			$entry = $this->current();

			$itemMarkers = array();
			$itemMarkers['###CURRENT###'] = $entry->isCurrent() ? 'activ' : 'normal';
			$itemMarkers['###CURRENT###'] .= $entry->isFirst($key) ? ' first' : '';
			$itemMarkers['###LINK###'] = $entry->printLangUrl();
			$itemMarkers['###TITLE###'] = $entry->asTitle('title');
			$subPartMarkers['###LISTITEMS###'][] = $cObject->substituteMarkerArrayCached($entry->isTranslated() || $this->controller->configurations->get('pageIdIfNoTranslation') ? $subPartMarkers['###LISTITEM_LINK###'] : $subPartMarkers['###LISTITEM_NOLINK###'], $itemMarkers);
		}

		$subPartMarkers['###LISTITEMS###'] = implode('', $subPartMarkers['###LISTITEMS###']);
		$subPartMarkers['###LIST###'] = $cObject->substituteMarkerArrayCached($cObject->getSubpart($page, '###LIST###'), $markers, $subPartMarkers, array ());
		
		$page = $cObject->substituteMarkerArrayCached($page, $markers, $subPartMarkers, array ());
		
		$this->set('_content', $page);
	}

	function getPathToTemplateDirectory() {
		$pathToTemplateDirectory = $this->pathToTemplateDirectory ?  $this->pathToTemplateDirectory :
			$this->controller->configurations->get('pathToTemplateDirectory');
		if(!$pathToTemplateDirectory) 
			$this->_die(__FILE__, __LINE__, 'Please set the path to the template directory. 
			Do it either with the method setPathToTemplateDirectory($path) 
			or by choosing the default name "pathToTemplateDirectory" in the TS setup.');
		return $pathToTemplateDirectory;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_langswitch/views/class.tx_sflangswitch_views_list.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_langswitch/views/class.tx_sflangswitch_views_list.php']);
}
?>