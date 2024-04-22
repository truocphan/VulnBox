<div class="stm_lms_curriculum_v2_wrapper stm_lms_questions_v2_wrapper" v-bind:class="{'loading' : loading, 'loaded' : loaded}">
    <div class="stm_lms_curriculum_v2 stm_lms_questions_v2">

        <?php stm_lms_questions_v2_load_template('questions'); ?>


        <?php stm_lms_questions_v2_load_template('tabs'); ?>

    </div>
</div>