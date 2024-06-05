<?php

$iconbgcolor 	= $sy_options['s-icon-bgcolor'];
$iconcolor 		= $sy_options['s-icon-color'];
$iconsize 		= $sy_options['s-icon-size'];
$iconwidth 		= $sy_options['s-icon-width'];
$iconborcolor	= $sy_options['s-icon-borcolor'];
$iconborwidth  	= $sy_options['s-icon-borwidth'];
$fieldmargin 	= $sy_options['s-field-bmargin'];
$inputbgcolor 	= $sy_options['s-input-bgcolor'];
$inputtxtcolor 	= $sy_options['s-input-txtcolor'];
$inputborcolor 	= $sy_options['s-input-borcolor'];
$inputborwidth  = $sy_options['s-input-borwidth'];
$focusbgcolor 	= $sy_options['s-input-focusbgcolor'];
$focustxtcolor 	= $sy_options['s-input-focustxtcolor'];
$inputheight 	= $sy_options['s-input-height'];

?>

.xoo-aff-input-group .xoo-aff-input-icon{
	background-color: <?php echo esc_html( $iconbgcolor ); ?>;
	color: <?php echo esc_html( $iconcolor ); ?>;
	max-width: <?php echo esc_html( $iconwidth ); ?>px;
	min-width: <?php echo esc_html( $iconwidth ); ?>px;
	border-color: <?php echo esc_html( $iconborcolor ); ?>;
	border-width: <?php echo (int) $iconborwidth; ?>px;
	font-size: <?php echo esc_html( $iconsize ); ?>px;
}
.xoo-aff-group{
	margin-bottom: <?php echo esc_html( $fieldmargin ); ?>px;
}

.xoo-aff-group input[type="text"], .xoo-aff-group input[type="password"], .xoo-aff-group input[type="email"], .xoo-aff-group input[type="number"], .xoo-aff-group select, .xoo-aff-group select + .select2, .xoo-aff-group input[type="tel"]{
	background-color: <?php echo esc_html( $inputbgcolor ); ?>;
	color: <?php echo esc_html( $inputtxtcolor ); ?>;
	border-width: <?php echo (int) $inputborwidth; ?>px;
	border-color: <?php echo esc_html( $inputborcolor ); ?>;
	height: <?php echo (int) $inputheight; ?>px;
}



.xoo-aff-group input[type="text"]::placeholder, .xoo-aff-group input[type="password"]::placeholder, .xoo-aff-group input[type="email"]::placeholder, .xoo-aff-group input[type="number"]::placeholder, .xoo-aff-group select::placeholder, .xoo-aff-group input[type="tel"]::placeholder, .xoo-aff-group .select2-selection__rendered, .xoo-aff-group .select2-container--default .select2-selection--single .select2-selection__rendered{
	color: <?php echo esc_html( $inputtxtcolor ); ?>;
}

.xoo-aff-group input[type="text"]:focus, .xoo-aff-group input[type="password"]:focus, .xoo-aff-group input[type="email"]:focus, .xoo-aff-group input[type="number"]:focus, .xoo-aff-group select:focus, .xoo-aff-group select + .select2:focus, .xoo-aff-group input[type="tel"]:focus{
	background-color: <?php echo esc_html( $focusbgcolor ); ?>;
	color: <?php echo esc_html( $focustxtcolor ) ?>;
}

[placeholder]:focus::-webkit-input-placeholder{
	color: <?php echo esc_html( $focustxtcolor ) ?>!important;
}

<?php if( $sy_options['s-show-icons'] !== "yes" ): ?>

	.xoo-aff-input-group .xoo-aff-input-icon{
		display: none!important;
	}

<?php endif; ?>

.xoo-aff-input-icon + input[type="text"], .xoo-aff-input-icon + input[type="password"], .xoo-aff-input-icon + input[type="email"], .xoo-aff-input-icon + input[type="number"], .xoo-aff-input-icon + select, .xoo-aff-input-icon + select + .select2,  .xoo-aff-input-icon + input[type="tel"]{
	border-bottom-left-radius: 0;
	border-top-left-radius: 0;
}