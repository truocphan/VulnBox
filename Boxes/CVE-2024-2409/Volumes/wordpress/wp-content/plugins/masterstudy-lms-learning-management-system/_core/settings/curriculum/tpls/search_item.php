<div class="item_icon" v-bind:class="item.post_type">

    <span v-if="item.post_type==='stm-assignments'">
        <?php STM_LMS_Helpers::print_svg('settings/curriculum/images/assignment.svg'); ?>
    </span>

    <span v-if="item.post_type==='stm-quizzes'">
        <?php STM_LMS_Helpers::print_svg('settings/curriculum/images/quiz.svg'); ?>
    </span>

    <span v-if="item.post_type==='stm-lessons'">
        <?php STM_LMS_Helpers::print_svg('settings/curriculum/images/text.svg'); ?>
    </span>

</div>

<div class="title" v-html="item.title"></div>

<div class="actions">
    <div class="item_select"></div>
</div>