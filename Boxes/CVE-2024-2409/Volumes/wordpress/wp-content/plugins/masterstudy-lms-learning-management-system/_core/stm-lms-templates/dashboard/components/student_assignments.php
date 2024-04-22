<transition name="slide">

    <div class="dashboard-student-assignments">

        <div class="loading" v-if="loading"></div>

        <div class="dashboard-student-assignments--table" v-else>

            <div v-if="assignments.length">

                <div v-for="(assignment, assignment_key) in assignments" class="assingment-single">


                    <div class="content">
                        <h5><?php esc_html_e('Student attempt', 'masterstudy-lms-learning-management-system'); ?> #{{assignment_key + 1}}</h5>
                        <div class="content" v-html="assignment.content" v-if="assignment.content"></div>
                        <div class="content" v-else>---</div>
                    </div>

                    <div class="status">
                        <div class="status" v-bind:class="assignment.meta.status">
                            <i class="fa fa-check" v-if="assignment.meta.status==='passed'"></i>
                            <i class="fa fa-times" v-else></i>
                        </div>
                    </div>

                    <div class="comment">
                        <h5><?php esc_html_e('Instructor answer', 'masterstudy-lms-learning-management-system'); ?></h5>
                        <div class="content">
                            <div class="author">
                                <div class="img" v-html="instructor_data.avatar"></div>
                                <div class="author__info">
                                    <span v-html="instructor_data.meta.position" v-if="instructor_data.meta.position"></span>
                                    <h5 v-html="instructor_data.login"></h5>
                                </div>
                            </div>
                            <div v-html="assignment.meta.editor_comment" v-if="assignment.meta.editor_comment" class="inside_content"></div>
                            <div class="content" v-else>---</div>
                        </div>
                    </div>

                </div>
            </div>

            <h4 v-else><?php esc_html_e('No assignments yet...', 'masterstudy-lms-learning-management-system'); ?></h4>

        </div>

    </div>

</transition>