<?php
defined('ABSPATH') || die();

class HashFormEntry {

    public function __construct() {
        add_action('admin_menu', array($this, 'add_menu'), 10);
        add_filter('set-screen-option', array($this, 'set_screen_option'), 15, 3);

        add_action('wp_ajax_hashform_process_entry', array($this, 'process_entry'));
        add_action('wp_ajax_nopriv_hashform_process_entry', array($this, 'process_entry'));
    }

    public function add_menu() {
        global $hash_entry_listing_page;
        $hash_entry_listing_page = add_submenu_page('hashform', esc_html__('Entries', 'hash-form'), esc_html__('Entries', 'hash-form'), 'manage_options', 'hashform-entries', array($this, 'route'));
        add_action("load-$hash_entry_listing_page", array($this, 'listing_page_screen_options'));
    }

    public static function route() {
        $action = htmlspecialchars_decode(HashFormHelper::get_var('hashform_action', 'sanitize_text_field', HashFormHelper::get_var('action')));

        if (HashFormHelper::get_var('delete_all')) {
            $action = 'delete_all';
        }

        switch ($action) {
            case 'view':
            case 'destroy':
            case 'untrash':
            case 'trash':
            case 'delete_all':
                return self::$action();
            default:

                if (strpos($action, 'bulk_') === 0) {
                    self::bulk_actions();
                    return;
                }

                self::display_entry_list();

                return;
        }
    }

    public static function view($id = 0) {
        if (!$id) {
            $id = HashFormHelper::get_var('id', 'absint');
        }
        $entry = self::get_entry_vars($id);

        if (!$entry) {
            ?>
            <div id="message" class="error notice is-dismissible">
                <p><?php esc_html_e('You are trying to view an entry that does not exist.', 'hash-form'); ?></p>
            </div>
            <?php
            return;
        }

        include( HASHFORM_PATH . 'admin/entries/entry-detail.php' );
    }

    public static function display_message($message, $class) {
        if ('' !== $message) {
            echo '<div id="message" class="' . esc_attr($class) . ' notice is-dismissible">';
            echo '<p>' . wp_kses_post($message) . '</p>';
            echo '</div>';
        }
    }

    public static function display_entry_list($message = '', $class = 'updated') {
        ?>
        <div class="hf-content">
            <div class="hf-entry-list-wrap wrap">
                <h1></h1>
                <div id="hf-entry-list">
                    <?php
                    self::display_message($message, $class);
                    $entry_table = new HashFormEntryListing();
                    $entry_status = HashFormHelper::get_var('status', 'sanitize_title', 'published');
                    $entry_table->views();
                    ?>
                    <form id="posts-filter" method="get">
                        <input type="hidden" name="page" value="<?php echo esc_attr(HashFormHelper::get_var('page', 'sanitize_title')); ?>" />
                        <input type="hidden" name="status" value="<?php echo esc_attr($entry_status); ?>" />
                        <?php
                        $entry_table->prepare_items();
                        $entry_table->search_box('Search', 'search');
                        $entry_table->display();
                        ?>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }

    public function listing_page_screen_options() {

        global $hash_entry_listing_page;

        $screen = get_current_screen();
        $hashform_action = HashFormHelper::get_var('hashform_action');

        // get out of here if we are not on our settings page
        if (!is_object($screen) || $screen->id != $hash_entry_listing_page || ($hashform_action == 'view'))
            return;

        $args = array(
            'label' => esc_html__('Entries per page', 'hash-form'),
            'default' => 10,
            'option' => 'entries_per_page'
        );

        add_screen_option('per_page', $args);

        //new HashFormEntryListing();
    }

    public function set_screen_option($status, $option, $value) {
        if ('entries_per_page' == $option)
            return $value;
    }

    public static function trash() {
        self::change_form_status('trash');
    }

    public static function untrash() {
        self::change_form_status('untrash');
    }

    public static function change_form_status($status) {
        $available_status = array(
            'untrash' => array('new_status' => 'published'),
            'trash' => array('new_status' => 'trash'),
        );

        if (!isset($available_status[$status])) {
            return;
        }

        $id = HashFormHelper::get_var('id', 'absint');

        check_admin_referer($status . '_entry_' . $id);

        $count = 0;
        if (self::set_status($id, $available_status[$status]['new_status'])) {
            $count ++;
        }

        $available_status['untrash']['message'] = sprintf(_n('%1$s form restored from the Trash.', '%1$s forms restored from the Trash.', $count, 'hash-form'), $count);
        $available_status['trash']['message'] = sprintf(_n('%1$s form moved to the Trash. %2$sUndo%3$s', '%1$s forms moved to the Trash. %2$sUndo%3$s', $count, 'hash-form'), $count, '<a href="' . esc_url(wp_nonce_url('?page=hashform-entries&hashform_action=untrash&id=' . $id, 'untrash_entry_' . $id)) . '">', '</a>');
        $message = $available_status[$status]['message'];

        self::display_entry_list($message);
    }

