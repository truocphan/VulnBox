<div class="single_quiz_data_wrapper" v-if="quiz.opened">

    <div class="loading" v-if="quiz.loading"></div>

    <div class="single_quiz_data" v-else v-html="quiz.data_html">

    </div>

</div>