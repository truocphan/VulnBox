<div class="curriculum-search">

    <div class="curriculum-search_popup">

        <div class="curriculum-search_head">

            <div class="curriculum-search_title">
                <?php esc_html_e('Adding questions to quiz', 'masterstudy-lms-learning-management-system'); ?>
            </div>

            <div class="curriculum-search_input">

                <input v-model="search"
                       ref="curriculum_search"
                       @keydown.enter.prevent="searchItems"
                       @keydown="searchingItems"
                       placeholder="<?php esc_attr_e('Search items', 'masterstudy-lms-learning-management-system'); ?>"/>

                <div class="add_item_submit" @click="searchItems">
                    <?php STM_LMS_Helpers::print_svg('settings/curriculum/images/search.svg'); ?>
                </div>

            </div>

        </div>

        <div class="curriculum-search_list" v-bind:class="{'loading_v2' : loading}">

            <div class="section_items" v-if="searchList.length">
                <div class="items">

                    <!--Search items-->
                    <div class="item"
                         v-for="(item, item_key) in searchList"
                         @mouseover="$set(item, 'class', 'hovered')"
                         @mouseout="item.class = ''"
                         @click="selectItem(item, item_key)"
                         v-bind:class="{'selected' : item.selected, 'hovered' : item.class === 'hovered'}">

                        <?php stm_lms_curriculum_v2_load_template('search_item'); ?>

                    </div>
                </div>
            </div>
        </div>

        <div class="curriculum-search_submit">
            <span @click="addItems">
                {{countSelected('<?php esc_html_e('Add {x} selected questions.', 'masterstudy-lms-learning-management-system') ?>')}}
            </span>
        </div>

    </div>

    <div class="curriculum-search_popup_close" @click="$emit('close_popup');"></div>


</div>