<?php

defined( 'ABSPATH' ) || die();

$post_id    = absint( $_REQUEST['post'] ); // phpcs:ignore
$section    = get_post_meta( $post_id, 'jx-layout-type', true );
$conditions = get_post_meta( $post_id, 'jupiterx-condition-rules', true );

// Just enabled for layout builder templates.
if ( empty( $section ) ) {
	return;
}

// Take care of php warning.
if ( ! is_array( $conditions ) ) {
	$conditions = [];
}

// Putting section conditions in variables once to be used everywhere.
define( 'JX_EDITOR_WOO_CONDITIONS', JupiterX_Core_Condition_Manager::get_instance()->get_data( 'woocommerce', $section )['list'] );
define( 'JX_EDITOR_USERS_CONDITIONS', JupiterX_Core_Condition_Manager::get_instance()->get_data( 'users', $section )['list'] );
define( 'JX_EDITOR_SINGULAR_CONDITIONS', JupiterX_Core_Condition_Manager::get_instance()->get_data( 'singular', $section )['list'] );
define( 'JX_EDITOR_ARCHIVE_CONDITIONS', JupiterX_Core_Condition_Manager::get_instance()->get_data( 'archive', $section )['list'] );

// Enabled conditions for header - footer - page titlebar
$second_condition = [
	''            => esc_html__( 'Select an option', 'jupiterx-core' ),
	'entire'      => esc_html__( 'Entire Site', 'jupiterx-core' ),
	'archive'     => esc_html__( 'Archive', 'jupiterx-core' ),
	'singular'    => esc_html__( 'Singular', 'jupiterx-core' ),
	'woocommerce' => esc_html__( 'Woocommerce', 'jupiterx-core' ),
	'users'       => esc_html__( 'User Attributes', 'jupiterx-core' ),
	'maintenance' => esc_html__( 'Maintenance', 'jupiterx-core' ),
];

if ( 'singular' === $section ) {
	$second_condition = [
		''            => esc_html__( 'Select an option', 'jupiterx-core' ),
		'entire'      => esc_html__( 'Entire Site', 'jupiterx-core' ),
		'singular'    => esc_html__( 'Singular', 'jupiterx-core' ),
		'woocommerce' => esc_html__( 'Woocommerce', 'jupiterx-core' ),
		'users'       => esc_html__( 'User Attributes', 'jupiterx-core' ),
		'maintenance' => esc_html__( 'Maintenance', 'jupiterx-core' ),
	];
}

if ( 'archive' === $section ) {
	$second_condition = [
		''            => esc_html__( 'Select an option', 'jupiterx-core' ),
		'archive'     => esc_html__( 'Archive', 'jupiterx-core' ),
		'users'       => esc_html__( 'User Attributes', 'jupiterx-core' ),
	];
}

if ( 'product' === $section || 'product-archive' === $section ) {
	$second_condition = [
		''            => esc_html__( 'Select an option', 'jupiterx-core' ),
		'woocommerce' => esc_html__( 'Woocommerce', 'jupiterx-core' ),
		'users'       => esc_html__( 'User Attributes', 'jupiterx-core' ),
	];
}

$first_condition = [
	'include' => esc_html__( 'Include', 'jupiterx-core' ),
	'exclude' => esc_html__( 'Exclude', 'jupiterx-core' ),
];

/**
 * Create select options.
 *
 * @since 2.5.0
 */
function jx_editor_conditions_create_select_options( $data, $condition3 ) {
	if ( empty( $data ) ) {
		$data = [];
	}

	foreach ( $data as $key => $value ) :
		$selected = ( $condition3 === $key ) ? 'selected="selected"' : '';

		if ( is_array( $value ) ) :
			echo '<optgroup label="' . $key . '">';
				foreach ( $value as $key => $val ) {
					$selected2 = ( $condition3 === $key ) ? 'selected="selected"' : '';

					$key = esc_attr( $key );
					echo "<option {$selected2} value='{$key}'>{$val}</option>";
				}
			echo '</optgroup>';
		else :
			$key = esc_attr( $key );
			echo "<option {$selected} value='{$key}'>{$value}</option>";
		endif;
	endforeach;
}

