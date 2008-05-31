<?php

########################################################################
# Extension Manager/Repository config file for ext: "sf_langswitch"
#
# Auto generated 10-12-2007 12:10
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Language Switch',
	'description' => 'Offer a language switch in a more comfortable way',
	'category' => 'fe',
	'author' => 'Sebastian Fischer',
	'author_email' => 's.fischer@b-und-h.de',
	'shy' => '',
	'dependencies' => 'lib',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'author_company' => 'Buttgereit und Heidenreich',
	'version' => '0.3.2',
	'constraints' => array(
		'depends' => array(
			'lib' => '0.1.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:13:{s:9:"ChangeLog";s:4:"97df";s:12:"ext_icon.gif";s:4:"ec72";s:17:"ext_localconf.php";s:4:"9a4f";s:14:"ext_tables.php";s:4:"a1ce";s:28:"configurations/locallang.xml";s:4:"ddbe";s:24:"configurations/setup.txt";s:4:"087d";s:56:"controllers/class.tx_sflangswitch_controllers_switch.php";s:4:"40fd";s:49:"models/class.tx_sflangswitch_models_languages.php";s:4:"fb4b";s:19:"templates/list.html";s:4:"8a7a";s:21:"templates/select.html";s:4:"b0cf";s:43:"views/class.tx_sflangswitch_views_entry.php";s:4:"8b1e";s:42:"views/class.tx_sflangswitch_views_list.php";s:4:"27a7";s:44:"views/class.tx_sflangswitch_views_select.php";s:4:"d50e";}',
	'suggests' => array(
	),
);

?>