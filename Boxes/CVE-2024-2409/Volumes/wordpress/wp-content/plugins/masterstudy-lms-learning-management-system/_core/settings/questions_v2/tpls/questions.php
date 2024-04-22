<draggable :list="items"
           class="sections dragArea"
           :options="{ group: 'section'}"
           handle=".question_move">

    <div class="section"
         :key="item.id"
         v-if="item"
         v-for="(item, item_key) in items">

        <?php stm_lms_questions_v2_load_template('question_data'); ?>

        <?php stm_lms_questions_v2_load_template('question_items'); ?>

    </div>

</draggable>