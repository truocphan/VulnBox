<?php
/**
 * @var $current_user
 */

?>

<div class="stm_lms_chat_companion" v-if="conversations[conversation]">
	<div class="stm_lms_chat_companion__image" v-html="conversations[conversation]['companion']['avatar']"></div>
	<div class="stm_lms_chat_companion__title" v-html="conversations[conversation]['companion']['login']"></div>

	<i class="lnr lnr-sync" @click="updateConversation()" v-bind:class="{'active' : updating}"></i>
</div>

<div class="stm_lms_chat_messages" id="stm_lms_chat_messages"
	v-if="conversations[conversation] && conversations[conversation]['messages']">

	<div class="stm_lms_chat_messages__single"
		v-bind:class="{'owner_message' : message.isOwner, 'companion_message' : !message.isOwner}"
		v-for="message in conversations[conversation]['messages']">

		<div class="stm_lms_chat_messages__single_message" v-html="message.message"></div>

		<div class="stm_lms_chat_messages__single_user">
			<div class="stm_lms_chat_companion">
				<div class="stm_lms_chat_companion__title">
					<h5 v-html="message['companion']['login']"></h5>
					<label v-html="message.ago"></label>
				</div>
			</div>
		</div>
	</div>

</div>

<div class="stm_lms_chat_messages__send" v-if="conversations[conversation]">
	<h4><?php esc_html_e( 'Reply to', 'masterstudy-lms-learning-management-system' ); ?>
		<span>{{conversations[conversation]['companion']['login']}}</span></h4>
	<textarea v-model="myMessage"
		placeholder="<?php esc_html_e( 'Your message', 'masterstudy-lms-learning-management-system' ); ?>"></textarea>
	<a href="#"
		@click.prevent="sendMessage()"
		class="btn btn-default"
		v-bind:class="{'loading': loading}">
		<span><?php esc_html_e( 'Send message', 'masterstudy-lms-learning-management-system' ); ?></span>
	</a>
	<p v-if="myResponse" v-model="myResponse" class="stm-lms-message error">{{ myResponse }}</p>
</div>
