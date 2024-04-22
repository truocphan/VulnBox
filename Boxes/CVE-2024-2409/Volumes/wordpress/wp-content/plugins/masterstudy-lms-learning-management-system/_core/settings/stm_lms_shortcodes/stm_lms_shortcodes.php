<?php stm_lms_register_style('admin/shortcodes'); ?>
<div class="stm_lms_shortcode_list">
    <div>
        <label>Search box</label>
        <input type="text" disabled value='[stm_courses_searchbox]'>
        <ul class="params">
           <li>
               style
               <ul>
                   <li>style_1</li>
                   <li>style_2</li>
               </ul>
           </li>
        </ul>
    </div>
    <div>
        <label>Courses Carousel</label>
        <input type="text" disabled
               value='[stm_lms_courses_carousel]'/>
        <ul class="params">
            <li>
                title (enter the module title)
            </li>
            <li>
                title_color (change the color of the title, example #fafafa)
            </li>
            <li>
                query (sorting options — Sort by, set by default as "none")
                <ul>
                    <li>none</li>
                    <li>popular</li>
                    <li>free</li>
                    <li>rating</li>
                </ul>
            </li>
            <li>
                prev_next (enable or disable Previous/Next buttons, set by default as “enable”)
                <ul>
                    <li>enable</li>
                    <li>disable</li>
                </ul>
            </li>
            <li>
                remove_border (enable or disable border removing, set by default "disable")
                <ul>
                    <li>enable</li>
                    <li>disable</li>
                </ul>
            </li>
            <li>
                show_categories (enable/disable display of categories, set by default as "disable")
                <ul>
                    <li>enable</li>
                    <li>disable</li>
                </ul>
            </li>
            <li>
                pagination (disable or enable paginations, set by default as "disable")
                <ul>
                    <li>enable</li>
                    <li>disable</li>
                </ul>
            </li>
            <li>
                per_row (specify the number of courses per row, by default — 6)
            </li>
            <li>
                taxonomy (term ID of stm_lms_course_taxonomy taxonomy, only if show_categories is "enable". example "233,255,321")
            </li>
            <li>
                image_size (image size, (Ex.: thumbnail))
            </li>
        </ul>
    </div>
    <div>
        <label>Courses Categories</label>
        <input type="text" disabled
               value='[stm_lms_courses_categories]'/>
        <ul class="params">
            <li>
                style
                <ul>
                    <li>style_1</li>
                    <li>style_2</li>
                    <li>style_3</li>
                    <li>style_4</li>
                </ul>
            </li>
            <li>taxonomy (term ID of stm_lms_course_taxonomy taxonomy. example "233,255,321")</li>
        </ul>
    </div>
    <div>
        <label>Courses Grid</label>
        <input type="text" disabled
               value='[stm_lms_courses_grid]'/>
        <ul class="params">
            <li>
                hide_top_bar (hide/show the top bar, by default — "showing")
                <ul>
                    <li>hidden</li>
                    <li>showing</li>
                </ul>
            </li>
            <li>title (module title)</li>
            <li>
                hide_load_more (hide/show the button Load More, by default — "showing")
                <ul>
                    <li>hidden</li>
                    <li>showing</li>
                </ul>
            </li>
            <li>
                hide_sort (hide/show sorting option, by default — "showing")
                <ul>
                    <li>hidden</li>
                    <li>showing</li>
                </ul>
            </li>
            <li>
                per_row (the number of Courses Per Row, by default 6)
            </li>
            <li>
                image_size (image size, (Ex.: thumbnail))
            </li>
            <li>
                posts_per_page (number of courses to show on the page)
            </li>
        </ul>
    </div>
    <div>
        <label>Featured Teacher</label>
        <input type="text" disabled
               value='[stm_lms_featured_teacher]'/>
        <ul class="params">
            <li>
                instructor (Instructor ID)
            </li>
            <li>position (Instructor Position)</li>
            <li>
                bio (Instructor Bio)
            </li>
            <li>
                image (enter image ID)
            </li>
        </ul>
    </div>
    <div>
        <label>Instructors Carousel</label>
        <input type="text" disabled
               value='[stm_lms_instructors_carousel]'/>
        <ul class="params">
            <li>
                title (module title)
            </li>
            <li>title_color (changes the color of the title)</li>
            <li>
                per_row (number of Instructors per row, by default 6)
            </li>
            <li>
                per_row_md (number of Instructors per row on Notebook, by default 4)
            </li>
            <li>
                per_row_sm (number of Instructors per row on Tablet, by default 2)
            </li>
            <li>
                per_row_xs (number of Instructors per row on Mobile, by default 1)
            </li>
            <li>
                style (change the display style, by default "style_1")
                <ul>
                    <li>style_1</li>
                    <li>style_2</li>
                </ul>
            </li>
            <li>
                sort (the option Sort By)
                <ul>
                    <li>default</li>
                    <li>rating</li>
                </ul>
            </li>
            <li>
                prev_next (Enable or Disable Previous and Next Buttons, by default "enable")
                <ul>
                    <li>default</li>
                    <li>rating</li>
                </ul>
            </li>
            <li>
                pagination (Enable or Disable Previous and Next Buttons, by default "disable")
                <ul>
                    <li>default</li>
                    <li>rating</li>
                </ul>
            </li>
        </ul>
    </div>
    <div>
        <label>Recent Courses</label>
        <input type="text" disabled
               value='[stm_lms_recent_courses]'/>
        <ul class="params">
            <li>
                posts_per_page (Number of courses to show on the page)
            </li>
            <li>image_size (Image size (Ex.: thumbnail))</li>
            <li>
                per_row (the number of courses per row)
            </li>
            <li>
                style (Default "style_1")
                <ul>
                    <li>style_1</li>
                    <li>style_2</li>
                </ul>
            </li>
        </ul>
    </div>
    <div>
        <label>Single Course Carousel</label>
        <input type="text" disabled
               value='[stm_lms_single_course_carousel]'/>
        <ul class="params">
            <li>
                query (Sorting options “Sort by”, by default "none")
                <ul>
                    <li>none</li>
                    <li>popular</li>
                    <li>free</li>
                    <li>rating</li>
                </ul>
            </li>
            <li>
                prev_next (Enable or Disable Previous/Next Buttons, by default "enable")
                <ul>
                    <li>enable</li>
                    <li>disable</li>
                </ul>
            </li>
            <li>
                pagination (Enable or Disable pagination, by default "disable")
                <ul>
                    <li>enable</li>
                    <li>disable</li>
                </ul>
            </li>
            <li>
                taxonomy (term ID of stm_lms_course_taxonomy taxonomy, example "233,255,321")
            </li>
        </ul>
    </div>
    <div>
        <label>Certificate checker (pro version)</label>
        <input type="text" disabled
               value='[stm_lms_certificate_checker]'/>
        <ul class="params">
            <li>
                title (module title)
            </li>
        </ul>
    </div>
    <div>
        <label>Course Bundles (pro version)</label>
        <input type="text" disabled
               value='[stm_lms_course_bundles]'/>
        <ul class="params">
            <li>
                title (module title)
            </li>
            <li>
                columns (number of columns, by default 3)
                <ul>
                    <li>2</li>
                    <li>3</li>
                </ul>
            </li>
            <li>
                posts_per_page (number of posts per page)
            </li>
        </ul>
    </div>
    <div>
        <label>Google Classrooms grid view (pro version)</label>
        <input type="text" disabled
               value='[stm_lms_google_classroom]'/>
        <ul class="params">
            <li>
                title (module title)
            </li>
            <li>
                number (number of posts on the page)
            </li>
        </ul>
    </div>
</div>