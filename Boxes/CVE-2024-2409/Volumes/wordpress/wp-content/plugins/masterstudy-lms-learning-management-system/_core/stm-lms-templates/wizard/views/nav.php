<div class="stm_lms_splash_wizard__nav">
    <div class="stm_lms_splash_wizard__nav_item"
        v-bind:class="{'active' : step_key === active_step}"
        @click="changeStep(step_key)"
        v-for="(step, step_key, index) in steps"
    >
        <div class="stm_lms_splash_wizard__nav_item_count">
            <span>{{index + 1}}</span>
        </div>
        <div class="stm_lms_splash_wizard__nav_item_title">
            {{step}}
        </div>
    </div>
</div>