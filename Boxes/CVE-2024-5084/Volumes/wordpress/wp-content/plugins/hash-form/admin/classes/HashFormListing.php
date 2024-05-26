<?php
defined('ABSPATH') || die();

/**
 * Adding WP List table class if it's not available.
 */
if (!class_exists(WP_List_Table::class)) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class HashFormListing extends \WP_List_Table {

    private $table_data;
    private $status;

    public function __construct() {
        parent::__construct(
                array(
                    'singular' => 'Form',
                    'plural' => 'Forms',
                    'ajax' => false,
                )
        );
        $this->status = htmlspecialchars_decode(HashFormHelper::get_var('status', 'sanitize_text_field', 'published'));
    }

    public function no_items() {
        esc_html_e('No forms found. Please create a new one.', 'hash-form');
    }

    public function column_default($item, $column_name) {
        switch ($column_name) {
            case 'cd':
            case 'name':
            case 'entries':
            case 'id':
            case 'shortcode':
            case 'created_at':
            default:
                return $item[$column_name];
        }
    }

    public function get_columns() {
        return array(
            'cb' => '<input type="checkbox" />',
            'name' => esc_html__('Form Title', 'hash-form'),
            'entries' => esc_html__('Entries', 'hash-form'),
            'id' => 'ID',
            'shortcode' => esc_html__('Shortcode', 'hash-form'),
            'created_at' => esc_html__('Date', 'hash-form')
        );
    }

    public function column_title($item) {
        $form_name = $item['name'];
        $form_id = $item['id'];
        if (trim($form_name) == '') {
            $form_name = esc_html__('(no title)', 'hash-form');
        }
        $edit_url = admin_url('admin.php?page=hashform&hashform_action=edit&id=' . absint($form_id));

        $output = '<strong>';
        if ('trash' == $this->status) {
            $output .= esc_html($form_name);
        } else {
            $output .= '<a class="row-title" href="' . esc_url($edit_url) . '" aria-label="' . sprintf(esc_html__('%s (Edit)', 'hash-form'), $form_name) . '">' . esc_html($form_name) . '</a>';
        }
        $output .= '</strong>';

        // Get actions.
        $actions = $this->get_action_links($item);
        $row_actions = array();

        foreach ($actions as $id => $action) {
            $row_actions[] = '<span class="' . esc_attr($id) . '"><a href="' . $action['url'] . '">' . $action['label'] . '</a></span>';
        }

        $output .= '<div class="row-desc">' . $item['description'] . '</div>';

        $output .= '<div class="row-actions">' . implode(' | ', $row_actions) . '</div>';

        return $output;
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
        $hidden = ( is_array(get_user_meta(get_current_user_id(), 'managetoplevel_page_hashformcolumnshidden', true)) ) ? get_user_meta(get_current_user_id(), 'managetoplevel_page_hashformcolumnshidden', true) : array();
        $primary = 'id';
        $this->_column_headers = array($columns, $hidden, $sortable, $primary);

        if ($this->table_data) {
            foreach ($this->table_data as $item) {
                $id = $item['id'];
                $data[$id] = array(
                    'name' => $this->column_title($item),
                    'entries' => $this->get_entry_link($id),
                    'id' => $id,
                    'form_key' => $item['form_key'],
                    'shortcode' => '[hashform id="' . $id . '"]',
                    'created_at' => HashFormHelper::convert_date_format($item['created_at'])
                );
            }

            usort($data, array(&$this, 'usort_reorder'));

            /* pagination */
            $per_page = $this->get_items_per_page('forms_per_page', 10);
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

    private function usort_reorder($a, $b) {
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
        $table = $wpdb->prefix . 'hashform_forms';
        $status = $this->status;
        $search = htmlspecialchars_decode(HashFormHelper::get_var('s'));

        if ($search) {
            $query = $wpdb->prepare("SELECT * from {$table} WHERE status=%s AND name Like %s", $status, '%' . $wpdb->esc_like($search) . '%');
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
                $this->extra_tablenav($which);
            }

            $this->pagination($which);
            ?>
            <br class="clear" />
        </div>
        <?php
    }

    public function extra_tablenav($which) {
        if ('trash' == $this->status) {
            ?>
            <div class="alignleft actions"><?php submit_button(esc_html__('Empty Trash', 'hash-form'), 'apply', 'delete_all', false); ?></div>
            <?php
        }
    }

    public function get_sortable_columns() {
        return array(
            'name' => array('name', false),
            'id' => array('id', false),
            'entries' => array('form_key', false),
            'created_at' => array('created_at', false),
        );
    }

    public function get_action_links($item) {
        $form_id = $item['id'];
        $actions = array();
        $trash_links = $this->delete_trash_links($form_id);
        if ('trash' == $this->status) {
            $actions['restore'] = $trash_links['restore'];
            $actions['delete'] = $trash_links['delete'];
        } else {
            $actions['duplicate'] = array(
                'label' => esc_html__('Duplicate', 'hash-form'),
                'url' => wp_nonce_url('?page=hashform&hashform_action=duplicate&id=' . $form_id)
            );
            $actions['edit'] = array(
                'label' => esc_html__('Edit', 'hash-form'),
                'url' => admin_url('admin.php?page=hashform&hashform_action=edit&id=' . $form_id)
            );
            $actions['view'] = array(
                'label' => esc_html__('Preview', 'hash-form'),
                'url' => admin_url('admin-ajax.php?action=hashform_preview&form=' . $form_id)
            );
            $actions['trash'] = $trash_links['trash'];
        }
        return $actions;
    }

    private function delete_trash_links($id) {
        $base_url = '?page=hashform&id=' . $id;
        return array(
            'restore' => array(
                'label' => esc_html__('Restore', 'hash-form'),
                'url' => wp_nonce_url($base_url . '&hashform_action=untrash', 'untrash_form_' . absint($id)),
            ),
            'delete' => array(
                'label' => esc_html__('Delete Permanently', 'hash-form'),
                'url' => wp_nonce_url($base_url . '&hashform_action=destroy', 'destroy_form_' . absint($id)),
            ),
            'trash' => array(
                'label' => esc_html__('Trash', 'hash-form'),
                'url' => wp_nonce_url($base_url . '&hashform_action=trash', 'trash_form_' . absint($id)),
            )
        );
    }

    public function get_entry_link($id) {
        $count = HashFormEntry::get_entry_count($id);
        return '<a href="' . esc_url(admin_url('admin.php?page=hashform-entries&form_id=' . $id)) . '">' . $count . '</a>';
    }

    public function get_views() {
        $statuses = array(
            'published' => esc_html__('All', 'hash-form'),
            'trash' => esc_html__('Trash', 'hash-form'),
        );

        $links = array();

        $counts = self::get_count();

        foreach ($statuses as $status => $name) {
            $class = ($status == $this->status) ? ' class="current"' : '';
            if ($counts->{$status}) {
                $links[$status] = '<a href="' . esc_url('?page=hashform&status=' . $status) . '" ' . $class . '>' . sprintf(__('%1$s <span class="count">(%2$s)</span>', 'hash-form'), $name, number_format_i18n($counts->{$status})) . '</a>';
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

    public static function get_count() {
        global $wpdb;
        $query = $wpdb->prepare("SELECT status FROM {$wpdb->prefix}hashform_forms WHERE id!=%d", 0);
        $results = $wpdb->get_results($query);
        $statuses = array('published', 'draft', 'trash');
        $counts = array_fill_keys($statuses, 0);
        foreach ($results as $row) {
            if ('trash' != $row->status) {
                $counts['published'] ++;
            } else {
                $counts['trash'] ++;
            }
        }
        $counts = (object) $counts;
        return $counts;
    }

    public static function get_status($id = 0) {
        global $wpdb;
        $query = $wpdb->prepare("SELECT status FROM {$wpdb->prefix}hashform_forms WHERE id=%d", $id);
        $results = $wpdb->get_results($query);
        return isset($results[0]) ? $results[0]->status : 'unavailable';
    }

}
