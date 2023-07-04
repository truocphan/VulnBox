<?php
function mo_twitter_mo_btn() {
	?>
	<form id="mo_openid_twitter_button" name="mo_openid_twitter_button" method="post" action="">
		<input type="hidden" name="option" value="mo_openid_twitter_button" />
		<input type="hidden" name="mo_openid_twitter_button_nonce" value="<?php echo esc_attr( wp_create_nonce( 'mo-openid-twitter-button-nonce' ) ); ?>"/>

		<div class="mo_openid_table_layout">

			<table>
				<br>

				<label class="mo_openid_checkbox_container_disable">
					<input disabled type="checkbox" id="mo_openid_twitter_mo_btn" /><?php echo esc_attr( mo_sl( 'Enable Twitter Follow Button' ) ); ?>
					<span class="mo_openid_checkbox_checkmark"></span>
				</label>
				<tr>
					<td>Where Twitter Follow Button will be displayed:</td>
					<td><select disabled name="mo_openid_twitter_follow_position_button" id="position_button" >
							<option disabled value="before"
							<?php
							if ( get_option( 'mo_openid_twitter_follow_position_button' ) === 'before' ) {
								echo 'selected';}
							?>
							>Before Content</option>
							<option disabled value="after" 
							<?php
							if ( get_option( 'mo_openid_twitter_follow_position_button' ) === 'after' ) {
								echo 'selected';}
							?>
							>After Content</option>
							<option disabled value="before_and_after" 
							<?php
							if ( get_option( 'mo_openid_twitter_follow_position_button' ) === 'before_and_after' ) {
								echo 'selected';}
							?>
							>Before and After</option>
						</select>
					</td>
				</tr>
				<tr style="height: 10px;"></tr>
				<tr>

					<td>Custom CSS for &lt;div&gt; (i.e. float: right;):</td>
					<td><input disabled id="own_css" name="mo_openid_twitter_follow_own_css" value="<?php echo esc_attr( get_option( 'mo_openid_twitter_follow_own_css' ) ); ?>";></td>
					</td>
				</tr>
				<tr style="height: 10px;"></tr>
				<tr >
					<td>What's your user name?</td>
					<td><input disabled size="32" id="screen_name" name="mo_openid_twitter_follow_screen_name" value="<?php echo esc_attr( get_option( 'mo_openid_twitter_follow_screen_name' ) ); ?>"></td>
					</td>
				</tr>
				<tr style="height: 10px;"></tr>

				<tr>
					<td>What color background will be used?</td>
					<td><select disabled name="mo_openid_twitter_follow_data_button_background" id="data_button" >
							<option disabled value="grey" 
							<?php
							if ( get_option( 'mo_openid_twitter_follow_data_button_background' ) === 'grey' ) {
								echo 'selected';}
							?>
							>dark</option>
							<option disabled value="blue"  
							<?php
							if ( get_option( 'mo_openid_twitter_follow_data_button_background' ) === 'blue' ) {
								echo 'selected';}
							?>
							 >light</option>
						</select>
					</td>
				</tr>
				<tr style="height: 10px;"></tr>
				<tr>
					<td>Text color?</td>
					<td><input disabled size="10" id="text_color" name="mo_openid_twitter_follow_data_text_color" value="<?php echo esc_attr( get_option( 'mo_openid_twitter_follow_data_text_color' ) ); ?>"></td>
					</td>
				</tr>
				<tr style="height: 10px;"></tr>
				<tr>
					<td>Link color?</td>
					<td><input disabled size="10" id="link_color" name="mo_openid_twitter_follow_data_link_color" value="<?php echo esc_attr( get_option( 'mo_openid_twitter_follow_data_link_color' ) ); ?>"></td>
					</td>
				</tr>
				<tr style="height: 10px;"></tr>

				<tr >
					<td>Show follower count?</th>
					<td><select disabled name="mo_openid_twitter_follow_data_show_count" id="data_show_count" >
							<option disabled value="true" 
							<?php
							if ( get_option( 'mo_openid_twitter_follow_data_show_count' ) === 'true' ) {
								echo 'selected';}
							?>
							 >true</option>
							<option disabled value="false" 
							<?php
							if ( get_option( 'mo_openid_twitter_follow_data_show_count' ) === 'false' ) {
								echo 'selected';}
							?>
							>false</option>
						</select>
					</td>
				</tr>
				<tr style="height: 10px;"></tr>

				<tr>
					<td>Language options</td>
					<td>
						<select disabled name="mo_openid_twitter_follow_lang" id="lang" >
							<option disabled value="en" 
							<?php
							if ( get_option( 'mo_openid_twitter_follow_lang' ) === 'en' ) {
								echo 'selected';}
							?>
							>English</option>
							<option disabled value="fr" 
							<?php
							if ( get_option( 'mo_openid_twitter_follow_lang' ) === 'fr' ) {
								echo 'selected';}
							?>
							>French</option>
							<option disabled value="de" 
							<?php
							if ( get_option( 'mo_openid_twitter_follow_lang' ) === 'de' ) {
								echo 'selected';}
							?>
							>German</option>
							<option disabled value="it" 
							<?php
							if ( get_option( 'mo_openid_twitter_follow_lang' ) === 'it' ) {
								echo 'selected';}
							?>
							>Italian</option>
							<option disabled value="ja" 
							<?php
							if ( get_option( 'mo_openid_twitter_follow_lang' ) === 'ja' ) {
								echo 'selected';}
							?>
							>Japanese</option>
							<option disabled value="ko" 
							<?php
							if ( get_option( 'mo_openid_twitter_follow_lang' ) === 'ko' ) {
								echo 'selected';}
							?>
							>Korean</option>
							<option disabled value="ru" 
							<?php
							if ( get_option( 'mo_openid_twitter_follow_lang' ) === 'ru' ) {
								echo 'selected';}
							?>
							 >Russian</option>
							<option disabled value="es" 
							<?php
							if ( get_option( 'mo_openid_twitter_follow_lang' ) === 'es' ) {
								echo 'selected';}
							?>
							 >Spanish</option>
							<option disabled value="tr" 
							<?php
							if ( get_option( 'mo_openid_twitter_follow_lang' ) === 'tr' ) {
								echo 'selected';}
							?>
							 >Turkish</option>
						</select>
					</td>
				</tr>
				<tr style="height: 10px;"></tr>
				<tr>
					<td>Show Vote Up Link:</td>
					<td><select disabled name="twitter_follow_button_options[creditOn]" id="creditOn" >
							<option disabled value="true" 
							<?php
							if ( get_option( 'mo_openid_twitter_follow_crediton' ) == 'true' ) {
								echo 'selected';}
							?>
							 >true</option>
							<option disabled value="false" 
							<?php
							if ( get_option( 'mo_openid_twitter_follow_crediton' ) == 'false' ) {
								echo 'selected';}
							?>
							 >false</option>
						</select>
					</td>
				</tr>
			</table>

			<br/><b><input disabled type="submit" name="submit" value="<?php echo esc_attr( mo_sl( 'Save' ) ); ?>" style="width:150px;text-shadow: none;background-color:#0867b2;color:white;box-shadow:none;"  class="button button-primary button-large" /></b>
		</div>
	</form>
	<script>
		//to set heading name
		jQuery('#mo_openid_page_heading').text('<?php echo esc_attr( mo_sl( 'Twitter Follow Button' ) ); ?>');
		var temp = jQuery("<a style=\"left: 1%; padding:4px; position: relative; text-decoration: none\" class=\"mo-openid-premium\" href=\"<?php echo esc_attr( add_query_arg( array( 'tab' => 'licensing_plans' ), sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) ); ?>\">PRO</a>");
		jQuery("#mo_openid_page_heading").append(temp);
		var win_height = jQuery('#mo_openid_menu_height').height();
		//win_height=win_height+18;
		jQuery(".mo_container").css({height:win_height});
	</script>



	<?php
}