    public static function set_status($id, $status) {
        $statuses = array('published', 'trash');
        if (!in_array($status, $statuses))
            return false;

        global $wpdb;

        if (is_array($id)) {
            $query = $wpdb->prepare("UPDATE {$wpdb->prefix}hashform_entries SET status=%s WHERE id IN (" . implode(',', array_fill(0, count($id), '%d')) . ")", $status, ...$id);
            $query_results = $wpdb->query($query);
        } else {
            $query_results = $wpdb->update($wpdb->prefix . 'hashform_entries', array('status' => $status), array('id' => $id));
        }

        return $query_results;
    }

    public static function delete_all() {
        $count = self::delete();
        $message = sprintf(_n('%1$s form permanently deleted.', '%1$s forms permanently deleted.', $count, 'hash-form'), $count);
        self::display_entry_list($message);
    }

    public static function delete() {
        global $wpdb;
        $query = $wpdb->prepare("SELECT id FROM {$wpdb->prefix}hashform_entries WHERE status=%s", 'trash');
        $trash_entries = $wpdb->get_results($query);
        if (!$trash_entries) {
            return 0;
        }
        $count = 0;
        foreach ($trash_entries as $entry) {
            self::destroy_entry($entry->id);
            $count ++;
        }
        return $count;
    }

    public static function destroy() {
        $id = HashFormHelper::get_var('id', 'absint');
        check_admin_referer('destroy_entry_' . $id);
        $count = 0;
        if (self::destroy_entry($id)) {
            $count ++;
        }
        $message = sprintf(_n('%1$s Entry Permanently Deleted', '%1$s Entries Permanently Deleted', $count, 'hash-form'), $count);
        self::display_entry_list($message);
    }

    public static function destroy_entry($id) {
        global $wpdb;
        $entry = self::get_entry_vars($id); // Item meta is required for conditional logic in actions with 'delete' events.
        if (!$entry) {
            return false;
        }

        $query = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'hashform_entry_meta WHERE item_id=%d', $id);
        $wpdb->query($query);

