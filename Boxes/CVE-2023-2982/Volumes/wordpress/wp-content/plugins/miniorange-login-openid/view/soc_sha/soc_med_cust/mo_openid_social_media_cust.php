<?php

function mo_openid_social_icons_customization() {
	?>
  <script>jQuery('#mo_openid_page_heading').text('<?php echo esc_attr( mo_sl( 'Social Customize Icons' ) ); ?>');
   var temp = jQuery("<a style=\"left: 1%; padding:4px; position: relative; text-decoration: none\" class=\"mo-openid-premium\" href=\"<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>\">PRO</a>");
					jQuery("#mo_openid_page_heading").append(temp);</script>

  <form id="social_media_cust" name="social_media_cust" method="post" action="">
		<input type="hidden" name="option" value="mo_openid_social_media_cust" />
		<input type="hidden" name="mo_openid_social_media_cust_nonce"
			   value="<?php echo esc_attr( wp_create_nonce( 'mo-openid-social-media-cust-nonce' ) ); ?>"/>
	<div style="height: auto; padding: 20px 20px 20px 20px; "><table style="width:60%">
		 <tr><td>
		  <h2>Hover Icons <a style="left: 1%; position: relative; text-decoration: none" class="mo-openid-premium" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></a></h2>
		  <?php echo esc_attr( Cursor_icon_hover() ); ?>
				<br><br>Use this <b>[hover-icon]</b> shortcode to display mouse hover icons.
		</td></tr>
		</table>
	</div> 
</form>
<div class="mo_openid_table_layout" style="height: auto; padding: 20px 20px 20px 20px;">
			<form method='post' action='' name='myform' enctype='multipart/form-data'>
				<h3>Upload image <a style="left: 1%; position: relative; text-decoration: none" class="mo-openid-premium" href="<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>"><?php echo esc_attr( mo_sl( 'PRO' ) ); ?></a></h3>
				<label style="cursor: auto" class="mo_openid_note_style">&nbsp;&nbsp;&nbsp;<?php echo esc_attr( mo_sl( '<b> Unlock this feature if you want to setup your own customize social sharing app</b>' ) ); ?>.</label><br>
				<input type='file' name='file' disabled>
				<input type='submit' name='image_submit' value='Submit' disabled>
				</form>
				<br><br>
				
			<form method="post">
			<input type="hidden" name="option" value="mo_openid_custom_social_sharing" />
			<table id="custom_attr" style="width:100%">
				<?php
				// $user_id=get_current_user_id();
				// $user_info = get_userdata($user_id);
				if ( get_option( 'mo_openid_custom_social_sharing' ) ) {
					$custom_attr = get_option( 'mo_openid_custom_social_sharing' );
					$k           = count( $custom_attr );
					$a           = 0;
					foreach ( $custom_attr as $x ) {
						foreach ( $x as $xx => $x_value ) {
							;
						}
						?>
							<tr>
							<td>
							<input type="text" class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2"  name="mo_openid_custom_set_attribute_<?php echo esc_attr( $a ); ?>_name" value="<?php echo esc_attr( $xx ); ?>" required style="width:80%;"/>
						</td>
						<td>
						  <input type="text" class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2"  name="mo_openid_custom_set_attribute_<?php echo esc_attr( $a ); ?>_value" value="<?php echo esc_attr( $x_value[0] ); ?>" required style="width:80%;"/>
						  </td><td>
						  <input type="text" class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2"  name="mo_openid_custom_set_attribute_<?php echo esc_attr( $a ); ?>_image" value="<?php echo esc_attr( $x_value[1] ); ?>" required style="width:80%;"/>
						  </td><td> 
						  <input name="<?php echo esc_attr( $a ); ?>" type="submit" value="<?php echo esc_attr( mo_sl( 'Delete' ) ); ?>" class="button button-primary button-large"/></td>
						</tr>
						<?php
						$a++;
					}
				}
				?>
				<tr>
					<td><br><input type="text" class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2" name="mo_openid_custom_attribute_1_name" placeholder="Custom Share Name" style="width:80%;" disabled/></td>

					<td><br><input type="text" class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2" name="mo_openid_custom_attribute_1_value" placeholder="Custom Share Link" style="width:80%;" disabled/></td>

					<td><br><input type="text" class="mo_openid_textfield_css" style="border: 1px solid ;border-color: #0867b2" name="mo_openid_custom_attribute_1_image" placeholder="Custom Share Image" style="width:80%;" disabled/></td>

					<td> <br><input type="button" name="mo_add_attribute" value="+" onclick="add_custom_attribute();" class=" button-primary" disabled/>&nbsp;
						<input type="button" name="mo_remove_attribute" value="-" onclick="remove_custom_attribute();" class=" button-primary" disabled/>
					</td> </tr>
				<tr id="mo_openid_custom_attribute"><td></td></tr>
				<tr>
					<td align="center"colspan="3"><br>
						<input type="hidden" name="mo_openid_custom_social_sharing_nonce"
							   value="<?php echo esc_attr( wp_create_nonce( 'mo-openid-custom-social-sharing-nonce' ) ); ?>" />
						<input name="mo_openid_save_config_element" type="submit" value="Save Attributes"  class="button button-primary button-large" disabled/>
						&nbsp &nbsp <a  href="" class="button button-primary button-large" disabled><?php echo esc_attr( mo_sl( 'Cancel' ) ); ?></a>
					</td>
				</tr>
			</table>
		</form>
	  </div>

		<br>
	<?php
}

function Cursor_icon_hover() {

	$apparr = array(
		'facebook' => '#46629e',
		'twitter'  => '#00acee',
		'google'   => '#dd4b39',
	);

	$hovericon = "<section class='social'>";

	foreach ( $apparr as $x => $y ) {
		if ( 1 == 1 ) {
			$hovericon .= "<a class='icon " . $x . "'><i class='fab fa-" . $x . "'  width='30px' height:'30px' style='margin-top: 5px;'></i></a>";

			$hovericon .= '<style>
                        .social .' . $x . ' {
                      background: ' . $y . ';
                    }
                    .social .' . $x . ':before,
                    .social .' . $x . ':after {
                      border-color: ' . $y . ';
                    }
                    </style>';
		};
	}

	$hovericon .= '
        <style>
        @font-face {
          font-family: "icomoon";
          font-weight: normal;
          font-style: normal;
        }

        a {
          text-decoration: none;
        }

        .social {
          width: auto;
          position: relative;
          margin: 0px auto;
        }
        .social a {
          position: relative;
          display: inline-block;
          font-family: "icomoon";
          font-size: 1.2em;
          width: 40px;
          height: 40px;
          line-height: 40px;
          color: white;
          border-radius: 50%;
          text-align: center;
          margin-right: 30px;
          font-smoothing: antialiased;
        }

        .social a:before,
        .social a:after {
          content: "";
          display: block;
          position: absolute;
          background: transparent;
          top: 0;
          bottom: 0;
          left: 0;
          right: 0;
          border-radius: 50%;
          transition: 0.3s all;
          border: 3px solid;
        }
        .social a:hover:after {
          -webkit-transform: scale(1.5);
        }
        .social a:hover:before {
          -webkit-transform: scale(2);
          transition: 0.3s all;
          opacity: 0;
        }
        </style>
        ';

	return $hovericon;
}

