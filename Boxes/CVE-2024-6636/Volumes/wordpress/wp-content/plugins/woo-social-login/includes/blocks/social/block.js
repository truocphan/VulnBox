"use strict";
(function (blocks, editor, components, i18n, element) {
  
  var el                = element.createElement
  var registerBlockType = blocks.registerBlockType
  var BlockControls     = editor.BlockControls
  var InspectorControls = editor.InspectorControls
  var TextControl       = components.TextControl
  var SelectControl     = components.SelectControl
  var CheckboxControl   = components.CheckboxControl
  const { serverSideRender: ServerSideRender } = window.wp;

  // custom block icon
  const iconEl = el('svg', { width: 20, height: 20 },
    el('path', { d: "M12.5,12H12v-0.5c0-0.3-0.2-0.5-0.5-0.5H11V6h1l1-2c-1,0.1-2,0.1-3,0C9.2,3.4,8.6,2.8,8,2V1.5C8,1.2,7.8,1,7.5,1 S7,1.2,7,1.5V2C6.4,2.8,5.8,3.4,5,4C4,4.1,3,4.1,2,4l1,2h1v5c0,0-0.5,0-0.5,0C3.2,11,3,11.2,3,11.5V12H2.5C2.2,12,2,12.2,2,12.5V13 h11v-0.5C13,12.2,12.8,12,12.5,12z M7,11H5V6h2V11z M10,11H8V6h2V11z" } )
  );

  const { __ } = i18n;

  // register social login custom block
  registerBlockType('wpweb/woo-social-login-block', {
    title: 'WooCommerce Social Login', 
    description: 'Display WooCommerce Social Login shortcode as block using the Gutenberg editor.',
    icon: 'networking',
    category: 'widgets',
    attributes: { // Necessary for saving block content.
      title: {
        type    : 'string',
        default : __('Prefer to Login with Social Media')
      },
      networks: {
        type    : 'array',
        default : Array()
      },
      redirect_url: {
        type    : 'url',
        default : ''
      },
      showonpage: {
        default : false
      },
      expand_collapse: {
        default : ''
      }
    },

    edit: function (props) {
      
      var attributes      = props.attributes
      var title           = props.attributes.title
      var networks        = props.attributes.networks
      var redirect_url    = props.attributes.redirect_url
      var showonpage      = props.attributes.showonpage
      var expand_collapse = props.attributes.expand_collapse

      return [
        el(BlockControls, { key: 'controls' }, // Display controls when the block is clicked on.
        ),
        el(InspectorControls, { key: 'inspector' }, // Display the block options in the inspector panel.
          el(components.PanelBody, {
            title: i18n.__('Social Login Settings'),
            className: 'wp-block-settings',
            initialOpen: true
          },
          // Social Login Title
          el(TextControl, {
            type  : 'string',
            label : i18n.__('Social Login Title'),
            help  : i18n.__('Enter a social login title.'),
            value : title,
            onChange: function (newContent) {
              props.setAttributes({ title: newContent })
            }
          }),
          // Social Networks
          el(SelectControl, {
            multiple : true,
            label  : i18n.__('Social Networks'),
            help   : i18n.__('Select social networks you want to show. Leave it empty to display all enable social networks.'),
            value  : networks,
            options: [
                { label: i18n.__('Facebook'), value: 'facebook' },
                { label: i18n.__('Twitter'), value: 'twitter' },
                { label: i18n.__('Google'), value: 'googleplus' },
                { label: i18n.__('LinkedIn'), value: 'linkedin' },
                { label: i18n.__('Yahoo'), value: 'yahoo' },
                { label: i18n.__('Foursquare'), value: 'foursquare' },
                { label: i18n.__('Windows Live'), value: 'windowslive' },
                { label: i18n.__('VK'), value: 'vk' },
                { label: i18n.__('Amazon'), value: 'amazon' },
                { label: i18n.__('Paypal'), value: 'paypal' },
                { label: i18n.__('Line'), value: 'line' },
                { label: i18n.__('GitHub'), value: 'github' },
                { label: i18n.__('Wordpress'), value: 'wordpresscom' },
                { label: i18n.__('Login with email'), value: 'email' },
              ],
            onChange: function (newNetwork) {
              props.setAttributes({ networks: newNetwork })
            }
          }),
          // Redirect url
          el(TextControl, {
            type  : 'url',
            label : i18n.__('Redirect URL'),
            help  : i18n.__('Enter a redirect URL for users after they login with social media. The URL must start with http:// or https://'),
            value : redirect_url,
            onChange: function (newRedirectURL) {
              props.setAttributes({ redirect_url: newRedirectURL })
            }
          }),
          // Show Only on Page / Post
          el(CheckboxControl, {
            label   : i18n.__('Show Only on Page / Post.'),
            help    : i18n.__('Check this box if you want to show social login buttons only on inner page of posts and pages.'),
            checked : showonpage,
            onChange: function (newCheck) {
              props.setAttributes({ showonpage: newCheck })
            }
          }),
          // Enable expand/collapse button
          el(SelectControl, {
            label  : i18n.__('Expand/Collapse Buttons'),
            help   : i18n.__('Here you can select how to show the social login buttons.'),
            value  : expand_collapse,
            options: [
                { label: i18n.__('None'), value: '' },
                { label: i18n.__('Collapse'), value: 'collapse' },
                { label: i18n.__('Expand'), value: 'expand' },
              ],
            onChange: function (newSelection) {
              props.setAttributes({ expand_collapse: newSelection })
            }
          })
          )
        ),
        el(ServerSideRender, {
          block      : 'wpweb/woo-social-login-block',
          attributes : attributes,
        }),
      ]
    },

    save: function (props) {
      return null;      
    },
  } );
}(
  window.wp.blocks,
  window.wp.editor,
  window.wp.components,
  window.wp.i18n,
  window.wp.element
) );

// Social login toggle on editor
jQuery(document).on('click', '.woo-slg-show-social-login', function () {
  jQuery('.woo-slg-social-container-checkout').slideToggle();
});
