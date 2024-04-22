<div class="ms_lms_slider_custom swiper-container">
	<div class="ms_lms_slider_custom_wrapper swiper-wrapper">
	<?php if ( ! empty( $slides ) ) {
		foreach ( $slides as $slide ) { ?>
			<div class="ms_lms_slider_custom__slide swiper-slide elementor-repeater-item-<?php echo esc_attr( $slide['_id'] ); ?>">
				<div class="ms_lms_slider_custom__slide_overlay">
					<?php if ( ! empty( $slide['show_info_block'] ) ) { ?>
						<div class="ms_lms_slider_custom__slide_infoblock <?php echo esc_attr( ( ! empty( $slide['info_block_preset'] ) ) ? $slide['info_block_preset'] : '' ); ?>">
							<div class="ms_lms_slider_custom__slide_infoblock_wrapper <?php echo esc_attr( ( ! empty( $slide['info_block_animation_effect'] ) ) ? $slide['info_block_animation_effect'] : '' ); ?> <?php echo esc_attr( ( ! empty( $slide['info_block_full_width'] ) ) ? 'lms-full-width' : '' ); ?>">
								<?php if ( ! empty( $slide['info_block_title'] ) ) { ?>
									<h2 class="ms_lms_slider_custom__slide_infoblock_title <?php echo esc_attr( ( ! empty( $slide['info_block_title_animation'] ) ) ? $slide['info_block_title_animation'] : '' ); ?>">
										<?php echo esc_html( $slide['info_block_title'] ); ?>
									</h2>
								<?php } ?>
								<?php if ( ! empty( $slide['info_block_description'] ) ) { ?>
									<p class="ms_lms_slider_custom__slide_infoblock_description <?php echo esc_attr( ( ! empty( $slide['info_block_description_animation'] ) ) ? $slide['info_block_description_animation'] : '' ); ?>">
										<?php echo esc_html( $slide['info_block_description'] ); ?>
									</p>
									<?php
								}
								if ( ! empty( $slide['show_info_block_first_button'] ) || ! empty( $slide['show_info_block_second_button'] ) ) {
									?>
								<div class="ms_lms_slider_custom__slide_infoblock_buttons_wrapper <?php echo esc_attr( ( ! empty( $slide['info_block_buttons_animation'] ) ) ? $slide['info_block_buttons_animation'] : '' ); ?>">
									<?php if ( ! empty( $slide['show_info_block_first_button'] ) ) { ?>
										<a href="<?php echo ( ! empty( $slide['info_block_first_button_link']['url'] ) ) ? esc_url( $slide['info_block_first_button_link']['url'] ) : '#'; ?>"
										<?php
										echo ( $slide['info_block_first_button_link']['is_external'] ) ? ' target="_blank"' : '';
										echo ( $slide['info_block_first_button_link']['nofollow'] ) ? ' rel="nofollow"' : '';
										if ( ! empty( $slide['info_block_first_button_link']['custom_attributes'] ) ) {
											$pairs = explode( ',', $slide['info_block_first_button_link']['custom_attributes'] );
											foreach ( $pairs as $pair ) {
												list( $key, $value ) = explode( '|', $pair );
												echo ' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
											}
										}
										?>
										class="ms_lms_slider_custom__slide_infoblock_first-button">
											<?php
											if ( ! empty( $slide['info_block_first_button_icon'] ) ) {
												if ( ! empty( $slide['info_block_first_button_icon']['value']['url'] ) ) {
													?>
													<span class="ms_lms_slider_custom__slide_infoblock_first-button_icon <?php echo esc_attr( ( ! empty( $slide['info_block_first_button_icon_position'] ) ) ? $slide['info_block_first_button_icon_position'] : '' ); ?>">
														<img src="<?php echo esc_url( $slide['info_block_first_button_icon']['value']['url'] ); ?>" alt="">
													</span>
													<?php
												} elseif ( ! empty( $slide['info_block_first_button_icon']['library'] ) ) {
													?>
													<span class="ms_lms_slider_custom__slide_infoblock_first-button_icon <?php echo esc_attr( ( ! empty( $slide['info_block_first_button_icon_position'] ) ) ? $slide['info_block_first_button_icon_position'] : '' ); ?>">
														<i class="<?php echo esc_attr( $slide['info_block_first_button_icon']['value'] ); ?>"></i>
													</span>
													<?php
												}
											}
											?>
											<span class="ms_lms_slider_custom__slide_infoblock_first-button_title"><?php echo esc_html( $slide['info_block_first_button_title'] ); ?></span>
										</a>
									<?php } ?>
									<?php if ( ! empty( $slide['show_info_block_second_button'] ) ) { ?>
										<a href="<?php echo ( ! empty( $slide['info_block_second_button_link']['url'] ) ) ? esc_url( $slide['info_block_second_button_link']['url'] ) : '#'; ?>"
										<?php
										echo ( $slide['info_block_second_button_link']['is_external'] ) ? ' target="_blank"' : '';
										echo ( $slide['info_block_second_button_link']['nofollow'] ) ? ' rel="nofollow"' : '';
										if ( ! empty( $slide['info_block_second_button_link']['custom_attributes'] ) ) {
											$pairs = explode( ',', $slide['info_block_second_button_link']['custom_attributes'] );
											foreach ( $pairs as $pair ) {
												list( $key, $value ) = explode( '|', $pair );
												echo ' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
											}
										}
										?>
										class="ms_lms_slider_custom__slide_infoblock_second-button">
											<?php
											if ( ! empty( $slide['info_block_second_button_icon'] ) ) {
												if ( ! empty( $slide['info_block_second_button_icon']['value']['url'] ) ) {
													?>
													<span class="ms_lms_slider_custom__slide_infoblock_second-button_icon <?php echo esc_attr( ( ! empty( $slide['info_block_second_button_icon_position'] ) ) ? $slide['info_block_second_button_icon_position'] : '' ); ?>">
														<img src="<?php echo esc_url( $slide['info_block_second_button_icon']['value']['url'] ); ?>" alt="">
													</span>
													<?php
												} elseif ( ! empty( $slide['info_block_second_button_icon']['library'] ) ) {
													?>
													<span class="ms_lms_slider_custom__slide_infoblock_second-button_icon <?php echo esc_attr( ( ! empty( $slide['info_block_second_button_icon_position'] ) ) ? $slide['info_block_second_button_icon_position'] : '' ); ?>">
														<i class="<?php echo esc_attr( $slide['info_block_second_button_icon']['value'] ); ?>"></i>
													</span>
													<?php
												}
											}
											?>
											<span class="ms_lms_slider_custom__slide_infoblock_second-button_title"><?php echo esc_html( $slide['info_block_second_button_title'] ); ?></span>
										</a>
									<?php } ?>
								</div>
							<?php } ?>
							</div>
						</div>
						<?php } ?>
				</div>
				<?php
				if ( ! empty( $slide['slide_image'] ) ) {
					echo wp_get_attachment_image( $slide['slide_image']['id'], 'full' );
				}
				?>
			</div>
			<?php
		}
	}
	?>
	</div>
	<?php if ( ! empty( $show_navigation ) ) { ?>
		<button class="ms_lms_slider_custom__navigation_prev <?php echo ( ! empty( $navigation_presets ) ) ? esc_attr( $navigation_presets ) : 'style_1'; ?> <?php echo ( ! empty( $navigation_position ) ) ? esc_attr( $navigation_position ) : ''; ?>">
			<i class="lnr lnr-chevron-left"></i>
		</button>
		<button class="ms_lms_slider_custom__navigation_next <?php echo ( ! empty( $navigation_presets ) ) ? esc_attr( $navigation_presets ) : 'style_1'; ?> <?php echo ( ! empty( $navigation_position ) ) ? esc_attr( $navigation_position ) : ''; ?>">
			<i class="lnr lnr-chevron-right"></i>
		</button>
	<?php } ?>
</div>
