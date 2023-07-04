<?php
require_once ABSPATH . 'wp-includes/pluggable.php';

echo '  <div id="mo_openid_rateus_myModal" class="mo_openid_modal_rateus">
                       
                        <div class="mo_openid_rateus_modal_content" id="color_change" style="background-color: #FFFFFF">
                            <div id="moOpenIdRateUs">
                            
                                <span class="mo_openid_star-cb-group" >
                                        <table style="width: 100%"><tr style="background-color: #0867b2"><td>
                                                    <span style="margin-top: 2%;margin-right: 2%" class="mo_openid_rateus_close">&times;</span>
                                            <center><h2 style="color: #FFFFFF"><strong>Rate Us</strong></h2></center></td></tr>
                                </table><form>
                                            <fieldset class="mo-openid-star-back-rateus"  id="mo_openid_fieldset" style="margin-top: 20%">
                                               <span class="mo_openid_star-cb-group">
                                                    <input type="radio" id="mo_openid_rating-5" name="mo_openid_rating" value="5" onclick="window.open(\'https://wordpress.org/support/plugin/miniorange-login-openid/reviews/\', \'_blank\'); five_star();"  /><label for="mo_openid_rating-5">5</label>
                                                    <input type="radio" id="mo_openid_rating-4" name="mo_openid_rating" value="4" onclick="form_popup(4); " /><label for="mo_openid_rating-4">4</label>
                                                    <input type="radio" id="mo_openid_rating-3" name="mo_openid_rating" value="3" onclick="form_popup(3); " /><label for="mo_openid_rating-3">3</label>
                                                    <input type="radio" id="mo_openid_rating-2" name="mo_openid_rating" value="2" onclick="form_popup(2); " /><label for="mo_openid_rating-2">2</label>
                                                    <input type="radio" id="mo_openid_rating-1" name="mo_openid_rating" value="1" onclick="form_popup(1); " /><label for="mo_openid_rating-1">1</label>
                                                    <input type="radio" id="mo_openid_rating-0" name="mo_openid_rating" value="0" class="mo_openid_star-cb-clear" /><label for="mo_openid_rating-0">0</label>
                                                </span>
                                            </fieldset>
                                        </form>
                                    </span>
                            </div>
                            <div id="mo_openid_support_form_feedback" class="mo-support-form" style="display: none;" >
                                <table style="width: 100%"><tr style="background-color: #0867b2"><td>
                                            <span style="margin-top: 2%;margin-right: 2%" class="mo_openid_rateus_feedback_close">&times;</span>

                                            <center><h2 style="color: #FFFFFF"><strong>FEEDBACK FORM</strong></h2></center></td></tr>
                                </table>
                                <div ><br>
                                    <form id="mo_openid_rateus_submit_form" method="post" action="">
                                        <input type="hidden" name="option" value="mo_openid_rateus_query_option" />
                                        <input type="hidden" name="mo_openid_rateus_nonce" value="' . esc_attr( wp_create_nonce( 'mo-openid-rateus-nonce' ) ) . '"/>
                                        <label style="margin-left: 4%"> We would be glad to hear what you think</label>
                                        <input class="mo_openid_modal_rateus_style" type="email" style=" margin-left: 5%;width: 87%; border-bottom: 1px solid; border-bottom-color:#0867b2 " type="email"  required placeholder="Enter your Email" name="mo_openid_rateus_email" value="' . esc_attr( get_option( 'mo_openid_admin_email' ) ) . '">

                                        <table style="margin-left: 5%; width: 91%;height: 30%">
                                            <tr style="width: 50%">
                                                <td>
                                                    <textarea class="mo_openid_modal_rateus_style" id="subject" required name="mo_openid_rateus_query" onkeypress="mo_openid_valid_query(this)" onkeyup="mo_openid_valid_query(this)" onblur="mo_openid_valid_query(this)"  placeholder="Write something.." style="height:100%;width: 100%;border-bottom: 1px solid; border-bottom-color:#0867b2 "></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input class="button button-primary button-large" style="width: 35%" type="submit" name="submit" value="submit">
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>';
?>

<script>
	function five_star() {
		jQuery("#mo_openid_rateus_myModal").hide();
		jQuery("#mo_openid_rating-5").prop('checked',false);

	}
	function form_popup(rating){
		var mo_openid_rating_given_nonce = '<?php echo esc_attr( wp_create_nonce( 'mo-openid-rating-given-nonce' ) ); ?>';
		jQuery.ajax({
			url: "<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>", //the page containing php script
			method: "POST", //request type,
			dataType: 'json',
			data: {
				action: 'mo_openid_rating_given',
				rating: rating,
				'mo_openid_rating_given' : mo_openid_rating_given_nonce,
			},
			success: function (result) {
				jQuery("#mo_openid_support_form_feedback").show();
				jQuery("#moOpenIdRateUs").hide();
			}
		});
	}
	function asdf(asdf1) {
		var modal = document.getElementById("mo_openid_rateus_myModal");
		var mo_btn = document.getElementById("mo_openid_rateus_modal");
		mo_btn.onclick = function() {
			jQuery("#mo_openid_support_form_feedback").hide();
			jQuery("#mo_openid_rating-4").prop('checked',false);
			jQuery("#mo_openid_rating-3").prop('checked',false);
			jQuery("#mo_openid_rating-2").prop('checked',false);
			jQuery("#mo_openid_rating-1").prop('checked',false);
			jQuery("#mo_openid_rating-0").prop('checked',false);
			modal.style.display ="block";
			jQuery("#moOpenIdRateUs").show();

			var mo_openid_span = document.getElementsByClassName("mo_openid_rateus_close")[0];


			// When the user clicks the button, open the modal


			// When the user clicks on <span> (x), close the modal
			mo_openid_span.onclick = function() {
				modal.style.display = "none";
				window.onclick = function(event){
					if (event.target == modal) {
						modal.style.display = "none";
					}
				}

			}



		}
		var mo_openid_span1 = document.getElementsByClassName("mo_openid_rateus_feedback_close")[0];
		mo_openid_span1.onclick = function() {
			modal.style.display = "none";
			window.onclick = function(event){
				if (event.target == modal) {
					modal.style.display = "none";
				}
			}
		}

	}

</script>

