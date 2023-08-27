'use strict';

(function ($) {

  $(document).on('click', '[data-jupiterx-tool]', function () {
    var $self = $(this);
    var tool = $self.data('jupiterxTool');
    $self.nonce = $('.jupiterx-dashboard-widget').data('nonce');

    switch (tool) {
      case 'flush-network-cache':
        flushNetworkCache($self);
        break;
    }
  });

  // Flush network cache.
  function flushNetworkCache($self) {
    $.ajax({
      type: 'POST',
      url: ajaxurl,
      data: {
        'action': 'jupiterx_dashboard',
        'nonce': $self.nonce,
        'type': 'get-sites'
      },
      beforeSend: function beforeSend() {
        $self.addClass('updating-message');
      }
    }).success(function (response) {

      // For each group of sites.
      $.each(response.data, function (index, sites) {
        $.ajax({
          type: 'POST',
          url: ajaxurl,
          async: false,
          data: {
            'action': 'jupiterx_dashboard',
            'nonce': $self.nonce,
            'type': $self.data('jupiterxTool'),
            'sites': sites
          }
        }).success(function (response) {
          console.log(response);
        }).error(function (response) {
          console.log(response);
        });
      });

      // Final feedback.
      $self.addClass('updated-message button-disabled').removeClass('updating-message');

      setTimeout(function () {
        $self.removeClass('updated-message button-disabled');
      }, 1500, $self);
    }).error(function (response) {
      console.log(response);
    });
  }
})(jQuery);