<?php
stm_lms_register_style( 'dashboard/dashboard' );
stm_lms_register_script( 'admin/manage_users', array('vue.js', 'vue-resource.js'), true );
?>
<div id="stm_lms_user_manage" class="lms-dashboard-table">
    <div class="loading" v-if="loading"></div>
    <table>
        <thead>
        <tr>
            <th><?php esc_html_e( 'User ID', 'masterstudy-lms-learning-management-system' ); ?></th>
            <th><?php esc_html_e( 'Username', 'masterstudy-lms-learning-management-system' ); ?></th>
            <th><?php esc_html_e( 'User email', 'masterstudy-lms-learning-management-system' ); ?></th>
            <th width="20%"><?php esc_html_e( 'User Info', 'masterstudy-lms-learning-management-system' ); ?></th>
            <th @click="direction = direction === 'ASC' ? 'DESC' : 'ASC'" style="cursor: pointer;">
                <i class="fa fa-long-arrow-alt-up"></i>
                <i class="fa fa-long-arrow-alt-down"></i>
                <?php esc_html_e( 'Submission Date', 'masterstudy-lms-learning-management-system' ); ?>
            </th>
            <th><?php esc_html_e( 'Actions', 'masterstudy-lms-learning-management-system' ); ?></th>
            <th><?php esc_html_e( 'History', 'masterstudy-lms-learning-management-system' ); ?></th>
            <th><?php esc_html_e( 'Ban', 'masterstudy-lms-learning-management-system' ); ?></th>
        </tr>
        </thead>
        <tbody>
            <tr v-for="(user, key) in computedUsers">
                <td v-html="'#' + user.id"></td>
                <td><a :href="user.edit_link" v-html="user.display_name"></a></td>
                <td v-html="user.user_email"></td>
                <td v-if="typeof user.custom_fields !== 'undefined' && user.custom_fields.length">
                    <span v-for="field in user.custom_fields">
                        <span v-if="field.label" v-html="field.label"></span>
                        <span v-else-if="field.slug" v-html="field.slug"></span>
                        <span v-else="field.field_name" v-html="field.field_name"></span>
                         -
                        <span v-html="field.value + '; '"></span>
                    </span>
                </td>
                <td v-else>
                    <?php esc_html_e( 'Degree - ', 'masterstudy-lms-learning-management-system' ); ?>{{user.degree}},
                    <?php esc_html_e( 'Expertize - ', 'masterstudy-lms-learning-management-system' ); ?>{{user.expertize}}
                </td>
                <td v-html="user.submission_date"></td>
                <td v-if="user.status === 'pending'">
                    <textarea v-model="user.message" placeholder="<?php esc_attr_e( 'Message', 'masterstudy-lms-learning-management-system' ); ?>"></textarea>
                    <button class="button-primary" @click="updateUserStatus(user.id, key, 'approved')"><?php esc_html_e( 'Approve', 'masterstudy-lms-learning-management-system' ); ?></button>
                    <button class="button-secondary" @click="updateUserStatus(user.id, key, 'rejected')"><?php esc_html_e( 'Decline', 'masterstudy-lms-learning-management-system' ); ?></button>
                </td>
                <td v-else v-html="user.status" :class="'status ' + user.status"></td>
                <td>
                    <a href="#" v-if="user.submission_history.length > 0" @click.prevent="showHistory(key)"><?php esc_html_e( 'History', 'masterstudy-lms-learning-management-system' ); ?></a>
                </td>
                <td>
                    <input type="checkbox" v-model="user.banned" @change="banUser(user.id, user.banned)" />
                </td>
            </tr>
        </tbody>
    </table>
    <div class="history_modal" v-if="historyModal.status">
        <div class="history_overlay" @click="historyModal.status = false"></div>
        <div class="history_close" @click="historyModal.status = false">
            <i class="fas fa-times"></i>
        </div>
        <div class="history_modal_body">
            <div class="lms-dashboard-table">
                <table>
                    <thead>
                        <th><?php esc_html_e( 'Submission date', 'masterstudy-lms-learning-management-system' ); ?></th>
                        <th><?php esc_html_e( 'Status', 'masterstudy-lms-learning-management-system' ); ?></th>
                        <th><?php esc_html_e( 'Message', 'masterstudy-lms-learning-management-system' ); ?></th>
                        <th><?php esc_html_e( 'Answer date', 'masterstudy-lms-learning-management-system' ); ?></th>
                    </thead>
                    <tbody>
                        <tr v-for="data in historyModal.history">
                            <td v-html="data.request_display_date"></td>
                            <td :class="data.status + ' status'" v-html="data.status"></td>
                            <td v-html="data.message"></td>
                            <td v-html="data.answer_display_date"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="users-pagination" v-if="total > 1">
        <ul>
            <li v-for="page in total">
                <a href="#" v-if="currentPage !== page" v-html="page" @click.prevent="changePage(page)"></a>
                <span v-else v-html="page"></span>
            </li>
        </ul>
    </div>
</div>