        $query = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'hashform_entries WHERE id=%d', $id);
        $result = $wpdb->query($query);
        return $result;
    }

    public static function bulk_actions() {
        $message = self::process_bulk_actions();
        self::display_entry_list($message);
    }

    public static function process_bulk_actions() {
        if (!$_REQUEST)
            return;

        $bulkaction = HashFormHelper::get_var('action', 'sanitize_text_field');


        if ($bulkaction == - 1) {
            $bulkaction = HashFormHelper::get_var('action2', 'sanitize_title');
        }

        if (!empty($bulkaction) && strpos($bulkaction, 'bulk_') === 0) {
            $bulkaction = str_replace('bulk_', '', $bulkaction);
        }

        $ids = HashFormHelper::get_var('entry_id', 'sanitize_text_field');

        if (empty($ids)) {
            $error = esc_html__('No Entries were specified', 'hash-form');
            return $error;
        }

        if (!is_array($ids)) {
            $ids = explode(',', $ids);
        }

        switch ($bulkaction) {
            case 'delete':
                $message = self::bulk_destroy($ids);
                break;
            case 'trash':
                $message = self::bulk_trash($ids);
                break;
            case 'untrash':
                $message = self::bulk_untrash($ids);
        }

        if (isset($message) && !empty($message)) {
            return $message;
        }
    }

    public static function bulk_trash($ids) {
        $count = self::set_status($ids, 'trash');
        if (!$count) {
            return '';
        }
        return sprintf(_n('%1$s form moved to the Trash. %2$sUndo%3$s', '%1$s forms moved to the Trash. %2$sUndo%3$s', $count, 'hash-form'), $count, '<a href="' . esc_url(wp_nonce_url('?page=hashform-entries&action=bulk_untrash&status=published&entry_id=' . implode(',', $ids), 'bulk-toplevel_page_hashform')) . '">', '</a>');
    }

    public static function bulk_untrash($ids) {
        $count = self::set_status($ids, 'published');
        if (!$count) {
            return '';
        }
        return sprintf(_n('%1$s form restored from the Trash.', '%1$s forms restored from the Trash.', $count, 'hash-form'), $count);
    }

    public static function bulk_destroy($ids) {
        $count = 0;
        foreach ($ids as $id) {
            $entry = self::destroy_entry($id);
            if ($entry) {
                $count ++;
            }
        }
        $message = sprintf(_n('%1$s form permanently deleted.', '%1$s forms permanently deleted.', $count, 'hash-form'), $count);
        return $message;
    }

    public static function get_entry_vars($id) {
        global $wpdb;
        $query = "SELECT e.*, f.name AS form_name, f.form_key AS form_key
        FROM {$wpdb->prefix}hashform_entries AS e
        LEFT OUTER JOIN {$wpdb->prefix}hashform_forms AS f ON e.form_id = f.id
        WHERE e.id = %d";

        $query = $wpdb->prepare($query, $id); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
        $entry = $wpdb->get_row($query); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

        $entry = self::get_meta($entry);
        return $entry;
    }

    public static function get_meta($entry) {
        if (!$entry) {
            return $entry;
        }

        global $wpdb;
        $query = "SELECT m.*, f.type AS field_type, f.field_key, f.name ";
        $query .= "FROM {$wpdb->prefix}hashform_entry_meta AS m ";
        $query .= "LEFT JOIN {$wpdb->prefix}hashform_fields AS f ON m.field_id = f.id ";
        $query .= "WHERE m.item_id = %d AND m.field_id != %d ";
        $query .= "ORDER BY m.id ASC";

        $query = $wpdb->prepare($query, $entry->id, 0);

        $metas = $wpdb->get_results($query);

        $entry->metas = array();

        foreach ($metas as $meta_val) {
            if ($meta_val->item_id == $entry->id) {
                $entry->metas[$meta_val->field_id] = array(
                    'name' => $meta_val->name,
                    'value' => $meta_val->meta_value,
                    'type' => $meta_val->field_type
                );
                continue;
            }

            // include sub entries in an array
            if (!isset($entry->metas[$meta_val->field_id])) {
                $entry->metas[$meta_val->field_id] = array();
            }

            $entry->metas[$meta_val->field_id][] = $meta_val->meta_value;
        }

        return $entry;
    }

    public function process_entry() {
        global $wpdb;
        parse_str(htmlspecialchars_decode(HashFormHelper::get_post('data', 'esc_html')), $data);

        if (empty($data) || empty($data['form_id']) || !isset($data['form_key'])) {
            return;
        }

        $form_id = $data['form_id'];
        $form = HashFormBuilder::get_form_vars($form_id);

        if (!$form) {
            return;
        }
        $errors = '';
        $errors = HashFormValidate::validate(wp_unslash($data));

        if (empty($errors)) {
            $form_settings = $form->settings;
            $entry_id = self::create($data);

            $send_mail = new HashFormEmail($form, $entry_id);
            $check_mail = $send_mail->send_email();

            if (!$check_mail) {
                $wpdb->update($wpdb->prefix . 'hashform_entries', array('delivery_status' => false), array('id' => $entry_id));
                return wp_send_json(array(
                    'status' => 'failed',
                    'message' => esc_html($form_settings['error_message'])
                ));
            }
        }

        return wp_send_json(array(
            'status' => 'error',
            'message' => $errors
        ));
    }

    public static function create($values) {
        global $wpdb;
        $current_user_id = get_current_user_id();
        $user_id = $current_user_id ? $current_user_id : 0;
        $new_values = array(
            'ip' => sanitize_text_field(HashFormHelper::get_ip()),
            'delivery_status' => 1,
            'form_id' => isset($values['form_id']) ? absint($values['form_id']) : '',
            'created_at' => sanitize_text_field(current_time('mysql')),
            'user_id' => absint($user_id),
            'status' => 'published'
        );

        $query_results = $wpdb->insert($wpdb->prefix . 'hashform_entries', $new_values);
        if (!$query_results) {
            return false;
        } else {
            $entry_id = $wpdb->insert_id;
        }

        if (isset($values['item_meta'])) {
            foreach ($values['item_meta'] as $field_id => $meta_value) {
                if (!empty($meta_value)) {
                    if (is_array($meta_value)) {
                        $meta_value = serialize($meta_value);
                    } else {
                        $meta_value = sanitize_text_field(trim($meta_value));
                    }

                    $meta_values = array(
                        'meta_value' => $meta_value,
                        'item_id' => absint($entry_id),
                        'field_id' => absint($field_id),
                        'created_at' => sanitize_text_field(current_time('mysql')),
                    );

                    self::sanitize_meta_value($meta_values);

                    $query_results = $wpdb->insert($wpdb->prefix . 'hashform_entry_meta', $meta_values);
                }
            }
        }
        return $entry_id;
    }

    private static function sanitize_meta_value(&$values) {
        $field = HashFormFields::get_field_vars($values['field_id']);
        if ($field) {
            $field_obj = HashFormFields::get_field_object($field);
            $values['meta_value'] = $field_obj->set_value_before_save($values['meta_value']);
            $values['meta_value'] = $field_obj->sanitize_value($values['meta_value']);
        }
    }

    public static function get_count() {
        global $wpdb;
        $query = $wpdb->prepare("SELECT status FROM {$wpdb->prefix}hashform_entries WHERE id!=%d", 0);
        $results = $wpdb->get_results($query);
        $statuses = array('published', 'trash');
        $counts = array_fill_keys($statuses, 0);
        foreach ($results as $row) {
            if ('published' == $row->status) {
                $counts['published'] ++;
            } else {
                $counts['trash'] ++;
            }
        }
        return $counts;
    }

    public static function get_entry_count($form_id) {
        global $wpdb;
        $query = $wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}hashform_entries e LEFT OUTER JOIN {$wpdb->prefix}hashform_forms f ON e.form_id=f.id WHERE e.form_id=%d AND e.status='published'", $form_id);
        $count = $wpdb->get_var($query);
        return $count;
    }

}

new HashFormEntry();
