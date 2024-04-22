<?php

namespace ANP\Popup\Plugin;

use ANP\NotificationEnqueueControl;
use \ANP\Popup\ItemBase;

class ItemCoreActivate extends ItemBase {

	public function __construct($imgLink, $title, $actionBtnLink, $notifyKey) {
		parent::__construct( $imgLink, $title, '', $actionBtnLink, $notifyKey );
	}

	public function createHtml() {
		return sprintf(
			$this->getItemTemplate(),
			'anp-item-theme-update-wrap',
			$this->getImg(),
			$this->getTitle(),
			$this->getActionBtn()
		);
	}

	public function getActionBtn() {
		return sprintf( '<a href="%s" class="anp-btn anp-action-btn">Begin activating plugins</a>', $this->actionBtnLink );
	}
}