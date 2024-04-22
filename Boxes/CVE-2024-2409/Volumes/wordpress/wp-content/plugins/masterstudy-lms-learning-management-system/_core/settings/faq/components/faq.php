<div>
    <div class="stm-lms-faq unflex_fields">

        <div class="stm_lms_faq">
            <div class="stm_lms_faq__single" v-for="(item, key) in faq">
                <div class="stm_lms_faq__single_top">
                    <i class="stm_lms_faq__single_delete fa fa-trash-alt" @click="deleteItem(key)"></i>
                </div>

                <div class="wpcfto_generic_field wpcfto_generic_field_flex_input">
                    <label><?php esc_html_e('Question', 'masterstudy-lms-learning-management-system'); ?> {{ key + 1 }}</label>
                    <input type="text" v-model="item['question']" placeholder="<?php esc_html_e('Enter FAQ question', 'masterstudy-lms-learning-management-system') ?>" />
                </div>

                <div class="wpcfto_generic_field wpcfto_generic_field_flex_input">
                    <label><?php esc_html_e('Answer', 'masterstudy-lms-learning-management-system'); ?> {{ key + 1 }}</label>
                    <textarea v-model="item['answer']" placeholder="<?php esc_html_e('Enter FAQ answer', 'masterstudy-lms-learning-management-system') ?>"></textarea>
                </div>
            </div>
        </div>


        <div class="addArea" @click="addNew()">
            <i class="fa fa-plus"></i>
            <span><?php esc_html_e('New FAQ item', 'masterstudy-lms-learning-management-system'); ?></span>
        </div>

    </div>
</div>