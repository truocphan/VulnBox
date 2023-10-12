<?php
defined('ABSPATH') || die('Access Denied');

/**
 * Admin view class.
 */
class FMAdminView {
  /**
   * PLUGIN = 2 points to Contact Form Maker
   */
  const PLUGIN = 1;

  private $premium_link = 'https://10web.io/plugins/wordpress-form-maker/?utm_source=form_maker&utm_medium=free_plugin';

  /**
   * Generate form.
   *
   * @param string $content
   * @param array  $attr
   *
   * @return string Form html.
   */
  protected function form($content = '', $attr = array()) {
    echo $this->topbar();
    ob_start();
    // Form.
    $action = isset($attr['action']) ? esc_attr($attr['action']) : '';
    $method = isset($attr['method']) ? esc_attr($attr['method']) : 'post';
    $name = isset($attr['name']) ? esc_attr($attr['name']) : WDFMInstance(self::PLUGIN)->prefix . '_form';
    $id = isset($attr['id']) ? esc_attr($attr['id']) : '';
    $class = isset($attr['class']) ? esc_attr($attr['class']) : WDFMInstance(self::PLUGIN)->prefix . '_form';
    $style = isset($attr['style']) ? esc_attr($attr['style']) : '';
    $current_id = isset($attr['current_id']) ? esc_attr($attr['current_id']) : '';
    ?>
	<div id="fm-form-admin" class="wrap">
    <?php
    // Generate message container by message id or directly by message.
    $message_id = WDW_FM_Library(self::PLUGIN)->get('message', 0, 'intval');
    $message = WDW_FM_Library(self::PLUGIN)->get('msg', '');
    echo WDW_FM_Library(self::PLUGIN)->message_id($message_id, $message);
    ?>
      <form
          <?php echo $action ? 'action="' . $action . '"' : ''; ?>
          <?php echo $method ? 'method="' . $method . '"' : ''; ?>
          <?php echo $name ? ' name="' . $name . '"' : ''; ?>
          <?php echo $id ? ' id="' . $id . '"' : ''; ?>
          <?php echo $class ? ' class="' . $class . '"' : ''; ?>
          <?php echo $style ? ' style="' . $style . '"' : ''; ?>
      ><?php
      echo $content;
      // Add nonce to form.
      wp_nonce_field(WDFMInstance(self::PLUGIN)->nonce, WDFMInstance(self::PLUGIN)->nonce);
        ?>
        <input id="task" name="task" type="hidden" value=""/>
        <input id="current_id" name="current_id" type="hidden" value="<?php echo $current_id; ?>"/>
      </form>
    </div><?php
    return ob_get_clean();
  }

  /**
   * Generate title.
   *
   * @param array $params
   *
   * @return string Title html.
   */
  protected function title( $params = array() ) {
    $title = !empty($params['title']) ? $params['title'] : '';
    $title_class = !empty($params['title_class']) ? $params['title_class'] : '';
    $title_name = !empty($params['title_name']) ? $params['title_name'] : '';
    $title_id = !empty($params['title_id']) ? $params['title_id'] : '';
    $title_value = !empty($params['title_value']) ? $params['title_value'] : '';
		$add_new_button = !empty($params['add_new_button']) ? $params['add_new_button'] : '';

	  $attributes = '';
    if ( !empty($add_new_button) && is_array($add_new_button) ) {
      foreach ( $add_new_button as $key => $val ) {
        $attributes .= $key . '="' . $val . '"';
      }
    }
    ob_start();
    ?><div class="wd-page-title <?php echo $title_class; ?>">
      <h1 class="wp-heading-inline"><?php echo $title; ?>
      <?php
      if ( $title_name || $title_id || $title_value ) {
        ?>
        <span id="fm-title-edit">
          <input type="text" id="<?php echo $title_id; ?>" name="<?php echo $title_name; ?>" value="<?php echo $title_value; ?>" />
        </span>
        <?php
      }
      if ( $add_new_button ) {
        ?>
        <a class="page-title-action" <?php echo $attributes; ?>>
          <?php _e('Add New', WDFMInstance(self::PLUGIN)->prefix); ?>
        </a>
        <?php
      }
      ?>
      </h1>
    </div><?php
    return ob_get_clean();
  }

