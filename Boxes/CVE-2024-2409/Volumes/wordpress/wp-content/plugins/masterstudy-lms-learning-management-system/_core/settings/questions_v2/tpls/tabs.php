<div class="add_items">

    <?php stm_lms_questions_v2_load_template('add_new_nav'); ?>

    <?php stm_lms_questions_v2_load_template('add_new_quiz'); ?>

    <?php stm_lms_questions_v2_load_template('add_new_qb'); ?>

    <questions_search inline-template v-if="search" :items="items" v-on:close_popup="search = false">
        <?php stm_lms_questions_v2_load_template('search'); ?>
    </questions_search>

</div>