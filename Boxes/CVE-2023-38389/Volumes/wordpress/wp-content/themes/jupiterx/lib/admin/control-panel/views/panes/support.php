<?php
return;
$hot_topics = [];
?>
<div class="jupiterx-cp-pane-box" id="jupiterx-cp-support">
	<h3> <?php esc_html_e( 'Updates', 'jupiterx' ); ?></h3>
	<div class="row">
		<div class="col-md-4">
			<div class="jupiterx-cp-support-item">
				<div class="jupiterx-cp-support-item-header">
					<img src="<?php echo esc_url( JUPITERX_ADMIN_URL . 'control-panel/assets/images/book-solid-grey.svg' ); ?>" alt="<?php _e( 'Articles', 'jupiterx' ); ?>">
					<h2><?php _e( 'Articles', 'jupiterx' ); ?></h2>
				</div>
				<div class="jupiterx-cp-support-item-content">
					<p><?php _e( 'Need help? Check this extensive documentation for trouble shooting or learning more about Jupiter X.', 'jupiterx' ); ?></p>
					<a class="btn btn-primary" href="<?php echo esc_url( 'https://themes.artbees.net/docs/getting-help-from-the-artbees-support/' );?>" target="_blank"><?php _e( 'Read Articles', 'jupiterx' ); ?></a>
				</div>
				<?php if ( count( $hot_topics ) > 0 ) : ?>
				<div class="jupiterx-cp-support-item-hot-topics">
					<h6><?php _e( 'Hot topics:', 'jupiterx' ); ?></h6>
					<ul>
						<?php foreach($hot_topics as $hot_topic) : ?>
						<li>
							<img src="<?php echo esc_url( JUPITERX_ADMIN_URL . 'control-panel/assets/images/fire-solid-grey.svg' ); ?>" alt="<?php echo esc_attr( $hot_topic['title'] ); ?>">
							<a href="<?php echo esc_url( $hot_topic['link'] ); ?>" target="_blank"><?php echo esc_html( $hot_topic['title'] ); ?></a>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<div class="col-md-4">
			<div class="jupiterx-cp-support-item">
				<div class="jupiterx-cp-support-item-header">
					<img src="<?php echo esc_url( JUPITERX_ADMIN_URL . 'control-panel/assets/images/comments-solid-grey.svg' ); ?>" alt="<?php _e( 'Ask a question', 'jupiterx' ); ?>">
					<h2><?php _e( 'Ask a question', 'jupiterx' ); ?></h2>
				</div>
				<div class="jupiterx-cp-support-item-content">
					<p>
						<?php _e( 'Check Jupiter X support forum and ask away any questions you have. There also many answered topics.', 'jupiterx' ); ?><br />
						<a href="<?php echo esc_url( '#' );?>" target="_blank"><?php _e( 'Open a thread', 'jupiterx' ); ?></a>
					</p>
				</div>
			</div>
			<div class="jupiterx-cp-support-item">
				<div class="jupiterx-cp-support-item-header">
					<img src="<?php echo esc_url( JUPITERX_ADMIN_URL . 'control-panel/assets/images/history-solid-grey.svg' ); ?>" alt="<?php _e( 'Release history', 'jupiterx' ); ?>">
					<h2><?php _e( 'Release history', 'jupiterx' ); ?></h2>
				</div>
				<div class="jupiterx-cp-support-item-content">
					<p>
						<?php _e( 'Check Jupiter X support forum and ask away any questions you have. There also many answered topics.', 'jupiterx' ); ?><br />
						<a href="<?php echo esc_url( 'https://themes.artbees.net/support/jupiterx/release-notes/' );?>" target="_blank"><?php _e( 'Release history', 'jupiterx' ); ?></a>
					</p>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="jupiterx-cp-support-item">
				<div class="jupiterx-cp-support-item-header">
					<img src="<?php echo esc_url( JUPITERX_ADMIN_URL . 'control-panel/assets/images/bug-solid-grey.svg' ); ?>" alt="<?php _e( 'Report a bug', 'jupiterx' ); ?>">
					<h2><?php _e( 'Report a bug', 'jupiterx' ); ?></h2>
				</div>
				<div class="jupiterx-cp-support-item-content">
					<p>
						<?php _e( 'Help us make Jupiter even a more pleasant product to work with.', 'jupiterx' ); ?><br />
						<a href="<?php echo esc_url( '#' );?>" target="_blank"><?php _e( 'Give us a feedback', 'jupiterx' ); ?></a>
					</p>
				</div>
			</div>
			<div class="jupiterx-cp-support-item">
				<div class="jupiterx-cp-support-item-header">
					<img src="<?php echo esc_url( JUPITERX_ADMIN_URL . 'control-panel/assets/images/video-solid-grey.svg' ); ?>" alt="<?php _e( 'Video tutorials', 'jupiterx' ); ?>">
					<h2><?php _e( 'Video tutorials', 'jupiterx' ); ?></h2>
				</div>
				<div class="jupiterx-cp-support-item-content">
					<p>
						<?php _e( 'Quick and narrative video tutorials including tips & tricks and how-to\'s.', 'jupiterx' ); ?><br />
						<a href="<?php echo esc_url( 'https://themes.artbees.net/support/jupiterx/videos/' );?>" target="_blank"><?php _e( 'Start watching', 'jupiterx' ); ?></a>
					</p>
				</div>
			</div>
			<div class="jupiterx-cp-support-item">
				<div class="jupiterx-cp-support-item-header">
					<i class="jupiterx-icon-pro"></i>
					<h2><?php _e( 'Pre-sale questions?', 'jupiterx' ); ?></h2>
				</div>
				<div class="jupiterx-cp-support-item-content">
					<p>
						<?php _e( 'Any questions about the Pro version of  Jupiter X? Let’s talk.', 'jupiterx' ); ?><br />
						<a href="<?php echo esc_url( '#' );?>" target="_blank"><?php _e( 'Open a thread', 'jupiterx' ); ?></a>
					</p>
				</div>
			</div>
		</div>
	</div>
</div>