  /**
   * Generate buttons.
   *
   * @param array $buttons
   * @param bool $single
   * @param array $parent
   *
   * @return array Buttons html.
   */
  protected function buttons( $buttons = array(), $single = FALSE, $parent = array() ) {
    ob_start();
    if ( !$single ) {
      $parent_id = isset($parent['id']) ? esc_attr($parent['id']) : '';
      $parent_class = isset($parent['class']) ? esc_attr($parent['class']) : 'wd-buttons';
      $parent_style = isset($parent['style']) ? esc_attr($parent['style']) : '';
      ?>
    <div
      <?php echo $parent_id ? 'id="' . $parent_id . '"' : ''; ?>
      <?php echo $parent_class ? ' class="' . $parent_class . '"' : ''; ?>
      <?php echo $parent_style ? ' style="' . $parent_style . '"' : ''; ?>
      >
      <?php
    }
    foreach ($buttons as $button) {
      $title = isset($button['title']) ? esc_attr($button['title']) : '';
      $value = isset($button['value']) ? esc_attr($button['value']) : '';
      $name = isset($button['name']) ? esc_attr($button['name']) : '';
      $id = isset($button['id']) ? esc_attr($button['id']) : '';
      $class = isset($button['class']) ? esc_attr($button['class']) : '';
      $style = isset($button['style']) ? esc_attr($button['style']) : '';
      $onclick = isset($button['onclick']) ? esc_attr($button['onclick']) : '';
      ?><button type="submit"
               <?php echo $value ? ' value="' . $value . '"' : ''; ?>
               <?php echo $name ? ' name="' . $name . '"' : ''; ?>
               <?php echo $id ? ' id="' . $id . '"' : ''; ?>
               class="wd-button <?php echo $class; ?>"
               <?php echo $style ? ' style="' . $style . '"' : ''; ?>
               <?php echo $onclick ? ' onclick="' . $onclick . '"' : ''; ?>
         ><?php echo $title; ?></button><?php
    }
    if ( !$single ) {
      ?>
    </div>
      <?php
    }
    return ob_get_clean();
  }

  /**
   * Search.
   *
   * @return string
   */
  protected function search() {
    $search = WDW_FM_Library(self::PLUGIN)->get('s', '', 'esc_html');
    ob_start();
    ?>
    <p class="search-box">
      <input name="s" value="<?php echo $search; ?>" type="search" onkeypress="return input_search(event, this)" />
      <input class="button" value="<?php _e('Search', WDFMInstance(self::PLUGIN)->prefix); ?>" type="button" onclick="search(this)" />
    </p>
    <?php

    return ob_get_clean();
  }

