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
	 */
	private array $attributes = [];

	/**
	 * Placeholder text to display for the Twitter object before loading.
	 */
	private string $placeholderText = '';

	/**
	 * Constructor
	 */
	public function __construct(array $args, string $input) {
		$this->setWidth($args['width'] ?? 300);
		$this->setHeight($args['height'] ?? 500);
		$this->setTheme($args['theme'] ?? '');
		$this->setChrome($args);
		if (isset($args['count'])) {
			$this->setTweetCount($args['count']);
		}
		$this->setLinkColor($args['linkcolor'] ?? 'black');
		$this->setBorderColor($args['bordercolor'] ?? 'black');
		$this->setType($args, $input);
	}

	/**
	 * Set the width.
	 */
	public function setWidth(mixed $width): void
    {
		$width = intval($width);
		if (!$width || $width < 180 || $width > 520) {
			$width = 300;
		}

		$this->attributes['width'] = $width;
	}

	/**
	 * Set the height.
	 */
	public function setHeight(mixed $height): void
    {
		$height = intval($height);
		if (!$height || $height < 200) {
			$height = 500;
		}

		$this->attributes['height'] = $height;
	}

	/**
	 * Background Theme Setting
	 */
	public function setTheme($background): void
    {
		if ($background) {
			$this->attributes['data-theme'] = $background;
		}
	}

	/**
	 * Set link color.
	 */
	public function setLinkColor($linkColor): void
    {
		if ($linkColor) {
			$this->attributes['data-link-color'] = $linkColor;
		}
	}

	/**
	 * Set border color.
	 */
	public function setBorderColor($borderColor): void
    {
		if ($borderColor) {
			$this->attributes['data-border-color'] = $borderColor;
		}
	}

	/**
	 * Check settings for chrome elements.
	 */
	public function setChrome(array &$args): void
    {
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
	 */
	public function setTweetCount(mixed $maximum) {
		$maximum = intval($maximum);
		if ($maximum) {
			$this->attributes['data-tweet-limit'] = $maximum;
		}
	}

	/**
	 * Check for type of list.
	 */
	public function setType(array &$args, string $input) {
		$type     = $args['type'] ?? null;
		$slug     = $args['list'] ?? null;
		$widgetid = $args['widgetid'] ?? null;

		switch($type) {
		case 'user':
		case '*':
		default:
			if (!$widgetid) {
				$widgetid = '347799644828467200';
			}
			$this->attributes['data-widget-id'] = $widgetid;
			$this->attributes['data-screen-name'] = $input;
			$this->attributes['href'] = 'https://twitter.com/'.$input;
			$this->placeholderText = wfMessage('twitter-placeholder-user', $input)->text();
			break;

		case 'favorite':
			if (!$widgetid) {
				$widgetid = '349235092381655041';
			}
			$this->attributes['data-widget-id'] = $widgetid;
			$this->attributes['data-favorites-screen-name'] = $input;
			$this->attributes['href'] = 'https://twitter.com/'.$input.'/favorites';
			$this->placeholderText = wfMessage('twitter-placeholder-favorites', $input)->text();
			break;

		case 'list':
			if (!$widgetid) {
				$widgetid = '349235479721431040';
			}
			$this->attributes['data-widget-id'] = $widgetid;
			$this->attributes['data-list-owner-screen-name'] = $input;
			$this->attributes['data-list-slug'] = $slug;
			$this->attributes['href'] = 'https://twitter.com/'.$input.'/'.$slug;
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
	 */
	public function getFeedEmbed(): string {
		$this->attributes['class'] = 'twitter-timeline';
		$embed = Html::element('a', $this->attributes, $this->placeholderText);

		// check for adding a border of our own when transparent but not noborders
		if (str_contains($this->attributes['data-chrome'], 'transparent')
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
	 * Returns the html <script> fragment to load twitter feeds onto a page.
	 */
	static public function getScriptTag(): string
    {
		return '<script type="text/javascript">window.twttr=(function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],t=window.twttr||{};if(d.getElementById(id))return;js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);t._e=[];t.ready=function(f){t._e.push(f);};return t;}(document,"script","twitter-wjs"));</script>';
	}
}
