'use strict';

(function ($) {

  if (typeof elementor === 'undefined' || typeof elementorCommonConfig.finder === 'undefined') {
    return;
  }

  /**
   * Add menu items.
   */
  function addMenuItems() {
    var items = [{
      name: 'jupiterx-theme-settings',
      icon: '',
      title: 'Theme Styles',
      type: 'link',
      link: elementorCommonConfig.finder.data.site.items['wordpress-customizer'].url,
      newTab: true
    }, {
      name: 'jupiterx-control-panel',
      icon: '',
      title: 'Control Panel',
      type: 'link',
      link: elementorCommonConfig.finder.data.site.items['wordpress-dashboard'].url + 'admin.php?page=jupiterx',
      newTab: true
    }];

    items.forEach(function (item) {
      elementor.modules.layouts.panel.pages.menu.Menu.addItem(item, 'more', 'exit-to-dashboard');
    });
  }

  elementor.on('panel:init', addMenuItems);
})(jQuery);