<?php
function mo_openid_short_code() {
	?>

	<div class="mo_openid_table_layout" >
		<table>
			<tr>
		<td style="font-size: 15px;">

				<br><br><b><?php echo esc_attr( mo_sl( 'Horizontal' ) ); ?></b> --> <code id="1">[miniorange_social_sharing]</code><i style= "width: 11px;height: 9px;padding-left:2px;padding-top:3px" class="far fa-fw fa-lg fa-copy mo_copy mo_copytooltip" onclick="copyToClipboard(this, '#1', '#shortcode_url1_copy')"><span id="shortcode_url1_copy" class="mo_copytooltiptext">Copy to Clipboard</span></i></b><br><br>
				<b><?php echo esc_attr( mo_sl( 'Vertical' ) ); ?></b> --> <code id="2">[miniorange_social_sharing_vertical]</code><i style= "width: 11px;height: 9px;padding-left:2px;padding-top:3px" class="far fa-fw fa-lg fa-copy mo_copy mo_copytooltip" onclick="copyToClipboard(this, '#2', '#shortcode_url2_copy')"><span id="shortcode_url2_copy" class="mo_copytooltiptext">Copy to Clipboard</span></i>
				<!--Use [miniorange_social_sharing] Shortcode in the content of required page/post where you want to display horizontal Social Sharing Icons. Use [miniorange_social_sharing_vertical] shortcode for vertical Social Sharing Icons.--><br>

			   <br>
				<h4><?php echo esc_attr( mo_sl( 'For Sharing Icons' ) ); ?></h4>
			   <?php echo esc_attr( mo_sl( ' You can use  different attribute to customize social sharing icons. All attributes are optional' ) ); ?>.<br>
				<b><?php echo esc_attr( mo_sl( 'Example' ) ); ?>:</b> <code id="3" style="font-size: 14px"> [miniorange_social_sharing  shape="square" heading="Share with" color="#000000" fontcolor="blue" theme="customFont" space="14" size="30" url="https://www.miniorange.com"]</code><i style= "width: 11px;height: 9px;padding-left:2px;padding-top:3px" class="far fa-fw fa-lg fa-copy mo_copy mo_copytooltip" onclick="copyToClipboard(this, '#3', '#shortcode_url3_copy')"><span id="shortcode_url3_copy" class="mo_copytooltiptext">Copy to Clipboard</span></i>
				<br><br>

				<h4 style="margin-bottom:0 !important"><?php echo esc_attr( mo_sl( 'Common attributes - Horizontal and Vertical' ) ); ?></h4>
				<b><?php echo esc_attr( mo_sl( 'shape' ) ); ?></b>:<?php echo esc_attr( mo_sl( ' round, roundededges, square' ) ); ?><br>
				<b><?php echo esc_attr( mo_sl( 'theme' ) ); ?></b>: <?php echo esc_attr( mo_sl( 'default, custombackground, nobackground' ) ); ?><br>
				<b><?php echo esc_attr( mo_sl( 'size' ) ); ?></b>: <?php echo esc_attr( mo_sl( 'Any value between 20 to 100' ) ); ?><br>
				<b><?php echo esc_attr( mo_sl( 'space' ) ); ?></b>: <?php echo esc_attr( mo_sl( 'Any value between 0 to 50' ) ); ?><br>
				<b><?php echo esc_attr( mo_sl( 'url' ) ); ?></b>: <?php echo esc_attr( mo_sl( 'Enter custom URL for sharing' ) ); ?><br>
				<b><?php echo esc_attr( mo_sl( 'fontcolor' ) ); ?></b>: <?php echo esc_attr( mo_sl( 'Enter custom color for icons (only works with no background theme' ) ); ?><br>
				<b><?php echo esc_attr( mo_sl( 'sharecnt' ) ); ?></b>:<?php echo esc_attr( mo_sl( ' yes, no ' ) ); ?><b>*<?php echo esc_attr( mo_sl( 'To see social share count*' ) ); ?></b><br>
				<h4 style="margin-bottom:0 !important"><?php echo esc_attr( mo_sl( 'Horizontal attributes' ) ); ?></h4>
				<b><?php echo esc_attr( mo_sl( 'heading' ) ); ?></b>: <?php echo esc_attr( mo_sl( 'Enter custom heading text' ) ); ?><br>
				<b><?php echo esc_attr( mo_sl( 'color' ) ); ?></b>:<?php echo esc_attr( mo_sl( ' Enter custom text color for heading eg: cyan, red, blue, orange, yellow' ) ); ?> <br>
				<br>
				<b><?php echo esc_attr( mo_sl( 'Vertical attributes' ) ); ?></b><br>
				<b><?php echo esc_attr( mo_sl( 'alignment' ) ); ?></b>: <?php echo esc_attr( mo_sl( 'left,right' ) ); ?><br>
				<b><?php echo esc_attr( mo_sl( 'topoffset' ) ); ?></b>: <?php echo esc_attr( mo_sl( 'Any value(height from top) between 0 to 1000' ) ); ?><br>
				<b><?php echo esc_attr( mo_sl( 'rightoffset(Applicable if alignment is right)' ) ); ?></b>: <?php echo esc_attr( mo_sl( 'Any value between 0 to 200' ) ); ?><br>
				<b><?php echo esc_attr( mo_sl( 'leftoffset(Applicable if alignment is left)' ) ); ?></b>: <?php echo esc_attr( mo_sl( 'Any value between 0 to 200' ) ); ?><br>


		</td>
	</tr>
	</table>
	</div>
	<script>
		//to set heading name
		jQuery('#mo_openid_page_heading').text('<?php echo esc_attr( mo_sl( 'Social Sharing Shortcode' ) ); ?>');

		//copy to clipboard
		function copyToClipboard(copyButton, element, copyelement) {
			var temp = jQuery("<input>");
			jQuery("body").append(temp);
			temp.val(jQuery(element).text()).select();
			document.execCommand("copy");
			temp.remove();
			jQuery(copyelement).text("Copied");
			jQuery(copyButton).mouseout(function(){
				jQuery(copyelement).text("Copy to Clipboard");
			});
		}
	</script>

	<?php
}
?>
