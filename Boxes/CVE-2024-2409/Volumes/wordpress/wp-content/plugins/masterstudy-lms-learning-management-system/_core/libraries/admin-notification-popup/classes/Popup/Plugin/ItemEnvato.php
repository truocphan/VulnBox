<?php

namespace ANP\Popup\Plugin;

use ANP\NotificationEnqueueControl;
use \ANP\Popup\ItemBase;

class ItemEnvato extends ItemBase {

	public $btnName;

	public function __construct( $imgLink, $title, $desc, $btnName, $actionBtnLink, $notifyKey ) {
		parent::__construct( $imgLink, $title, $desc, $actionBtnLink, $notifyKey );
		$this->btnName = $btnName;
	}

	public function createHtml() {
		return sprintf(
			$this->getItemTemplate(),
			'anp-item-envato-wrap',
			$this->getImg(),
			$this->getTitle() . $this->getDesc(),
			$this->getActionBtn()
		);
	}

	public function getActionBtn() {
		return sprintf( '<a href="%s" class="anp-btn anp-action-btn">%s</a>', $this->actionBtnLink, $this->btnName );
	}
}
