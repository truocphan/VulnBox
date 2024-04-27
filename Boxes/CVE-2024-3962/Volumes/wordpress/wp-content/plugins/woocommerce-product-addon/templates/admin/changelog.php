<?php
/*
** N-Media More Plugins Here...
*/

/*
**========== Direct access not allowed ===========
*/
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Not Allowed' );
}

$changelog_handler = ( new PPOM_Changelog_Handler() );

$changelogs = [
	'free'=>[
		'items'=>$changelog_handler->get_changelog( PPOM_PATH . '/CHANGELOG.md' ),
		'label'=>esc_html__( 'PPOM Free', 'woocommerce-product-addon' )
	]
];

if( defined('PPOM_PRO_PATH') ) {
	$changelogs['pro'] = [
		'items'=>$changelog_handler->get_changelog( PPOM_PRO_PATH . '/CHANGELOG.md' ),
		'label'=>esc_html__('PPOM Pro', 'woocommerce-product-addon')
	];
}

$version_types_map = [
	'features'=>esc_html__( 'Features', 'woocommerce-product-addon' ),
	'fixes'=>esc_html__( 'Bug Fixes', 'woocommerce-product-addon' ),
	'tweaks'=>esc_html__( 'Tweaks', 'woocommerce-product-addon' ),
];

$ppom_settings_url = admin_url( "admin.php?page=wc-settings&tab=ppom_settings" );
$ppom_fields_url = admin_url( "admin.php?page=ppom" );

?>
<style type="text/css">

	#wpcontent {
		padding-left: 0 !important;
		position: relative;
	}

	.ppom-admin-wrap {
		margin: 0 !important;
	}
</style>
<div class="ppom-admin-addons-wrapper">
	<div id="ppom-admin-cl-header-wrapper">
		<div id="ppom-admin-changelog-header-top">
			<ul id="ppom-changelog-nav">
				<li><a id="ppom-all-addons" class="mr-3" href="<?php echo esc_url($ppom_fields_url); ?>"> <?php esc_html_e( 'PPOM Fields', 'woocommerce-product-addon' ); ?></a></li>
				<li><a href="<?php echo esc_url($ppom_settings_url); ?>"><?php esc_html_e('General Settings', 'woocommerce-product-addon'); ?></a></li>
			</ul>
		</div>
		<div class="ppom-admin-addons-header">
			<div class="ppom-admin-addons-header-logo">
				<h3><?php printf( esc_html__( '%s Changelog', 'woocommerce-product-addon' ), sprintf( '<span id="ppom-type-label">%s</span>', array_values($changelogs)[0]['label']) ); ?></h3>
			</div>
			<?php if(count($changelogs)>1){ ?>
			<div class="ppom-admin-addons-header-search-input">
				<select id="ppom-type">
				<?php foreach( $changelogs as $type=>$meta ) { ?>
					<option value="<?php echo esc_attr($type); ?>"><?php echo esc_html($meta['label']); ?></option>
				<?php } ?>
				</select>
			</div>
			<?php } ?>
		</div>
	</div>

	<div class="ppom-admin-cl-wrapper">
		<?php foreach($changelogs as $type=>$meta){ ?>
			<?php $id = sprintf( 'accordion-%s', $type ); ?>
			<div class="accordion-container" id="<?php echo esc_attr( $id ); ?>" <?php if( $type !== 'free' ) { ?> style="display:none"<?php } ?>>
			<?php foreach( $meta['items'] as $version ) { ?>
				<h3><?php printf( esc_html( 'v%s - %s' ), $version['version'], $version['date'] ); ?></h3>
				<div>
					<?php
						foreach( $version_types_map as $type=>$label )
						{
							if( ! isset( $version[$type] ) ) continue;
					?>
							<h4><span class="badge badge-secondary badge-<?php echo esc_attr($type); ?>"><?php echo esc_html( $label ); ?></span></h4>
							<ul>
								<?php foreach($version[$type] as $change){ ?>
									<li><?php echo wp_kses($change, ['a'=>['href'=>[], 'title'=>[]]]); ?></li>
								<?php } ?>
							</ul>
					<?php
						}
					?>
				</div>
			<?php } ?>
			</div>
			<script>
				jQuery( "#<?php echo esc_html( $id ); ?>" ).accordion({
					heightStyle: "content"
				});
			</script>
		<?php } ?>
	</div>
</div>

<script>
	;(function($){
		$('#ppom-type').change(function(){
			const type = $(this).val();
			$('#ppom-type-label').html($(this).find(':selected').html());
			$('.accordion-container').hide();
			$(`#accordion-${type}`).show();
		});
	})(jQuery);
</script>