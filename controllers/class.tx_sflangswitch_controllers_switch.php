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
 * Controller 'Switch' for the 'sf_langswitch' extension.
 *
 * @author	Sebastian Fischer <sebastian@icefox.de>
 * @package	TYPO3
 * @subpackage	tx_sflangswitch
 */

tx_div::load('tx_lib_controller');

class tx_sflangswitch_controllers_switch extends tx_lib_controller {

	var $defaultAction = 'renderListAction';

	function renderListAction($parameter1 = null, $parameter2 = null) {
		return $this->renderBoth($parameter1, $parameter2, 'tx_sflangswitch_views_list', 'listTemplate');
	}
	
	function renderSelectAction($parameter1 = null, $parameter2 = null) {
		return $this->renderBoth($parameter1, $parameter2, 'tx_sflangswitch_views_select', 'selectTemplate');
	}

	function renderBoth($parameter1, $parameter2, $viewClassName, $templateName) {
		parent::tx_lib_controller($parameter1, $parameter2);
		$this->setDefaultDesignator('tx_sflangswitch');
		
		$model = $this->makeInstance('tx_sflangswitch_models_languages');
		$model->load();
		
		$view = $this->makeInstance($viewClassName, $model);
		$view->castElements('tx_sflangswitch_views_entry');
		$view->render($this->configurations->get($templateName));
		
		$translator = $this->makeInstance('tx_lib_translator', $view);
		return $translator->translateContent();
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_langswitch/controllers/class.tx_sflangswitch_controllers_switch.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sf_langswitch/controllers/class.tx_sflangswitch_controllers_switch.php']);
}
?>