/**
 * Create condition row
 *
 * @since 2.5.0
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
function jx_editor_conditions_create_condition_row( $value1, $value2, $value3, $value4, $first_condition, $second_condition ) {
	$hidden3    = 'jx-condition-hide';
	$third_data = [];
	$by_child   = [ 'singular', 'archive', 'users', 'woocommerce' ];
	$hidden4    = '';

	// Make decision for condition select 3 and its values.
	if ( in_array( $value2, $by_child, true ) ) {
		switch ( $value2 ) :
			case 'users':
				$third_data = JX_EDITOR_USERS_CONDITIONS;
				break;
			case 'woocommerce':
				$third_data = JX_EDITOR_WOO_CONDITIONS;
				break;
			case 'singular':
				$third_data = JX_EDITOR_SINGULAR_CONDITIONS;
				break;
			case 'archive':
				$third_data = JX_EDITOR_ARCHIVE_CONDITIONS;
				break;
		endswitch;

		$hidden3 = '';
	}

	// Make decision for condition select 4.
	$excludes = [ 'all', 'front_page', 'error_404', 'date', 'search', 'woo_search', 'all_product_archive', 'shop_archive', 'shop_manager' ];
	if ( in_array( $value3, $excludes, true ) || strpos( $value3, '_' ) === false ) {
		$hidden4 = 'jx-condition-hide';
	}

	$icon = 'eicon-plus-square';

	if ( 'exclude' === $value1 ) {
		$icon = 'eicon-minus-square';
	}

	?>
		<div class="jupiterx-editor-condition-single-row-wrapper">
			<div class="jupiterx-editor-single-row-inner-wrapper">
			<div class="jupiterx-editor-conditions-first-condition-wrapper">
				<i class="elementor-icon left-icon <?php echo esc_attr( $icon ); ?>" aria-hidden="true"></i>
				<select class="jx-first-condition">
					<?php
						foreach ( $first_condition as $key => $value ) {
							$selected = ( $value1 === $key ) ? 'selected="selected"' : '';

							echo "<option {$selected} value='{$key}'>{$value}</option>";
						}
					?>
				</select>
				<i class="elementor-icon eicon-caret-down" aria-hidden="true" ></i>
			</div>
			<div class="jupiterx-editor-conditions-second-condition-wrapper">
				<select class="jx-second-condition white-select">
					<?php
						foreach ( $second_condition as $key => $value ) {
							$selected = ( $value2 === $key ) ? 'selected="selected"' : '';

							echo "<option {$selected} value='{$key}'>{$value}</option>";
						}
					?>
				</select>
				<i class="elementor-icon eicon-caret-down" aria-hidden="true" ></i>
			</div>
			<div class="jupiterx-editor-conditions-third-condition-wrapper <?php echo $hidden3; ?>">
				<select class="jx-third-condition white-select">
					<?php
						if ( ! empty( $third_data ) ) :
							jx_editor_conditions_create_select_options( $third_data, $value3 );
						endif;
					?>
				</select>
				<i class="elementor-icon eicon-caret-down" aria-hidden="true" ></i>
			</div>
			<div class="jupiterx-editor-conditions-fourth-condition-wrapper <?php echo $hidden4; ?>">
				<select class="jx-fourth-condition white-select">
					<?php if ( empty( $hidden4 ) ) : ?>
						<option value="<?php echo $value4[0]; ?>"><?php echo $value4[1]; ?></option>
					<?php endif; ?>
				</select>
				<div class="item-4th-special-select2">
					<?php if ( empty( $hidden4 ) ) : ?>
						<?php echo $value4[1]; ?>
					<?php endif; ?>
				</div>
				<i class="elementor-icon eicon-editor-close jx-editor-condition-clear-forth" aria-hidden="true"></i>
				<i class="elementor-icon eicon-caret-down jx-editor-condition-fourth-dropdown-icon" aria-hidden="true" ></i>
				<div class="jx-condition-search">
					<div class="value-holder">
						<input type="text" class="jx-editor-conditions-4th-search-box" >
					</div>
					<ul class="jx-condition-editor-response-list">
						<li class="jx-ec-result jx-ec-default"><?php echo esc_html__( 'Please enter 1 or more characters', 'jupiterx-core' ); ?></li>
						<li class="jx-ec-result jx-ec-default jx-ec-hidden-item"><?php echo esc_html__( 'Searching...', 'jupiterx-core' ); ?></li>
					</ul>
				</div>
			</div>
			</div>
			<div class="elementor-repeater-row-tool elementor-repeater-tool-remove jupiterx-editor-conditions-remove-row">
				<i class="eicon-close" aria-hidden="true"></i>
				<span class="elementor-screen-only"><?php echo esc_html__( 'Remove this item', 'jupiterx-core' ); ?> </span>
			</div>
			<div class="jx-editor-row-show-conflict-error"></div>
		</div>
	<?php
}

?>

<script type="text/html" id="jupiterx-editor-conditions-response-list-default-items">
	<li class="jx-ec-item jx-ec-result jx-ec-default jx-ec-default-visible"><?php echo esc_html__( 'Please enter 1 or more characters', 'jupiterx-core' ); ?></li>
	<li class="jx-ec-item jx-ec-result jx-ec-default jx-ec-hidden-item"><?php echo esc_html__( 'Searching...', 'jupiterx-core' ); ?></li>
</script>

<script type="text/html" id="jupiterx-editor-condition-show-conditions-button" >
	<div id="jupiterx-editor-conditions-trigger" class="elementor-panel-footer-sub-menu-item">
		<i class="elementor-icon eicon-flow" aria-hidden="true"></i>
		<span class="elementor-title"><?php echo esc_html__( 'Display Conditions', 'jupiterx-core' ); ?></span>
	</div>
</script>

<script type="text/html" id="jupiterx-conditions-modal-header">
	<div class="dialog-header dialog-lightbox-header">
		<div class="elementor-templates-modal__header">
			<div class="elementor-templates-modal__header__logo-area"><div class="elementor-templates-modal__header__logo">
				<span class="elementor-templates-modal__header__logo__icon-wrapper e-logo-wrapper">
					<i class="eicon-elementor"></i>
				</span>
				<span class="elementor-templates-modal__header__logo__title">
					<?php echo esc_html__( 'JupiterX Conditions', 'jupiterx-core' ); ?>
				</span>
			</div>
		</div>
		<div class="elementor-templates-modal__header__menu-area"></div>
		<div class="elementor-templates-modal__header__items-area">
			<div id="jupiterx-conditions-close-modal" class="elementor-templates-modal__header__close elementor-templates-modal__header__close--normal elementor-templates-modal__header__item">
				<i class="eicon-close" aria-hidden="true" title="Close"></i>
				<span class="elementor-screen-only jupiterx-condition-modal-close">
					<?php echo esc_html__( 'Close', 'jupiterx-core' ); ?>
				</span>
			</div>
			<div id="elementor-template-library-header-tools"></div>
		</div>
	</div>
</script>

<script type="text/html" id="jupiterx-condition-modal-description">
	<div class="elementor-template-library-blank-icon">
		<i class="elementor-icon eicon-sitemap" aria-hidden="true"></i>
	</div>
	<div class="elementor-template-library-blank-title">
		<?php echo esc_Html__( 'Where do you want to display this template?', 'jupiterx-core' ); ?>
	</div>
	<div class="elementor-template-library-blank-message">
		<?php echo esc_html__( 'Set the conditions that determine where your Template is used throughout your site.', 'jupiterx-core' ); ?>
		<br>
		<?php echo esc_html__( "For example, choose 'Entire Site' to display the template across your site.", 'jupiterx-core' ); ?>
	</div>
	<div id="jupiterx-editor-conditions-list" class="jupiterx-editor-conditions-list">
		<?php
			foreach ( $conditions as $condition ) :
				jx_editor_conditions_create_condition_row( $condition['conditionA'], $condition['conditionB'], $condition['conditionC'], $condition['conditionD'], $first_condition, $second_condition );
			endforeach;
		?>
	</div>
	<div id="jupiterx-editor-condition-add-new" class="elementor-button-wrapper">
		<button class="elementor-button elementor-button-default" type="button" id="jupiterx-editor-condition-add-new-btn">
			<?php echo esc_html__( 'Add Condition', 'jupiterx-core' ); ?>
		</button>
	</div>
</script>

<script type="text/html" id="jupiterx-conditions-editor-row" >
	<?php
		jx_editor_conditions_create_condition_row( 'include', '', '', '', $first_condition, $second_condition );
	?>
</script>

<script type="text/html" id="jupiterx-editor-conditions-woocommerce">
	<?php
		jx_editor_conditions_create_select_options( JX_EDITOR_WOO_CONDITIONS, 'entire-shop' );
	?>
</script>

<script type="text/html" id="jupiterx-editor-conditions-singular">
	<?php
		jx_editor_conditions_create_select_options( JX_EDITOR_SINGULAR_CONDITIONS, 'all' );
	?>
</script>

<script type="text/html" id="jupiterx-editor-conditions-archive">
	<?php
		jx_editor_conditions_create_select_options( JX_EDITOR_ARCHIVE_CONDITIONS, 'all' );
	?>
</script>

<script type="text/html" id="jupiterx-editor-conditions-users">
	<?php
		jx_editor_conditions_create_select_options( JX_EDITOR_USERS_CONDITIONS, 'all' );
	?>
</script>
