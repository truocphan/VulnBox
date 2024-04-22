<div class="stm-lms-payments">

    <div class="stm-lms-payment_method" v-for="(payment_info, payment) in payments">

        <label class="wpcfto_checkbox">
            <input type="checkbox" v-model="payment_info.enabled"/>
            <i class="fa fa-check"></i>
            {{ payment_info.name }}
        </label>

        <transition name="slide-fade">
            <div class="stm-lms-payment_info" v-if="payment_info.enabled">
                <div class="stm-lms-payment_info_field" v-for="(field_info, field_name) in payment_info.fields">

                    <textarea v-if="field_info['type'] == 'textarea'"
                              v-bind:placeholder="field_info['placeholder']"
                              v-model="payments[payment].fields[field_name].value">
                    </textarea>

                    <input type="text" v-if="field_info['type'] == 'text'"
                           v-bind:placeholder="field_info['placeholder']"
                           v-model="payment_info.fields[field_name].value">
                    </input>

                    <select v-if="field_info['type'] == 'select'"
                            v-model="payment_info.fields[field_name].value">
                        <option v-for="(option_value, option_name) in sources[field_info['source']]" v-bind:value="option_value">
                            {{ option_name }}
                        </option>
                    </select>

                </div>
            </div>
        </transition>

    </div>
</div>