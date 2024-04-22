<?php


namespace ANP\Popup\Theme;

use ANP\NotificationEnqueueControl;
use ANP\Popup\ItemBase;

class ItemUpdateTheme extends ItemBase {

	protected $chlogBtnLink = '';

	public function __construct($imgLink, $title, $desc, $actionBtnLink, $chlogBtnLink, $notifyKey) {
		parent::__construct( $imgLink, $title, $desc, $actionBtnLink, $notifyKey );
		$this->chlogBtnLink = $chlogBtnLink;
	}

	public function createHtml() {
		return sprintf(
			$this->getItemTemplate(),
			'anp-item-theme-update-wrap',
			$this->getImg(),
			$this->getTitle() . $this->getDesc(),
			$this->getActionBtn() . $this->getSkipBtn()
		);
	}

	public function getActionBtn() {
		return sprintf( '<a href="%s" class="anp-btn anp-action-btn">Update Theme</a>', $this->actionBtnLink );
	}

	public function getSkipBtn() {
		return sprintf( '<a href="%s" class="anp-btn anp-skip-btn">%s</a>', $this->chlogBtnLink, 'See Changelog' );
	}
}