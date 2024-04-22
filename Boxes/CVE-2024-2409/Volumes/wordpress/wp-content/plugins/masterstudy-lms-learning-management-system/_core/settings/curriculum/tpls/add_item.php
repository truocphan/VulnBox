<div class="add_item" v-bind:class="type">

    <div class="add_item_input" v-bind:class="{'loading_v2' : loading}">

        <input v-model="itemTitle"
               @keydown.enter.prevent="addItem"
               placeholder="<?php esc_attr_e('Enter Lesson title', 'masterstudy-lms-learning-management-system'); ?>"
               v-if="type === 'stm-lessons'"/>

        <input v-model="itemTitle"
               @keydown.enter.prevent="addItem"
               placeholder="<?php esc_attr_e('Enter Quiz title', 'masterstudy-lms-learning-management-system'); ?>"
               v-if="type === 'stm-quizzes'"/>

        <input v-model="itemTitle"
               @keydown.enter.prevent="addItem"
               placeholder="<?php esc_attr_e('Enter Assignment title', 'masterstudy-lms-learning-management-system'); ?>"
               v-if="type === 'stm-assignments'"/>

        <div class="add_item_submit" @click="addItem">
            <?php STM_LMS_Helpers::print_svg('settings/curriculum/images/enter.svg'); ?>
        </div>

    </div>

</div>