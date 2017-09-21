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

if ( function_exists( 'wfLoadExtension' ) ) {
	wfLoadExtension( 'CurseTwitter' );
	// Keep i18n globals so mergeMessageFileList.php doesn't break
	$wgMessagesDirs['CurseTwitter'] = __DIR__ . '/i18n';
	wfWarn(
		'Deprecated PHP entry point used for CurseTwitter extension. Please use wfLoadExtension instead, ' .
		'see https://www.mediawiki.org/wiki/Extension_registration for more details.'
	);
	return;
 } else {
	die( 'This version of the CurseTwitter extension requires MediaWiki 1.25+' );
}
