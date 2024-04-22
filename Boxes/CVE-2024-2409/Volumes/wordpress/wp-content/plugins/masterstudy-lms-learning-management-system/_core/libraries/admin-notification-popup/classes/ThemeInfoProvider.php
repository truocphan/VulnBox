<?php

namespace ANP;

use STM_Theme_Changelog;

class ThemeInfoProvider {

	public $slug = '';
	public $thumbnail = '';
	public $name = '';
	public $version = '';
	public $changelog = '';
	public $updateLink = '';
	public $hasUpdate = false;

	public function __construct( $slug ) {
		$this->slug = $slug;
		self::setActiveThemeInfo();
	}

	private function setActiveThemeInfo() {
		if ( function_exists( 'envato_market' ) ) {
			$themeTransient  = get_site_transient( envato_market()->get_option_name() . '_themes' );
			$premium         = ( ! empty( $themeTransient ) ) ? $themeTransient['active'] : envato_market()->items()->themes( 'active' );

			if ( ! isset( $premium[ $this->slug ] ) ) {
				return;
			}

			$this->thumbnail = $premium[ $this->slug ]['thumbnail_url'];
			$this->name      = $premium[ $this->slug ]['name'];
			$this->version   = $premium[ $this->slug ]['version'];

			$this->hasUpdate();
			$this->setChangelogText();
			$this->setUpdateLink();
		}
	}

	private function setChangelogText() {
		if ( class_exists( 'STM_Theme_Changelog' ) ) {
			$chlg = STM_Theme_Changelog::get_theme_changelog();
			if ( ! empty( $chlg[0] ) ) {
				$this->changelog = $chlg[0]['list'][0];
			}
		}
	}

	private function setUpdateLink() {
		$this->updateLink = add_query_arg(
			array(
				'action' => 'upgrade-theme',
				'theme'  => esc_attr( $this->slug ),
			),
			self_admin_url( 'update.php' )
		);
	}

	private function hasUpdate() {
		$theme = wp_get_theme( $this->slug );
		if ( $theme->exists() ) {
			$version = $theme->get( 'Version' );
			if ( version_compare( $version, $this->version, '<' ) ) {
				$this->hasUpdate = true;
			}
		}
	}
}