  /**
   * Pagination.
   *
   * @param     $page_url
   * @param     $total
   * @param int $items_per_page
   *
   * @return string
   */
  protected function pagination($page_url = '', $total = 0, $items_per_page = 20) {
    $page_number = WDW_FM_Library(self::PLUGIN)->get('paged', 1, 'intval');
    $search = WDW_FM_Library(self::PLUGIN)->get('s', '');
    $orderby = WDW_FM_Library(self::PLUGIN)->get('orderby', '');
    $order = WDW_FM_Library(self::PLUGIN)->get('order', '');
	$url_arg = array();
	if( !empty($search) ) {
		$url_arg['s'] = $search;
	}
	if( !empty($orderby) ) {
		$url_arg['orderby'] = $orderby;
	}
	if( !empty($order) ) {
		$url_arg['order'] = $order;
	}
    $page_url = add_query_arg($url_arg, $page_url);
	  
    if ( $total ) {
      if ( $total % $items_per_page ) {
        $pages_count = ($total - $total % $items_per_page) / $items_per_page + 1;
      }
      else {
        $pages_count = ($total - $total % $items_per_page) / $items_per_page;
      }
    }
    else {
      $pages_count = 1;
    }
    ob_start();
    ?>
    <div class="tablenav-pages">
      <span class="displaying-num">
        <?php printf(_n('%s item', '%s items', $total, WDFMInstance(self::PLUGIN)->prefix), $total); ?>
      </span>
      <?php
      if ( $total > $items_per_page ) {
        ?>
      <span class="pagination-links" data-pages-count="<?php echo $pages_count; ?>">
        <?php
        if ( $page_number == 1 ) {
          ?>
          <span class="tablenav-pages-navspan button disabled" aria-hidden="true">«</span>
          <span class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>
          <?php
        }
        else {
          ?>
          <a href="<?php echo add_query_arg(array('paged' => 1), $page_url); ?>" class="first-page button"><span class="screen-reader-text"><?php _e('First page', WDFMInstance(self::PLUGIN)->prefix); ?></span><span aria-hidden="true">«</span></a>
          <a href="<?php echo add_query_arg(array('paged' => ($page_number == 1 ? 1 : ($page_number - 1))), $page_url); ?>" class="previous-page button"><span class="screen-reader-text"><?php _e('Previous page', WDFMInstance(self::PLUGIN)->prefix); ?></span><span aria-hidden="true">‹</span></a>
          <?php
        }
        ?>
        <span class="paging-input">
          <label for="current-page-selector" class="screen-reader-text"><?php _e('Current Page', WDFMInstance(self::PLUGIN)->prefix); ?></label>
          <input type="text" class="current-page" name="current_page" value="<?php echo $page_number; ?>" onkeypress="return input_pagination(event, this)" size="1" />
          <span class="tablenav-paging-text">
             <?php _e('of', WDFMInstance(self::PLUGIN)->prefix); ?>
            <span class="total-pages"><?php echo $pages_count; ?></span>
          </span>
        </span>
        <?php
        if ( $page_number >= $pages_count ) {
          ?>
          <span class="tablenav-pages-navspan button disabled" aria-hidden="true">›</span>
          <span class="tablenav-pages-navspan button disabled" aria-hidden="true">»</span>
          <?php
        }
        else {
          ?>
          <a href="<?php echo add_query_arg(array('paged' => ($page_number >= $pages_count ? $pages_count : ($page_number + 1))), $page_url); ?>" class="next-page button"><span class="screen-reader-text"><?php _e('Next page', WDFMInstance(self::PLUGIN)->prefix); ?></span><span aria-hidden="true">›</span></a>
          <a href="<?php echo add_query_arg(array('paged' => $pages_count), $page_url); ?>" class="last-page button"><span class="screen-reader-text"><?php _e('Last page', WDFMInstance(self::PLUGIN)->prefix); ?></span><span aria-hidden="true">»</span></a>
          <?php
        }
        ?>
      </span>
        <?php
      }
      ?>
    </div>
    <?php

    return ob_get_clean();
  }

  /**
   * Bulk actions.
   *
   * @param array $actions
   * @return string
   */
  protected function bulk_actions( $actions = array() ) {
    ob_start();
    ?>
    <div class="alignleft actions bulkactions">
      <label for="bulk-action-selector-top" class="screen-reader-text"><?php _e('Select bulk action', WDFMInstance(self::PLUGIN)->prefix); ?></label>
      <select name="bulk_action" id="bulk-action-selector-top">
        <option value="-1"><?php _e('Bulk Actions', WDFMInstance(self::PLUGIN)->prefix); ?></option>
        <?php
        foreach ( $actions as $key => $action ) {
          ?>
          <option value="<?php echo $key; ?>"><?php echo $action['title']; ?></option>
          <?php
        }
        ?>
      </select>
      <input type="button" id="doaction" class="button action" onclick="wd_bulk_action(this)" value="<?php _e('Apply', WDFMInstance(self::PLUGIN)->prefix); ?>" />
    </div>
    <?php

    return ob_get_clean();
  }

  function import_popup_div() {
    if (WDFMInstance(self::PLUGIN)->is_free != 2) {
      do_action('fm_popup_import_content');
    }
  }

