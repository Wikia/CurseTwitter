<?php

use MediaWiki\Hook\ParserFirstCallInitHook;

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

class CurseTwitterHooks implements ParserFirstCallInitHook {
	/**
	 * Sets up this extensions parser functions.
	 *
	 * @access	public
	 * @param	object $parser Parser object passed as a reference.
	 */
	public function onParserFirstCallInit($parser): void {
		$parser->setHook("twitterfeed", [ $this, 'embedTwitter' ]);
	}

	/**
	 * Call proper embed data
	 *
	 * @access public
	 * @param string $input input
	 * @param array $args args
	 * @return array HTML
	 */
	public function embedTwitter($input, array $args, Parser $parser, PPFrame $frame) {
		$input = $parser->recursiveTagParse($input, $frame);
		$twitter = new CurseTwitter($args, $input);
		$parser->getOutput()->addModuleStyles(['ext.curse.twitter']);
		return [$twitter->getFeedEmbed().CurseTwitter::getScriptTag(), 'noparse' => true, 'isHTML' => true];
	}
}
