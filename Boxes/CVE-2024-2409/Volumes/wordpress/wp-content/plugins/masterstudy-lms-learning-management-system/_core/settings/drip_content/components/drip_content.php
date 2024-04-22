<div class="stm-drip-content-wrapper">

    <div class="wpcfto_generic_field">

        <div class="wpcfto-field-aside">
            <label v-html="label" v-if="label" class="wpcfto-field-aside__label"></label>
        </div>

        <div class="wpcfto-field-content">
            <div class="wpcfto-autocomplete-drips-list" v-if="items.length">

                <div v-for="(item, index) in items"
                     class="wpcfto-autocomplete-drips">

                    <div class="wpcfto-autocomplete-drips--title">
                        <?php esc_html_e("Drip Content", 'masterstudy-lms-learning-management-system'); ?> {{index + 1}}
                        <i class="fa fa-trash-alt" @click="items.splice(index, 1)"></i>
                    </div>


                    <div class="wpcfto-autocomplete-drips--content">

                        <div class="wpcfto-autocomplete-childs--search v-select-search" v-if="typeof item.parent.title === 'undefined'">

                            <i class="fa fa-plus"></i>

                            <v-select label="title"
                                      v-model="item.search"
                                      :options="options"
                                      @input="setSelected($event, item, 'parent')"
                                      @search="onSearch($event, [])">
                            </v-select>

                            <span class="v-select-search-label"><?php esc_html_e('Add must be done lesson', 'masterstudy-lms-learning-management-system'); ?></span>

                        </div>

                        <div class="wpcfto-autocomplete-drips--parents">

                            <div class="wpcfto-autocomplete-drips--childs">

                                <div class="wpcfto-autocomplete-drips--child" v-if="item.parent.title">

                                    <span v-if="item.parent.post_type==='stm-assignments'">
                                        <?php STM_LMS_Helpers::print_svg('settings/curriculum/images/assignment.svg'); ?>
                                    </span>

                                    <span v-if="item.parent.post_type==='stm-quizzes'">
                                        <?php STM_LMS_Helpers::print_svg('settings/curriculum/images/quiz.svg'); ?>
                                    </span>

                                    <span v-if="item.parent.post_type==='stm-lessons'">
                                        <?php STM_LMS_Helpers::print_svg('settings/curriculum/images/text.svg'); ?>
                                    </span>

                                    <span v-html="item.parent.title"></span>

                                    <i class="lnricons-cross" @click="$set(item, 'parent', {})"></i>

                                </div>

                            </div>

                            <div class="wpcfto-autocomplete-drips--parent" v-for="(child, child_index) in item.childs">

                                <div class="wpcfto-autocomplete-drips--parent-title">

                                    <span v-if="child.post_type==='stm-assignments'">
                                        <?php STM_LMS_Helpers::print_svg('settings/curriculum/images/assignment.svg'); ?>
                                    </span>

                                    <span v-if="child.post_type==='stm-quizzes'">
                                        <?php STM_LMS_Helpers::print_svg('settings/curriculum/images/quiz.svg'); ?>
                                    </span>

                                    <span v-if="child.post_type==='stm-lessons'">
                                        <?php STM_LMS_Helpers::print_svg('settings/curriculum/images/text.svg'); ?>
                                    </span>

                                    {{child.title}}

                                    <i class="lnricons-cross" @click="item.childs.splice(child_index, 1)"></i>

                                </div>

                            </div>

                            <div class="wpcfto-autocomplete-drips--search v-select-search">

                                <i class="fa fa-plus"></i>

                                <v-select label="title"
                                          :options="options"
                                          @input="setSelected($event, item.childs, item.childs.length)"
                                          @search="onSearch($event, item.childs)">
                                </v-select>

                                <span class="v-select-search-label"><?php esc_html_e('Target lesson', 'masterstudy-lms-learning-management-system'); ?></span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="addArea" @click="addNewParent()">
                <i class="fa fa-plus"></i>
                <span><?php esc_html_e('Add dependency', 'masterstudy-lms-learning-management-system'); ?></span>
            </div>

        </div>

    </div>

</div>