  /**
   * Generate top bar.
   *
   * @return string Top bar html.
   */
  protected function topbar() {
	$fm_settings = WDFMInstance(self::PLUGIN)->fm_settings;
    $page = WDW_FM_Library(self::PLUGIN)->get( 'page', '', 'esc_html' );
    $page = str_replace(WDFMInstance(self::PLUGIN)->menu_postfix, '', $page);
    $task = WDW_FM_Library(self::PLUGIN)->get( 'task', '', 'esc_html' );
    $user_guide_link = 'https://help.10web.io/hc/en-us/sections/360002133951-Form-Maker-Documentation?utm_source=form_maker&utm_medium=free_plugin';
    $show_content = true;
    $show_guide_link = true;
    $show_head = false;
    switch ($page) {
      case 'blocked_ips': {
        $user_guide_link = 'https://help.10web.io/hc/en-us/articles/360015499752-Eliminating-Spam-Submissions?utm_source=form_maker&utm_medium=free_plugin';
        break;
      }
      case 'options': {
        $user_guide_link = 'https://help.10web.io/hc/en-us/articles/360016112771-General-Options?utm_source=form_maker&utm_medium=free_plugin';
        break;
      }
      case 'pricing': {
        $show_content = false;
        $show_guide_link = false;
        $show_head = true;
        $user_guide_link = 'https://10web.io/plugins/wordpress-form-maker/?utm_source=form_maker&utm_medium=free_plugin';
        break;
      }
      case 'manage': {
        switch ( $task ) {
          case 'add':
          case 'edit':
          case 'edit_old': {
            $user_guide_link = 'https://help.10web.io/hc/en-us/articles/360015244232-Creating-a-Form?utm_source=form_maker&utm_medium=free_plugin';
            break;
          }
          case 'form_options':
          case 'form_options_old': {
            $user_guide_link = 'https://help.10web.io/hc/en-us/articles/360015862812-Form-Options-General?utm_source=form_maker&utm_medium=free_plugin';
            break;
          }
          case 'display_options': {
            $user_guide_link = 'https://help.10web.io/hc/en-us/articles/360015862692-Display-Options-and-Publishing?utm_source=form_maker&utm_medium=free_plugin';
            break;
          }
          default: {
            $user_guide_link = 'https://help.10web.io/hc/en-us/articles/360015435831-Introduction?utm_source=form_maker&utm_medium=free_plugin';
            $show_content = false;
            $show_head = true;
          }
        }
        break;
      }
      case 'submissions': {
        $user_guide_link = 'https://help.10web.io/hc/en-us/articles/360015863292-Managing-Form-Submissions?utm_source=form_maker&utm_medium=free_plugin';
        break;
      }
      case 'themes': {
        $user_guide_link = 'https://help.10web.io/hc/en-us/articles/360016083071-Configuring-Form-Maker-Themes?utm_source=form_maker&utm_medium=free_plugin';
        break;
      }
      case 'addons': {
        $show_content = false;
        $show_head = true;
        $user_guide_link = 'https://10web.io/plugins/wordpress-form-maker/?utm_source=form_maker&utm_medium=free_plugin#plugin_extensions';
        break;
      }
      default: {
        return '';
      }
    }
    $show_content = $show_content && WDFMInstance(self::PLUGIN)->is_free;
    $support_forum_link = 'https://wordpress.org/support/plugin/' . (WDFMInstance(self::PLUGIN)->is_free == 2 ? 'contact-form-maker' : 'form-maker') . '/#new-post';
    $premium_link = $this->premium_link;
    wp_enqueue_style(WDFMInstance(self::PLUGIN)->handle_prefix . '-roboto');
    if ( $fm_settings['fm_developer_mode'] ) {
      wp_enqueue_style(WDFMInstance(self::PLUGIN)->handle_prefix . '-topbar');
    }
    else {
      wp_enqueue_style(WDFMInstance(self::PLUGIN)->handle_prefix . '-styles');
    }
    ob_start();
    ?>
	<div class="wrap">
		<h1 class="fm-head-notice">&nbsp;</h1>
		<div class="fm-topbar-container">
		  <?php
		  if ($show_content) {
			?>
		  <div class="fm-topbar fm-topbar-content">
			<div class="fm-topbar-content-container">
			  <div class="fm-topbar-content-title">
				<?php _e('Form Maker Premium', WDFMInstance(self::PLUGIN)->prefix); ?>
			  </div>
			  <div class="fm-topbar-content-body">
				<?php _e('Add unlimited fields, create multi-page forms with fully customizable themes and much more.', WDFMInstance(self::PLUGIN)->prefix); ?>
			  </div>
			</div>
			<div class="fm-topbar-content-button-container">
			  <a href="<?php echo $premium_link; ?>" target="_blank" class="fm-topbar-upgrade-button"><?php _e( 'Upgrade', WDFMInstance(self::PLUGIN)->prefix ); ?></a>
			</div>
		  </div>
			<?php
		  }
		  ?>
      <div class="fm-topbar_cont">
        <div class="fm-topbar fm-topbar-links">
          <div class="fm-topbar-links-container">
            <?php if ( $show_guide_link ) { ?>
              <a href="<?php echo $user_guide_link; ?>" target="_blank" class="fm-topbar_user_guid">
                <div class="fm-topbar-links-item">
                  <?php _e('User guide', WDFMInstance(self::PLUGIN)->prefix); ?>
                </div>
              </a>
            <?php
            }?>
          </div>
        </div>
        <?php
        if (WDFMInstance(self::PLUGIN)->is_free) {
        ?>
        <div class="fm-topbar fm-topbar-links fm-topbar_support_forum">
          <div class="fm-topbar-links-container">
            <a href="<?php echo $support_forum_link; ?>" target="_blank" class="fm-topbar_support_forum">
                <div class="fm-topbar-links-item">
                <img src="<?php echo WDFMInstance(self::PLUGIN)->plugin_url . '/css/images/help.svg'; ?>" class="help_icon">
                <?php _e('Ask a question', WDFMInstance(self::PLUGIN)->prefix); ?>
                </div>
              </a>
          </div>
        </div>
        <?php
        }
        ?>
      </div>
		</div>
		<?php if ( $show_head ) {
		  $menus = array(
        'manage' => array(
          'href' => add_query_arg( array('page' => 'manage' . WDFMInstance(self::PLUGIN)->menu_postfix ), admin_url('admin.php')),
          'target' => '_self',
          'name' => __('Forms', WDFMInstance(self::PLUGIN)->prefix)
        ),
        'addons' => array(
          'href' => 'https://10web.io/plugins/wordpress-form-maker/?utm_source=form_maker&utm_medium=free_plugin#plugin_extensions',
          'target' => '_blank',
          'name' => __('Extensions', WDFMInstance(self::PLUGIN)->prefix)
        ),
        'pricing' => array(
          'href' => 'https://10web.io/plugins/wordpress-form-maker/?utm_source=form_maker&utm_medium=free_plugin',
          'target' => '_blank',
          'name' => __('Premium Version', WDFMInstance(self::PLUGIN)->prefix) .' <span class="fm-upgrade">' . __('Upgrade', WDFMInstance(self::PLUGIN)->prefix) . '</span>'
        ),
		  );
		  ?>
		  <div class="fm-head">
			<div><img src="<?php echo WDFMInstance(self::PLUGIN)->plugin_url . '/images/FormMaker.png'; ?>"></div>
        <ul class="fm-breadcrumbs">
          <?php
          foreach ( $menus as $key => $item ) {
            if ( !WDFMInstance(self::PLUGIN)->is_free && $key == 'pricing' ) {
              continue;
            }
            ?>
            <li class="fm-breadcrumb-item">
              <a class="fm-breadcrumb-item-link<?php echo ( $key == $page ) ? ' fm-breadcrumb-item-link-active' : ''; ?>" href="<?php echo $item['href']; ?>" target="<?php echo $item['target']; ?>"><?php echo $item['name']; ?></a>
            </li>
            <?php
          }
          ?>
        </ul>
		  </div>
		<?php }	?>
	</div>
	<?php
    return ob_get_clean();
  }

