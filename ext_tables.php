<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY] = 'layout,select_key,pages,recursive';

t3lib_extMgm::addStaticFile($_EXTKEY, 'configurations', 'Language Switch');

t3lib_extMgm::addPlugin(array('LLL:EXT:sf_langswitch/configurations/locallang.xml:tt_content.controllers_switch', $_EXTKEY), 'list_type');

?>