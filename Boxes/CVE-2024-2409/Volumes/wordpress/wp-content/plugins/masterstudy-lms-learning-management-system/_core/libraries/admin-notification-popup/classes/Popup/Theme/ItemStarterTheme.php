<?php

namespace ANP\Popup\Theme;

use ANP\NotificationEnqueueControl;
use \ANP\Popup\ItemBase;

class ItemStarterTheme extends ItemBase {

	protected $liveBtnLink = '';
	protected $skipBtnKey = '';

	public function __construct($imgLink, $title, $desc, $actionBtnLink, $liveBtnLink, $skipBtnKey) {
		parent::__construct( $imgLink, $title, $desc, $actionBtnLink, $skipBtnKey );
		$this->liveBtnLink = $liveBtnLink;
		$this->skipBtnKey = $skipBtnKey;
	}

	public function createHtml() {
		return sprintf(
			$this->getItemTemplate(),
			'anp-item-theme-update-wrap',
			$this->getImg(),
			$this->getTitle() . $this->getDesc(),
			$this->getActionBtn() . $this->getLiveDemoBtn() . $this->getSkipBtn()
		);
	}

	public function getActionBtn() {
		return sprintf( '<a href="%s" class="anp-btn anp-action-btn">Install</a>', $this->actionBtnLink );
	}

	public function getLiveDemoBtn () {
		return sprintf( '<a href="%s" class="anp-btn anp-live-btn">Live Demo</a>', $this->liveBtnLink );
	}

	public function getSkipBtn() {
		return sprintf( '<a href="#" class="anp-btn anp-skip-btn starter-skip-btn" data-type="later" data-key="%s">%s</a>', $this->skipBtnKey, 'No Thanks' );
	}
}
