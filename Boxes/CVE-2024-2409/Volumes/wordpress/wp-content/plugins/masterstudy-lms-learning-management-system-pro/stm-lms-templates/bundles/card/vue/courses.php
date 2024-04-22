<div class="stm_lms_single_bundle_card__courses">

    <a class="stm_lms_single_bundle_card__course"
       v-bind:href="courses[course].link"
       v-for="course in bundle.courses"
       v-if="typeof courses[course] !== 'undefined'">
        <div class="stm_lms_single_bundle_card__course_image" v-html="courses[course].image"></div>

        <div class="stm_lms_single_bundle_card__course_data heading_font">
            <div class="stm_lms_single_bundle_card__course_title" v-html="courses[course].title"></div>
            <div class="stm_lms_single_bundle_card__course_price"
                 v-html="courses[course].price"
                 v-if="courses[course].simple_price"></div>
        </div>

    </a>

</div>