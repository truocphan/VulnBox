<transition name="slide">

    <div class="stm-lms-dashboard-inner stm-lms-dashboard-courses">

        <back></back>

        <h2>
            <?php esc_html_e('All Courses', 'masterstudy-lms-learning-management-system'); ?>
            <span>(<?php echo STM_LMS_User_Manager_Courses::published(); ?>)</span>
        </h2>

        <div class="loading" v-if="loading"></div>

        <div v-else>

            <div class="courses">


                <table class="lms-dashboard-table">

                    <thead>
                        <tr>
                            <th class="title"><?php esc_html_e('Course title', 'masterstudy-lms-learning-management-system'); ?></th>
                            <th class="category"><?php esc_html_e('Category', 'masterstudy-lms-learning-management-system'); ?></th>
                            <th class="price"><?php esc_html_e('Price', 'masterstudy-lms-learning-management-system'); ?></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="course in courses">
                            <td class="title" v-html="course.title"></td>
                            <td class="category" v-html="course.categories.join(' ')"></td>
                            <td class="price" v-html="course.price"></td>
                        </tr>
                    </tbody>

                </table>

            </div>

            <div class="pagination" v-if="pages > 1">
                <a href="#"
                   v-for="s_page in pages"
                   v-on:click.prevent="switchPage(s_page)"
                   v-bind:class="{'active' : page == s_page}">{{s_page}}</a>
            </div>

        </div>

    </div>

</transition>