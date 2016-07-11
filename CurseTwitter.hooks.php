<?php
/**
 * Curse Inc.
 * Curse Twitter
 * Curse Twitter Hooks
 *
 * @author		Brent Copeland
 * @copyright	(c) 2013 Curse Inc.
 * @license		GPL v3.0
 * @package		Curse Twitter
 * @link		https://github.com/HydraWiki/CurseTwitter
 *
 **/

class CurseTwitterHooks {
	/**
	 * Sets up this extensions parser functions.
	 *
	 * @access	public
	 * @param	object	Parser object passed as a reference.
	 * @return	boolean true
	 */
	static public function onParserFirstCallInit(Parser &$parser) {
		$parser->setHook("twitterfeed", "CurseTwitterHooks::embedTwitter");

		return true;
	}

	/**
	 * Call proper embed data
	 *
	 * @access public
	 * @param string input
	 * @param array args
	 * @return string HTML
	 */
	static public function embedTwitter($input, array $args, Parser $parser, PPFrame $frame) {
		$input = $parser->recursiveTagParse($input, $frame);
		$twitter = new CurseTwitter($args, $input);
		$parser->getOutput()->addModuleStyles(['ext.curse.twitter']);
		return [$twitter->getFeedEmbed().CurseTwitter::getScriptTag(), 'noparse' => true, 'isHTML' => true];
	}
}
