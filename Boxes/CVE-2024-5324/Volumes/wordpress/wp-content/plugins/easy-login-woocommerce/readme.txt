=== Login/Signup Popup ( Inline Form + Woocommerce ) ===
Contributors: XootiX, xootixsupport
Donate link: https://www.paypal.me/xootix
Tags: social login, login customizer, registration, popup, custom registration fields
Requires at least: 3.0.1
Tested up to: 6.5
Stable tag: 2.7.2
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Replace your old login/registration form with an interactive popup & inline form design

== Description ==
[Live Demo](http://demo.xootix.com/easy-login-for-woocommerce/)
A simple and lightweight plugin which makes registration, login & reset password process super smooth.
You get two awesome fully customizable designs - Popup & Inline form with shortcodes.
You can choose which field to keep from the fields manager

### Features And Options:
* Supports Woocommerce
* Fully Ajaxed (no refresh)
* Login, Sign up , Lost Password & Reset password Form.
* Customizable Fields
* Fully Customizable.
* WPML compatible

### Add-ons:
* [Custom Registration Fields](http://xootix.com/plugins/easy-login-for-woocommerce#sp-addons) - Add extra fields to registration form , display them on user profile & myaccount page. (See Fields page to know supported field types )
* [Social Login](http://xootix.com/plugins/easy-login-for-woocommerce#sp-addons) - A single click login & registration with Google & Facebook.
* [One time Password (SMS) Login](http://xootix.com/plugins/easy-login-for-woocommerce#sp-addons) - Allow users to login with OTP ( sent on their phone or email) therefore removing the need to remember a password.
* [Google Recaptcha](http://xootix.com/plugins/easy-login-for-woocommerce#sp-addons) - Protect your form from bots using google recaptcha(v2/v3) + Password strength meter + Limit login attempts
* [Email Verification](http://xootix.com/plugins/easy-login-for-woocommerce#sp-addons) - Sends verification email on registration & restricts login access until email is verified


== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/ directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Click on Login/Signup Popup on the dashboard.

== Frequently Asked Questions ==

= How to setup? =
1. Go to apperance->menus
2. Under Login Popup Tab , select the desired option.

= Shortcodes =
Please check "How to use" tab under plugin settings for more information.

Use shortcode [xoo_el_action] to include it anywhere on the website. ( See info tab in settings to know more )
[xoo_el_action type="login" display="button" text="Login" change_to="logout" redirect_to="same"]

For Inline form
[xoo_el_inline_form active="login"]

You can also trigger popup using class.
Login         - xoo-el-login-tgr
Register      - xoo-el-reg-tgr
Lost Password - xoo-el-lostpw-tgr
For eg: <a class="xoo-el-login-tgr">Login</a>

= How to translate? =
1. Download PoEdit.
2. Open the easy-login-woocommerce.pot file in PoEdit. (/plugins/easy-login-woocommerce/languages/
   easy-login-woocommerce.pot)
3. Create new translation & translate the text.
4. Save the translated file with name "easy-login-woocommerce-Language_code". For eg: German(easy-login-woocommerce-de_DE)
   , French(easy-login-woocommerce-fr_FR). -- [Language code list](https://make.wordpress.org/polyglots/teams/)
5. Save Location: Your wordpress directory/wp-content/languages/


= How to override templates? =
Plugin template files are under templates folder.
Copy the template to your theme/templates/easy-login-woocommerce folder
If the template file is under sub directory, say in /globals folder then the copy directory will be
theme/templates/easy-login-woocommerce/globals/ For more info, check template header description


== Screenshots ==
1. Registration Form.
2. Lost Password Form
3. Reset password form.
4. Available registration Fields
5. Customizable Field
6. General Settings 1
7. General Settings 2
8. Style Settings 1
9. Style Settings 2
10. Shortcodes

== Changelog ==

= 2.7.2 =
* Fix - Password reset link not working for non-woocommerce users & when "Handle reset password" option disabled.
* Fix - Fields disappeared in 2.7.1

= 2.7.1 =
* New - Import/Export settings option
* Fix - Password eye toggle not working without icons.

= 2.7 =
* New - Field styling options
* New - Option to open popup using custom classes
* New - Show custom fields under users profile table
* Fix - Reset field not working  

= 2.6.5 =
* Fix- Field settings were not getting saved for some users.

= 2.6.4 =
* Fix - Autofill browser's saved email/password for login ( Visit Fields page after update to make it effective )

= 2.6.3 =
* New - added login_redirect and register_redirect attributes for inline form shortcode
* Fix - missing translation
* Fix - RTL settings styling
* Fix - alignment issues when field label is active

= 2.6.2 =
* Fix - Deprecated warning fix

= 2.6.1 =
* Fix - error generated without woocommerce
* Update - Translations

= 2.6 =
* New    - Single field Form pattern
* New    - Popup style - Sidebar
* New    - Navigation pattern option ( Footer links, Tabs )
* New    - Add logo above form
* New    - Polylang support
* New    - RTL Support
* New    - Option to edit shortcode of my account page
* Update - removed scrollbar script to reduce file size

*Template updates*
/templates/xoo-el-popup.php
/templates/global/xoo-el-header.php

= 2.5 =
* New - Password eye toggle
* New - Login, reset & lost password fields under field editor.
* New - Tab & Button texts as settings
* New - Separate login and registration forms using inline shortcode
* New - Email can be set as optional
* Fix - #login & #register opening popup only once

= 2.4 =
* Security fix

= 2.3 =
* Security fix

= 2.2 =
* Security update
* Settings UI update

= 2.1 =
* New 	- Added option to replace woocommerce checkout login form
* Fix 	- Minor Bugs

= 2.0 =
*** MAJOR UPDATE ***
* New 	- WPML Compatible
* Tweak - Template Changes
* Tweak - Code Optimized
* Tweak - Fields Tab separated
* Fix 	- Inline Form always showing on the top
* Fix 	- Multiple IDs warning
* Fix 	- Popup Flashing on page load
* Fix 	- Minor Bugs

= 1.7 =
* Fix - Registration issue for non woocommerce users
* Fix - OTP login activate/deactivate issue

= 1.6 =
* New - Mailchimp integration
* New - Added attribute "display" & "change_to_text"in shortcode [xoo_el_action]
* Tweak - Generate username functionality more secured
* Minor improvements

= 1.5 =
* Fix - Security issue

= 1.4 =
* Added "Hello Firstname" for menu item
* Minor bug fixes

= 1.3 =
* Major Release.
* New - Form input icons.
* New - Remember me checkbox.
* New - Terms and conditions checkbox.
* Tweak - Template changes
* Tweak - Removed font awesome icons , added custom font icons.

= 1.2 =
* Fix - Not working on mobile devices.
* New - Sidebar Image.
* New - Popup animation.

= 1.1 =
* Fix - Not working on mobile devices.
* New - Extra input padding.

= 1.0.0 =
* Initial Public Release.