  /**
   * @param $message
   * @param string $premium_link
   * @param string $premium_link_text
   * @return string
   */
  protected function free_message($message = '', $premium_link = '', $premium_link_text = '', $id = '') {
    $upgrade = false;
    if ('' == $premium_link) {
      $premium_link = $this->premium_link;
    }
    if ('' == $premium_link_text) {
      $premium_link_text = __( 'Upgrade', WDFMInstance(self::PLUGIN)->prefix );
      $upgrade = true;
    }
    ob_start();
    ?>
    <div class="fm-free-message" <?php if ($id) { echo 'id="' . $id . '"'; } ?>>
      <div class="fm-free-message-body">
        <?php echo $message; ?>
      </div>
      <div class="fm-free-message-button-container">
        <a href="<?php echo $premium_link; ?>" target="_blank" class="fm-free-message-upgrade-button <?php if(!$upgrade) {echo 'fm-free-message-promo-button'; } ?>"><?php echo $premium_link_text; ?></a>
      </div>
    </div>
    <?php
    return ob_get_clean();
  }

  /**
   * Generate stripe promo box.
   *
   * @param $message
   * @param $addon_message
   * @param $addon_link
   *
   * @return string Stripe promo box html.
   */
  protected function promo_box( $message = '', $addon_message = '', $addon_link = '', $id = 0 ) {
    $premium_link = $this->premium_link;
    ob_start();
    ?>
    <div class="fm-free-message fm-promo-message" <?php if ($id) { echo 'id="' . $id . '"'; } ?>>
      <div class="fm-free-message-body">
        <?php echo $message; ?>
      </div>
      <div class="fm-free-message-button-container">
        <a href="<?php echo $premium_link; ?>" target="_blank" class="fm-free-message-upgrade-button"><?php _e( 'Upgrade', WDFMInstance(self::PLUGIN)->prefix ); ?></a>
      </div>
      <div class="fm-free-message-body fm-free-message-huge">
        &
      </div>
      <div class="fm-free-message-body">
        <?php echo $addon_message; ?>
      </div>
      <div class="fm-free-message-button-container">
        <a href="<?php echo $addon_link; ?>" target="_blank" class="fm-free-message-upgrade-button fm-free-message-promo-button"><?php _e( 'Buy', WDFMInstance(self::PLUGIN)->prefix ); ?></a>
      </div>
    </div>
    <?php
    return ob_get_clean();
  }

