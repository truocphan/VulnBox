<?php


namespace ANP\Popup\Plugin;
use ANP\NotificationEnqueueControl;
use ANP\Popup\ItemBase;
use ANP\WpOrgPluginInfo;

class ItemRate extends ItemBase {

	protected $pluginSlug = '';
	protected $skipBtnKey = '';
	protected $event = '';

	public function __construct($pluginSlug, $imgLink, $title, $desc, $actionBtnLink, $skipBtnKey, $event = '') {
		parent::__construct( $imgLink, $title, $desc, $actionBtnLink, $skipBtnKey );
		$this->pluginSlug = $pluginSlug;
		$this->skipBtnKey = $skipBtnKey;
		$this->event = $event;
	}

	public function createHtml() {
		return sprintf(
			$this->getItemTemplate(),
			'anp-item-rating-wrap',
			$this->getImg(),
			$this->getTitle() . $this->getDesc() . $this->getRatings(),
			$this->getHiddenInputs() . $this->getActionBtn() . $this->getSkipBtn()
		);
	}

	private function getRatings() {
		$wpInfo = new WpOrgPluginInfo($this->pluginSlug);

		$ratingArgs = array(
			'rating' => $wpInfo::getRating(),
			'type' => 'percent',
			'number' => 0,
			'echo' => false,
		);

		return sprintf( '<div class="rating-info-wrap">%s %s ratings</div>', wp_star_rating( $ratingArgs ), $wpInfo::getNumRating() );
	}

	public function getHiddenInputs() {
		$fields = '<input type="hidden" name="plugin-name" value="' . $this->pluginSlug . '">';
		$fields .= '<input type="hidden" name="plugin-event" value="' . $this->event . '">';

		return $fields;
	}

	public function getActionBtn() {
		return sprintf( '<a href="%s" class="anp-btn anp-action-btn rate-skip-btn %s" data-type="sure" target="_blank">Rate Plugin</a>', $this->actionBtnLink, $this->pluginSlug );
	}

	public function getSkipBtn() {
		return sprintf( '<a href="#" class="anp-btn anp-skip-btn rate-skip-btn" data-type="later" data-key="%s">%s</a>', $this->skipBtnKey, 'Later' );
	}
}
