<?php
/**
 * @var $post_id
 * @var $item_id
 */
wp_enqueue_script( 'vue-resource.js' );

stm_lms_register_style( 'lesson_comments' );
stm_lms_register_script( 'lesson_comments' );
wp_add_inline_script(
	'stm-lms-lesson_comments',
	"var stm_lms_lesson_id = {$item_id}; var stm_lms_course_id = {$post_id}"
);
$lesson_style = STM_LMS_Options::get_option( 'lesson_style', 'default' );
$lesson_type  = get_post_meta( $item_id, 'type', true );
?>
<div id="stm_lms_lesson_comments" class="stm_lms_lesson_comments" v-bind:class="{'addQuestion' : addQuestion}">

	<div class="stm_lms_lesson_comments__inner">
		<div class="stm_lms_lesson_comments__top">
			<h3>
				<?php if ( 'classic' === $lesson_style ) : ?>
					<div class="stm-lms-course__sidebar_toggle">
						<i class="fa fa-question"></i>
					</div>
				<?php endif; ?>
					<?php esc_html_e( 'Questions', 'masterstudy-lms-learning-management-system' ); ?>
			</h3>
			<?php if ( 'classic' !== $lesson_style ) : ?>
				<a href="#" class="btn btn-default btn-icon" @click.prevent="add_question()">
				<?php esc_html_e( 'My Question', 'masterstudy-lms-learning-management-system' ); ?>
					<i class="lnr lnr-arrow-right"></i>
				</a>
			<?php endif; ?>
		</div>

		<div class="stm_lms_btn_icon search-question-form">
			<input class="form-control"
				name="search"
					v-model="search"
					placeholder="<?php esc_html_e( 'Search Questions', 'masterstudy-lms-learning-management-system' ); ?>"/>
			<button type="button" v-on:click="getCommentsSearch()"><i class="fa fa-search"></i></button>
		</div>

		<div class="stm_lms_lesson_comments__list" v-for="(comment, comment_key) in comments">

			<div class="stm_lms_lesson_comments__list_single" v-bind:class="{'expanded' : comment.expanded}">

				<div class="stm_lms_lesson_comments__list_single_inner" @click.prevent="expandComment(comment_key)">
					<div class="stm_lms_lesson_comments__list_single__text h5" v-html="comment.content"></div>
					<a href="#"
						class="stm_lms_lesson_comments__list_single__replies_count"
						v-if="!comment.expanded">
						<span class="fa fa-comment-dots"></span>
						{{ comment.replies_count }}
					</a>
				</div>

				<transition name="slide-fade">
					<div class="stm_lms_lesson_comments__list_single_expanded" v-if="comment.expanded">

					<div class="stm_lms_lesson_comments__list_single__info">
						<div class="stm_lms_lesson_comments__list_single__login">
							<a v-bind:href="comment.author.url" v-html="comment.author.login" v-if="comment.author.roles.includes('instructor')"></a>
								<span v-html="comment.author.login" v-else></span>
						</div>
						<div class="stm_lms_lesson_comments__list_single__date" v-html="comment.datetime"></div>
					</div>

						<div class="stm_lms_lesson_comments__list_single__reply"
							v-on:blur="textAreaUnFocused(comment_key)"
							v-bind:class="{'focused' : comment.focused}">
							<textarea v-model="reply[comment_key]"
								name="reply"
								v-on:focus="textAreaFocused(comment_key)"
								placeholder="<?php esc_html_e( 'Enter your comment', 'masterstudy-lms-learning-management-system' ); ?>"></textarea>
							<transition name="slide-fade">
								<a v-if="comment.focused"
									href="#"
									v-bind:class="{'loading' : addingComment[comment_key]}"
									@click.prevent="addComment(comment_key)"
									class="btn btn-default">
									<span><?php esc_html_e( 'Send', 'masterstudy-lms-learning-management-system' ); ?></span>
								</a>
							</transition>
						</div>

						<div class="stm_lms_lesson_comments__replies" v-for="(reply, reply_key) in comment.replies">
							<div class="stm_lms_lesson_comments__reply">
								<div class="stm_lms_lesson_comments__list_single__info">
									<!--<div class="stm_lms_lesson_comments__list_single__avatar" v-html="comment.author.avatar"></div>-->
									<div class="stm_lms_lesson_comments__list_single__login">
										<a v-bind:href="reply.author.url" v-html="reply.author.login"></a>
									</div>
									<div class="stm_lms_lesson_comments__list_single__date"
										v-html="reply.datetime"></div>
								</div>
								<div class="stm_lms_lesson_comments__list_single__text" v-html="reply.content"></div>
							</div>
						</div>

					</div>
				</transition>

			</div>

		</div>

		<a href="#"
			@click.prevent="getComments()"
			v-if="has_comments"
			class="btn btn-default load-more-btn load-comments"
			v-bind:class="{'loading' : comments_loading}">
			<span><?php esc_html_e( 'Load More', 'masterstudy-lms-learning-management-system' ); ?></span>
		</a>
		<?php if ( 'classic' === $lesson_style && 'stream' !== $lesson_type && 'zoom_conference' !== $lesson_type ) : ?>
			<a href="#" class="btn btn-default btn-icon my-questions" @click.prevent="add_question()">
				<?php esc_html_e( 'My Question', 'masterstudy-lms-learning-management-system' ); ?>
				<i class="lnr lnr-arrow-right"></i>
			</a>
		<?php endif; ?>
	</div>
	<div class="stm_lms_lesson_comments__add">
		<?php if ( 'classic' !== $lesson_style ) : ?>
			<a href="#" class="stm_lms_lesson_comments__add_back" @click.prevent="add_question()">
				<i class="lnr lnr-arrow-left"></i>
				<span><?php esc_html_e( 'Back to Q&A List', 'masterstudy-lms-learning-management-system' ); ?></span>
			</a>
		<?php endif; ?>
		<?php if ( 'classic' === $lesson_style && 'stream' !== $lesson_type && 'zoom_conference' !== $lesson_type ) : ?>
			<div class="add_comment_title">
				<h3><?php esc_html_e( 'Ask A Question', 'masterstudy-lms-learning-management-system' ); ?></h3>
			</div>
		<?php endif; ?>
		<div class="stm_lms_lesson_comments__add">
			<div class="form-group">
				<textarea v-model="comment_text"
						placeholder="<?php esc_html_e( 'Write your question here', 'masterstudy-lms-learning-management-system' ); ?>"></textarea>
			</div>
			<a href="#" @click.prevent="addComment()" class="btn btn-default" v-bind:class="{'loading' : loading}">
				<span><?php esc_html_e( 'Add Comment', 'masterstudy-lms-learning-management-system' ); ?></span>
			</a>
		</div>
		<div class="stm-lms-message" v-bind:class="status" v-if="message">
			{{ message }}
		</div>
		<div class="stm_lms_lesson_comments__list" v-for="(comment, comment_key) in myComments">
			<div class="stm_lms_lesson_comments__list_single" v-bind:class="{'expanded' : comment.expanded}">
				<div class="stm_lms_lesson_comments__list_single_inner"
					@click.prevent="expandComment(comment_key, true)">
					<div class="stm_lms_lesson_comments__list_single__text" v-html="comment.content"></div>
					<a href="#"
						class="stm_lms_lesson_comments__list_single__replies_count" v-html="comment.replies_count">
					</a>
				</div>
				<transition name="slide-fade">
					<div class="stm_lms_lesson_comments__list_single_expanded" v-if="comment.expanded">
						<div class="stm_lms_lesson_comments__list_single__info">
							<div class="stm_lms_lesson_comments__list_single__login">
								<a v-bind:href="comment.author.url" v-html="comment.author.login"></a>
							</div>
							<div class="stm_lms_lesson_comments__list_single__date" v-html="comment.datetime"></div>
						</div>
						<div class="stm_lms_lesson_comments__list_single__reply"
							v-on:blur="textAreaUnFocused(comment_key)"
							v-bind:class="{'focused' : comment.focused}">
							<textarea v-model="reply[comment_key]"
									name="reply"
									v-on:focus="textAreaFocused(comment_key)"
									placeholder="<?php esc_html_e( 'Enter your comment', 'masterstudy-lms-learning-management-system' ); ?>"></textarea>
							<transition name="slide-fade">
								<a v-if="comment.focused"
									href="#"
									v-bind:class="{'loading' : addingComment[comment_key]}"
									@click.prevent="addComment(comment_key)"
									class="btn btn-default">
									<span><?php esc_html_e( 'Send', 'masterstudy-lms-learning-management-system' ); ?></span>
								</a>
							</transition>
						</div>
						<div class="stm_lms_lesson_comments__replies" v-for="(reply, reply_key) in comment.replies">
							<div class="stm_lms_lesson_comments__reply">
								<div class="stm_lms_lesson_comments__list_single__info">
									<div class="stm_lms_lesson_comments__list_single__login">
										<a v-bind:href="reply.author.url" v-html="reply.author.login"></a>
									</div>
									<div class="stm_lms_lesson_comments__list_single__date"
										v-html="reply.datetime"></div>
								</div>
								<div class="stm_lms_lesson_comments__list_single__text" v-html="reply.content"></div>
							</div>
						</div>
					</div>
				</transition>
			</div>
		</div>
		<?php if ( 'classic' === $lesson_style && 'stream' !== $lesson_type && 'zoom_conference' !== $lesson_type ) : ?>
			<a href="#" class="btn btn-back" @click.prevent="add_question()">
				<i class="fa fa-arrow-circle-left"></i>
				<span><?php esc_html_e( 'Back to all questions', 'masterstudy-lms-learning-management-system' ); ?></span>
			</a>
		<?php endif; ?>
	</div>

</div>
