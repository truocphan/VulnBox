<?php

namespace ANP\Popup\Theme;

use ANP\Popup\ItemBase;

class ItemRateTheme extends ItemBase {

	public function __construct($imgLink, $title, $desc, $actionBtnLink, $notifyKey) {
		parent::__construct( $imgLink, $title, $desc, $actionBtnLink, $notifyKey );
	}

	public function createHtml() {
		return sprintf(
			$this->getItemTemplate(),
			'anp-item-theme-rate-wrap',
			$this->getImg(),
			$this->getTitle() . $this->getDesc(),
			$this->getActionBtn() . $this->getSkipBtn()
		);
	}

	public function getActionBtn() {
		return sprintf( '<a href="%s" class="anp-btn anp-action-btn add_review">Leave a Review</a>', $this->actionBtnLink );
	}

	public function getSkipBtn() {
		return sprintf( '<a href="%s" class="anp-btn anp-skip-btn skip_review">%s</a>', '#', 'No, thank you' );
	}
}