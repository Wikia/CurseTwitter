<?php
/**
 * Curse Inc.
 * Curse Twitter
 * Curse Twitter Mediawiki Settings
 *
 * @author		Brent Copeland
 * @copyright	(c) 2013 Curse Inc.
 * @license		GPL v3.0
 * @package		Curse Twitter
 * @link		https://github.com/HydraWiki/CurseTwitter
 *
 **/

/******************************************/
/* Credits								  */
/******************************************/
$wgExtensionCredits['parserhook'][] = [
	'path'				=> __FILE__,
	'name'				=> 'Curse Twitter',
	'author'			=> ['Wiki Platform Team'],
	'descriptionmsg'	=> 'cursetwitter_description',
	'version'			=> '1.3',
	'license-name'		=> 'GPL-3.0',
	'url'				=> 'https://github.com/HydraWiki/CacheBreaker'
];

/******************************************/
/* Language Strings, Page Aliases, Hooks  */
/******************************************/
$extDir = __DIR__;
$wgResourceModules['ext.curse.twitter'] = [
	'localBasePath' => __DIR__,
	'remoteExtPath' => 'CurseTwitter',
	'styles'        => ['css/ext.curse.twitter.css'],
	'position'		=> 'top'
];

$wgExtensionMessagesFiles['CurseTwitter']			= "{$extDir}/CurseTwitter.i18n.php";
$wgMessagesDirs['CurseTwitter']						= "{$extDir}/i18n";

$wgAutoloadClasses['CurseTwitterHooks']				= "{$extDir}/CurseTwitter.hooks.php";
$wgAutoloadClasses['CurseTwitter']					= "{$extDir}/classes/CurseTwitter.php";

$wgHooks['ParserFirstCallInit'][]					= 'CurseTwitterHooks::onParserFirstCallInit';
