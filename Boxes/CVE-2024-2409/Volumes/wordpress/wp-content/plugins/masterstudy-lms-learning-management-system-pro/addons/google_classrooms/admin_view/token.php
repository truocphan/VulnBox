<?php if ( empty( $google_classroom->get_auth_token() ) ) : ?>

	<!--First we getting config json-->
	<?php if ( empty( $google_classroom->getAuthConfig() ) ) : ?>

		<div class="stm_lms_g_c_create_auth_file">

			<div class="stm_lms_g_c_create_auth_file__label">
				<?php
				printf(
				/* translators: %s Title */
					__( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						'Create OAuth access data and upload Credentials JSON from <a href="https://console.developers.google.com/apis/credentials" target="_blank">Google Console</a>. As a redirect URI set <strong>%s</strong>',
						'masterstudy-lms-learning-management-system-pro'
					),
					esc_url( admin_url( 'admin.php?page=google_classrooms' ) )
				);
				?>
			</div>

			<div class="stm_lms_g_c_create_auth_file__load" v-if="!loadingJson">

				<div>
					<input type="file"
							ref="credentialsJson"
							class="form-control"
							@change="previewFiles"/>
				</div>

				<a href="#" class="stm_lms_material_button" @click.prevent="uploadCredentials">
					<span><?php esc_html_e( 'Load Credentials', 'masterstudy-lms-learning-management-system-pro' ); ?></span>
				</a>

			</div>

			<div class="stm_lms_material_button loading" v-else>
				<i class="lnr lnr-sync"></i>
			</div>

			<div class="stm_lms_g_c_create_auth_file__message" v-if="is_error" v-html="json_message"></div>

		</div>

	<?php else : ?>

		<a href="<?php echo esc_url( $google_classroom->delete_credentials() ); ?>" class="revoke_token">
			<?php esc_html_e( 'Delete Credentials', 'masterstudy-lms-learning-management-system-pro' ); ?>
		</a>

		<a href="<?php echo esc_url( $google_classroom->get_auth_url() ); ?>" class="add_token">
			<?php esc_html_e( 'Get Access Token', 'masterstudy-lms-learning-management-system-pro' ); ?>
		</a>

	<?php endif; ?>


<?php else : ?>

	<a href="<?php echo esc_url( $google_classroom->revoke_token_url() ); ?>" class="revoke_token">
		<?php esc_html_e( 'Revoke Access Token', 'masterstudy-lms-learning-management-system-pro' ); ?>
	</a>


<?php endif; ?>
