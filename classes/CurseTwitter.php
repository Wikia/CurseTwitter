<?php
/**
 * Curse Inc.
 * Curse Twitter
 * Twitter Functions
 *
 * @author		Brent Copeland
 * @copyright	(c) 2013 Curse Inc.
 * @license		GPL v3.0
 * @package		Curse Twitter
 * @link		https://github.com/HydraWiki/CurseTwitter
 *
 **/

class CurseTwitter {
	/**
	 * Attributes for the Twitter object.
	 *
	 * @var		array
	 */
	private $attributes = [];

	/**
	 * Placeholder text to display for the Twitter object before loading.
	 *
	 * @var		array
	 */
	private $placeholderText = '';

	/**
	 * Constructor
	 * @param array    attributes provided inside the twitter tag
	 * @param string   contents of the twitter tag
	 */
	public function __construct($args, $input) {
		$this->setWidth(isset($args['width']) ? $args['width'] : 300);
		$this->setHeight(isset($args['height']) ? $args['height'] : 500);
		$this->setTheme(isset($args['theme']) ? $args['theme'] : '');
		$this->setChrome($args);
		if (isset($args['count'])) {
			$this->setTweetCount($args['count']);
		}
		$this->setLinkColor(isset($args['linkcolor']) ? $args['linkcolor'] : 'black');
		$this->setBorderColor(isset($args['bordercolor']) ? $args['bordercolor'] : 'black');
		$this->setType($args, $input);
	}

	/**
	 * Set the width.
	 *
	 * @access	public
	 * @param	array	User supplied width.
	 * @return	string	Width
	 */
	public function setWidth($width) {
		$width = intval($width);
		if (!$width || $width < 180 || $width > 520) {
			$width = 300;
		}

		$this->attributes['width'] = $width;
	}

	/**
	 * Set the height.
	 *
	 * @access	public
	 * @param	array	User supplied height.
	 * @return	string	Height
	 */
	public function setHeight($height) {
		$height = intval($height);
		if (!$height || $height < 200) {
			$height = 500;
		}

		$this->attributes['height'] = $height;
	}

	/**
	 * Background Theme Setting
	 *
	 * @access	public
	 * @param	array	Background Theme
	 * @return	string	$theme
	 */
	public function setTheme($background) {
		if ($background) {
			$this->attributes['data-theme'] = $background;
		}
	}

	/**
	 * Set link color.
	 *
	 * @access	public
	 * @param	array	Link Color
	 * @return	string	$links
	 */
	public function setLinkColor($linkColor) {
		if ($linkColor) {
			$this->attributes['data-link-color'] = $linkColor;
		}
	}

	/**
	 * Set border color.
	 *
	 * @access	public
	 * @param	array	Border Color
	 * @return	string	$borders
	 */
	public function setBorderColor($borderColor) {
		if ($borderColor) {
			$this->attributes['data-border-color'] = $borderColor;
		}
	}

	/**
	 * Check settings for chrome elements.
	 *
	 * @access	public
	 * @param	array	args
	 * @return	string	$chrome
	 */
	public function setChrome(&$args) {
		$chrome = [];
		if (array_key_exists('noscrollbar', $args)) {
			$chrome[] = 'noscrollbar';
		}

		if (array_key_exists('noheader', $args)) {
			$chrome[] = 'noheader';
		}

		if (array_key_exists('nofooter', $args)) {
			$chrome[] = 'nofooter';
		}

		if (array_key_exists('noborders', $args)) {
			$chrome[] = 'noborders';
		}

		if (array_key_exists('transparent', $args) || array_key_exists('nobackground', $args)) {
			$chrome[] = 'transparent';
		}

		$this->attributes['data-chrome'] = implode(' ', $chrome);
	}

	/**
	 * Maximum number of tweets to show.
	 *
	 * @access	public
	 * @param	array	Maximum number of tweets.
	 * @return	string	$tweetcount
	 */
	public function setTweetCount($maximum) {
		$maximum = intval($maximum);
		if ($maximum) {
			$this->attributes['data-tweet-limit'] = $maximum;
		}
	}

	/**
	 * Check for type of list.
	 *
	 * @access	public
	 * @param	array	args
	 * @param	string	$input
	 * @return	string	$type
	 */
	public function setType(&$args, $input) {
		$type     = isset($args['type']) ? $args['type'] : null;
		$slug     = isset($args['list']) ? $args['list'] : null;
		$widgetid = isset($args['widgetid']) ? $args['widgetid'] : null;

		switch($type) {
		case 'user':
		case '*':
		default:
			if (!$widgetid) {
				$widgetid = '347799644828467200';
			}
			$this->attributes['data-widget-id'] = $widgetid;
			$this->attributes['data-screen-name'] = $input;
			$this->attributes['href'] = 'http://twitter.com/'.$input;
			$this->placeholderText = wfMessage('twitter-placeholder-user', $input)->text();
			break;

		case 'favorite':
			if (!$widgetid) {
				$widgetid = '349235092381655041';
			}
			$this->attributes['data-widget-id'] = $widgetid;
			$this->attributes['data-favorites-screen-name'] = $input;
			$this->attributes['href'] = 'http://twitter.com/'.$input.'/favorites';
			$this->placeholderText = wfMessage('twitter-placeholder-favorites', $input)->text();
			break;

		case 'list':
			if (!$widgetid) {
				$widgetid = '349235479721431040';
			}
			$this->attributes['data-widget-id'] = $widgetid;
			$this->attributes['data-list-owner-screen-name'] = $input;
			$this->attributes['data-list-slug'] = $slug;
			$this->attributes['href'] = 'http://twitter.com/'.$input.'/'.$slug;
			$this->placeholderText = wfMessage('twitter-placeholder-list', $input)->text();
			break;

		case 'search':
			$this->attributes['data-widget-id'] = $widgetid;
			$this->placeholderText = 'Tweets';
			break;
		}
	}

	/**
	 * Generate Twitter Feed Embed
	 *
	 * @access	public
	 * @return	string	HTML
	 */
	public function getFeedEmbed() {
		$this->attributes['class'] = 'twitter-timeline';
		$embed = Html::element('a', $this->attributes, $this->placeholderText);

		// check for adding a border of our own when transparent but not noborders
		if (strstr($this->attributes['data-chrome'], 'transparent') !== false
			&& !empty($this->attributes['data-border-color']) ) {
			$embed = Html::rawElement('div',
				[
					'class' => 'twitter-timeline-wrapper',
					'style' => 'border-color:'.htmlspecialchars($this->attributes['data-border-color']),
				],
				$embed
			);
		}

		return $embed;
	}

	/**
	 * Returns the html <script> fragment to load twitter feeds onto a page
	 * @access public
	 * @return string
	 */
	static public function getScriptTag() {
		return '<script>window.twttr=(function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],t=window.twttr||{};if(d.getElementById(id))return;js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);t._e=[];t.ready=function(f){t._e.push(f);};return t;}(document,"script","twitter-wjs"));</script>';
	}
}
