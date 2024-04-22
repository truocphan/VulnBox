<transition name="slide">

    <div class="dashboard-student-assignments">

        <div class="loading" v-if="loading"></div>

        <div class="dashboard-student-assignments--table" v-else>

            <div v-if="quizzes.length">

                <div v-for="(quiz, quiz_key) in quizzes" class="quiz-single" v-bind:class="{'passed' : quiz.status}">
                    <div class="quiz-single_title">
                        <?php esc_html_e('Attempt #', 'masterstudy-lms-learning-management-system') ?> {{quiz_key + 1}}
                    </div>
                    <div class="quiz-single_progress">
                        <div class="progress-wrapper">
                            <div class="progress">
                                <div class="progress-bar progress-bar-success"
                                     v-bind:class="{'active progress-bar-striped' : quiz.status !== 'passed'}"
                                     v-bind:style="{'width': quiz.progress + '%'}"></div>
                            </div>
                            <div class="progress-label">{{quiz.progress}}%</div>
                        </div>
                    </div>
                    <div class="quiz-single_toggle" @click="openQuiz(quiz, quiz_key + 1)">
                        <span><?php esc_html_e('Show student answers', 'masterstudy-lms-learning-management-system'); ?></span>
                        <i class="fa fa-chevron-down"></i>
                    </div>

                    <?php STM_LMS_Templates::show_lms_template('dashboard/parts/single_quiz'); ?>

                </div>
            </div>

            <h4 v-else><?php esc_html_e('No quizzes yet...', 'masterstudy-lms-learning-management-system'); ?></h4>

        </div>

    </div>

</transition>