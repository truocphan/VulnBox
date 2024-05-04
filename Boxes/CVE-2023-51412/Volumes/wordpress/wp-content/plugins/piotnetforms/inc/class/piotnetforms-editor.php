<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

// require all widgets
foreach ( glob( __DIR__ . '/../widgets/*.php' ) as $file ) {
	require_once $file;
}

$editor_class = 'piotnetforms_Editor';

class piotnetforms_Editor {

	private $widgets;

	public function __construct() {
		$this->widgets = [];
	}

	public function editor_panel() {
		$widgets = $this->widgets;
		echo '<div class="piotnetforms-editor__header">';
			echo '<div class="piotnetforms-editor__header-inner">';
				echo '<div class="piotnetforms-editor__collapse-button-close" title="Collapse" data-piotnetforms-editor-collapse-button-close><img src="' . plugin_dir_url( __FILE__ ) . '../../assets/images/i-collapse.svg"></div>';
				echo '<div class="piotnetforms-editor__header-text">Piotnet Forms</div>';
				echo '<div class="piotnetforms-editor__widgets-open" data-piotnetforms-editor-widgets-open><div class="piotnetforms-editor__widgets-open-button" data-piotnetforms-editor-widgets-open-button><img src="' . plugin_dir_url( __FILE__ ) . '../../assets/images/i-menu.svg"></div></div>';
			echo '</div>';
		echo '</div>';

		echo '<div class="piotnetforms-editor__widgets active" data-piotnetforms-widgets>';

		foreach ( $widgets as $widget ) {
			echo '<div class="piotnetforms-editor__widgets-item' . ( $widget['is_pro'] ? ' piotnetforms-editor__widgets-item--pro' : '' ) . '">';
				if ($widget['is_pro']) {
					echo '<a href="https://piotnetforms.com/?wpam_id=1">';
				}
				echo "<div class='piotnetforms-editor__widgets-item-inner'" . ( !$widget['is_pro'] ? " draggable='true'" : "" ) . " data-piotnetforms-editor-widgets-item-panel data-piotnetforms-editor-widgets-item='" . json_encode( $widget ) . "'>";
					if (is_array($widget['icon'])) {
						switch ($widget['icon']['type']) {
							case 'class':
								echo '<i class="' . esc_attr( $widget['icon']['value'] ) . '"></i>';
								break;
							case 'image':
								echo '<img src="' . esc_attr( $widget['icon']['value'] ) . '">';
								break;
							default:
								# code...
								break;
						}
					} else {
						echo '<i class="' . esc_attr( $widget['icon'] ) . '"></i>';
					}
					echo '<div class="piotnetforms-editor__widgets-item-name">';
						echo $widget['title'];
					echo '</div>';
				echo '</div>';
				if ($widget['is_pro']) {
					echo '</a>';
				}
			echo '</div>';
		}

		echo '</div>';

		echo '<div class="piotnetforms-editor__widget-settings" data-piotnetforms-editor-widget-settings>';
		?>
		<?php
		echo '</div>';
	}

	public function editor_preview_loop( $loop ) {
		foreach ( $loop as $widget_item ) {
			$widget            = new $widget_item['class_name']();
			$widget->settings  = $widget_item['settings'];
			$widget_id         = $widget_item['id'];
			$widget->widget_id = $widget_id;

			if ( ! empty( $widget_item['fonts'] ) ) {
				$fonts = $widget_item['fonts'];
				if ( ! empty( $fonts ) ) {
					foreach ( $fonts as $font ) {
						wp_enqueue_style( 'piotnetforms-add-font-' . $font, $font );
					}
				}
			}

			$widget_type = $widget->get_type();
			if ( $widget_type === 'section' || $widget_type === 'column' ) {
				echo @$widget->output_wrapper_start( $widget_id, true );

				if ( isset( $widget_item['elements'] ) ) {
					@$this->editor_preview_loop( $widget_item['elements'] );
				}
			} else {
				echo @$widget->output( $widget_id, true );
			}

			if ( $widget_type === 'section' || $widget_type === 'column' ) {
				echo @$widget->output_wrapper_end( $widget_id, true );
			}
		}
	}

	public function editor_preview( $widgets_settings ) {
		ob_start();
        @$this->editor_preview_loop( $widgets_settings );
		return ob_get_clean();
	}

	public function register_widget( $widget_object ) {
		$this->widgets[] = [
			'type'       => $widget_object->get_type(),
			'class_name' => $widget_object->get_class_name(),
			'title'      => $widget_object->get_title(),
			'icon'       => $widget_object->get_icon(),
			'is_pro'	 => $widget_object->is_pro,
		];
	}

	public function register_script( $widget_object ) {
		?>
		<div type="text/html" data-piotnetforms-template id="piotnetforms-<?php echo esc_attr( $widget_object->get_type() ); ?>-live-preview-template">
			<!--
			<?php $widget_object->live_preview(); ?>
			-->
		</div>
		<?php
	}
}