  /**
   * Generate limitation aler.
   *
   * @return string Limitation alert html.
   */
  protected function limitation_alert() {
    $premium_link = $this->premium_link;
    ob_start();
    ?>
    <div class="fm-limitation-alert-container fm-hidden">
      <div class="fm-limitation-alert-overlay"></div>
      <div class="fm-limitation-alert">
        <span class="fm-ico-delete"></span>
        <div class="fm-limitation-alert-header">
          <?php _e('The free version is limited up to 7 fields.', WDFMInstance(self::PLUGIN)->prefix); ?>
        </div>
        <div class="fm-limitation-alert-header">
          <?php _e('Upgrade to Premium version to add unlimited fields.', WDFMInstance(self::PLUGIN)->prefix); ?>
        </div>
        <div class="fm-limitation-alert-body">
          <div class="fm-limitation-alert-header">
            <?php _e('Premium Plan also includes:', WDFMInstance(self::PLUGIN)->prefix); ?>
          </div>
          <ul>
            <li><?php _e('Payment integration fields', WDFMInstance(self::PLUGIN)->prefix); ?></li>
            <li><?php _e('File upload field', WDFMInstance(self::PLUGIN)->prefix); ?></li>
            <li><?php _e('Google Maps API Integration', WDFMInstance(self::PLUGIN)->prefix); ?></li>
            <li><?php _e('Front-end Submissions', WDFMInstance(self::PLUGIN)->prefix); ?></li>
          </ul>
        </div>
        <div class="fm-free-message-button-container">
          <a href="<?php echo $premium_link; ?>" target="_blank" class="fm-free-message-upgrade-button"><?php _e( 'Upgrade', WDFMInstance(self::PLUGIN)->prefix ); ?></a>
        </div>
      </div>
    </div>
    <script>
      function fm_limitation_alert(show) {
        if (show) {
          jQuery('.fm-limitation-alert-container').removeClass('fm-hidden');
        }
        else {
          jQuery('.fm-limitation-alert-container').addClass('fm-hidden');
        }
      }
      jQuery(function() {
        jQuery('.fm-limitation-alert-overlay, .fm-limitation-alert .fm-ico-delete').on('click', function() {
          fm_limitation_alert(false);
        });
      });
    </script>
    <?php
    return ob_get_clean();
  }
}
