<?php
defined('ABSPATH') || die();

/**
 * Adding WP List table class if it's not available.
 */
if (!class_exists(WP_List_Table::class)) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class HashFormEntryListing extends \WP_List_Table {

    private $table_data;
    private $status;

    public function __construct() {
        parent::__construct(
                array(
                    'singular' => 'Entry',
                    'plural' => 'Entries',
                    'ajax' => false,
                )
        );
        $this->status = HashFormHelper::get_var('status', 'sanitize_text_field', 'published');
    }

    public function no_items() {
        esc_html_e('No entries found.', 'hash-form');
    }

    public function column_default($item, $column_name) {
        switch ($column_name) {
            case 'cd':
            case 'id':
            case 'name':
            case 'form_id':
            case 'user_id':
            case 'delivery_status':
            case 'ip':
            case 'created_at':
            default:
                return $item[$column_name];
        }
    }

    public function get_columns() {
        return array(
            'cb' => '<input type="checkbox" />',
            'name' => esc_html__('ID', 'hash-form'),
            'form_id' => esc_html__('Form', 'hash-form'),
            'user_id' => esc_html__('Created By', 'hash-form'),
            'delivery_status' => esc_html__('Status', 'hash-form'),
            'ip' => esc_html__('IP', 'hash-form'),
            'created_at' => esc_html__('Created At', 'hash-form')
        );
    }

    public function column_cb($item) {
        return sprintf(
                '<input type="checkbox" name="%1$s_id[]" value="%2$s" />', esc_attr($this->_args['singular']), esc_attr($item['id'])
        );
    }

    public function prepare_items() {
        $this->table_data = $this->get_table_data();

        $columns = $this->get_columns();
        $sortable = $this->get_sortable_columns();
        $hidden = ( is_array(get_user_meta(get_current_user_id(), 'managetoplevel_page_hashform-entriescolumnshidden', true)) ) ? get_user_meta(get_current_user_id(), 'managetoplevel_page_hashform-entriescolumnshidden', true) : array();
        $primary = 'id';
        $this->_column_headers = array($columns, $hidden, $sortable, $primary);

        if ($this->table_data) {
            foreach ($this->table_data as $item) {
                $id = $item['id'];
                $data[$id] = array(
                    'id' => $item['id'],
                    'name' => $this->get_column_id($item),
                    'form_id' => $this->get_form_link($item['form_id']),
                    'user_id' => $this->get_user_link($item['user_id']),
                    'delivery_status' => $item['delivery_status'] ? esc_html__('Success', 'hash-form') : esc_html__('Failed', 'hash-form'),
                    'created_at' => HashFormHelper::convert_date_format($item['created_at']),
                    'ip' => $item['ip']
                );
            }

            usort($data, array(&$this, 'usort_reorder'));

            /* pagination */
            $per_page = $this->get_items_per_page('entries_per_page', 10);
            $current_page = $this->get_pagenum();
            $total_items = count($data);

            $data = array_slice($data, (($current_page - 1) * $per_page), $per_page);

            $this->set_pagination_args(array(
                'total_items' => $total_items,
                'per_page' => $per_page,
                'total_pages' => ceil($total_items / $per_page)
            ));

            $this->items = $data;
        }
    }

    public function get_column_id($item) {
        $entry_id = $item['id'];

        $edit_url = admin_url('admin.php?page=hashform-entries&hashform_action=view&id=' . $entry_id);

        $output = '<strong>';
        if ('trash' == $this->status) {
            $output .= esc_html($entry_id);
        } else {
            $output .= '<a class="row-title" href="' . esc_url($edit_url) . '" aria-label="' . sprintf(esc_html__('%s (Edit)', 'hash-form'), $entry_id) . '">' . esc_html($entry_id) . '</a>';
        }
        $output .= '</strong>';

        // Get actions.
        $actions = $this->get_action_links($item);
        $row_actions = array();

        foreach ($actions as $id => $action) {
            $row_actions[] = '<span class="' . esc_attr($id) . '"><a href="' . $action['url'] . '">' . $action['label'] . '</a></span>';
        }


        $output .= '<div class="row-actions">' . implode(' | ', $row_actions) . '</div>';

        return $output;
    }

    public function usort_reorder($a, $b) {
        // If no sort, default to user_login
        $orderby = HashFormHelper::get_var('orderby', 'sanitize_text_field', 'created_at');

        // If no order, default to asc
        $order = HashFormHelper::get_var('order', 'sanitize_text_field', 'DESC');

        // Determine sort order
        $result = strcmp($a[$orderby], $b[$orderby]);

        // Send final sort direction to usort
        return ($order === 'asc') ? $result : -$result;
    }

    private function get_table_data() {
        global $wpdb;
        $table = $wpdb->prefix . 'hashform_entries';
        $status = $this->status;

        if ($search = htmlspecialchars_decode(HashFormHelper::get_var('s'))) {
            $query = $wpdb->prepare("SELECT * from {$table} WHERE status=%s AND form_id Like %s", $status, '%' . $wpdb->esc_like($search) . '%');
            return $wpdb->get_results($query, ARRAY_A);
        } else if ($form_id = HashFormHelper::get_var('form_id', 'absint')) {
            $query = $wpdb->prepare("SELECT * from {$table} WHERE status=%s AND form_id=%d", $status, $form_id);
            return $wpdb->get_results($query, ARRAY_A);
        } else {
            $query = $wpdb->prepare("SELECT * from {$table} WHERE status=%s", $status);
            return $wpdb->get_results($query, ARRAY_A);
        }
    }

    public function get_bulk_actions() {
        if ($this->status == 'published') {
            return array(
                'bulk_trash' => esc_html__('Move to Trash', 'hash-form'),
            );
        } else {
            return array(
                'bulk_untrash' => esc_html__('Restore', 'hash-form'),
                'bulk_delete' => esc_html__('Delete Permanently', 'hash-form')
            );
        }
    }

    protected function display_tablenav($which) {
        ?>
        <div class="tablenav <?php echo esc_attr($which); ?>">
            <?php if ($this->has_items()) { ?>
                <div class="alignleft actions bulkactions">
                    <?php $this->bulk_actions($which); ?>
                </div>
                <?php
            }

            $this->extra_tablenav($which);

            $this->pagination($which);
            ?>
            <br class="clear" />
        </div>
        <?php
    }

    public function extra_tablenav($which) {
        if ($this->has_items()) {
            if ('trash' == $this->status) {
                ?>
                <div class="alignleft actions"><?php submit_button(esc_html__('Empty Trash', 'hash-form'), 'apply', 'delete_all', false); ?></div>
                <?php
            }
        }

        if ($which === 'top') {
            $form_id = HashFormHelper::get_var('form_id', 'absint', 0);
            ?>
            <div class="alignleft actions">
                <?php
                self::forms_dropdown('form_id', $form_id);
                submit_button(esc_html__('Filter', 'hash-form'), 'filter_action', '', false, array('id' => 'post-query-submit'));
                ?>
            </div>
            <?php
        }
    }

    public static function forms_dropdown($field_name, $field_value = '') {
        $forms = HashFormBuilder::get_all_forms();
        ?>
        <select name="<?php echo esc_attr($field_name); ?>">
            <option value=""><?php echo esc_html__('All', 'hash-form'); ?></option>
            <?php foreach ($forms as $form) { ?>
                <option value="<?php echo esc_attr($form->id); ?>" <?php selected($field_value, $form->id); ?>>
                    <?php echo ('' === $form->name ? esc_html__('(no title)', 'hash-form') : esc_html($form->name)); ?>
                </option>
            <?php } ?>
        </select>
        <?php
    }

    public function get_sortable_columns() {
        return array(
            'id' => array('id', false),
            'form_id' => array('form_id', false),
            'user_id' => array('user_id', false),
            'status' => array('status', false),
            'created_at' => array('created_at', false),
            'delivery_status' => array('delivery_status', false),
            'ip' => array('ip', false)
        );
    }

    public function get_action_links($item) {
        $entry_id = $item['id'];
        $actions = array();
        $trash_links = self::delete_trash_links($entry_id);
        if ('trash' == $this->status) {
            $actions['restore'] = $trash_links['restore'];
            $actions['delete'] = $trash_links['delete'];
        } else {
            $actions['view'] = array(
                'label' => esc_html__('View', 'hash-form'),
                'url' => admin_url('admin.php?page=hashform-entries&hashform_action=view&id=' . $entry_id)
            );
            $actions['trash'] = $trash_links['trash'];
        }
        return $actions;
    }

    private static function delete_trash_links($id) {
        $base_url = '?page=hashform-entries&id=' . $id;
        return array(
            'restore' => array(
                'label' => esc_html__('Restore', 'hash-form'),
                'url' => wp_nonce_url($base_url . '&hashform_action=untrash', 'untrash_entry_' . absint($id)),
            ),
            'delete' => array(
                'label' => esc_html__('Delete Permanently', 'hash-form'),
                'url' => wp_nonce_url($base_url . '&hashform_action=destroy', 'destroy_entry_' . absint($id)),
            ),
            'trash' => array(
                'label' => esc_html__('Trash', 'hash-form'),
                'url' => wp_nonce_url($base_url . '&hashform_action=trash', 'trash_entry_' . absint($id)),
            )
        );
    }

    public function get_views() {
        $statuses = array(
            'published' => esc_html__('All', 'hash-form'),
            'trash' => esc_html__('Trash', 'hash-form'),
        );

        $links = array();

        $counts = HashFormEntry::get_count();

        foreach ($statuses as $status => $name) {
            $class = ($status == $this->status) ? ' class="current"' : '';
            if ($counts[$status]) {
                $links[$status] = '<a href="' . esc_url('?page=hashform-entries&status=' . $status) . '" ' . $class . '>' . sprintf(__('%1$s <span class="count">(%2$s)</span>', 'hash-form'), $name, number_format_i18n($counts[$status])) . '</a>';
            }
        }
        return $links;
    }

    public function views() {
        $views = $this->get_views();
        if (empty($views))
            return;
        echo "<ul class='subsubsub'>\n";
        foreach ($views as $class => $view) {
            $views[$class] = "\t" . '<li class="' . esc_attr($class) . '">' . wp_kses_post($view);
        }
        echo wp_kses_post(implode(" |</li>\n", $views) . "</li>\n");
        echo '</ul>';
    }

    private function get_form_link($form_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'hashform_forms';
        $query = $wpdb->prepare("SELECT name from {$table} WHERE id=%d", $form_id);
        $form_name = $wpdb->get_row($query, ARRAY_A);
        return '<a href="' . esc_url(admin_url('admin.php?page=hashform&hashform_action=edit&id=' . $form_id)) . '">' . esc_html($form_name['name']) . '</a>';
    }

    private function get_user_link($user_id) {
        if ($user_id) {
            $user_obj = get_user_by('id', $user_id);
            return '<a data-id="' . esc_attr($user_id) . '" href="' . get_edit_user_link($user_id) . '">' . esc_html($user_obj->display_name) . '</a>';
        } else {
            return esc_html('Guest', 'hash-form');
        }
    }

}
