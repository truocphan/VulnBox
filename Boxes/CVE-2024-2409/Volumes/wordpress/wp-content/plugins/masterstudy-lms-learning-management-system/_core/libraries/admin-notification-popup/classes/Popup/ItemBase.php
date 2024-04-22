<?php

namespace ANP\Popup;

use ANP\NotificationEnqueueControl;

abstract class ItemBase {
	protected $imgLink = '';
	protected $title = '';
	protected $desc = '';
	protected $actionBtnLink = '';
	protected $notifyKey = '';

	public function __construct( $imgLink, $title, $desc, $actionBtnLink, $notifyKey ) {
		$this->imgLink       = $imgLink;
		$this->title         = $title;
		$this->desc          = $desc;
		$this->actionBtnLink = $actionBtnLink;
		$this->notifyKey     = $notifyKey;
	}

	protected function getImg(): string {
		return sprintf( '<img src="%s" />', $this->imgLink );
	}

	protected function getTitle(): string {
		return sprintf( '<h4>%s</h4>', $this->title );
	}

	protected function getDesc(): string {
		return sprintf( '<div class="desc">%s</div>', $this->desc );
	}

	abstract public function getActionBtn();

	abstract public function createHtml();

	public function getItemTemplate(): string {

		$status = ( NotificationEnqueueControl::checkNotificationStatus( $this->notifyKey ) ) ? 'new' : '';

		return '<div class="anp-item-base %1s ' . $status . '" data-notify="' . $this->notifyKey . '">
					<div class="left">%2s</div>
					<div class="right">%3s
						<div class="btns-wrap">%4s</div>
					</div>
				</div>';
	}
}