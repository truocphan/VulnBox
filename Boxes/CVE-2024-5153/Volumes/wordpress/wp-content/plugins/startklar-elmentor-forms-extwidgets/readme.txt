=== Startklar Elementor Addons ===
Tags: Country code, DropZone, Honeypot
Contributors: WEB-SHOP-HOSTING
Tested up to: 6.5.2
Stable tag: 1.7.15
Requires PHP: 5.6.20
Requires at least: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Donate link: https://www.paypal.com/donate/?hosted_button_id=J2FXPNSYGWLBE

The plugin expands the Elementor-PRO Forms builder with Country Code selector, DropZone and Advanced Honeypot.

== Description ==
The plugin adds two new Widgets to the Elementor-PRO Forms builder.
- **Phone Country Code** - choosing a telephone prefix depending on the country
- **DropZone** - Drag & Drop multiple file upload field
- **Advanced Honeypot** - Improved up-to-date spam filtering

= Phone Country Code =
- Telephone prefix depending on the country.
- Provides the ability to select a searchable telephone prefix.
- The search works both by prefix number and by country name.
- The standard for WP language file is used, then it allows to translate the selector depending on the change of the locale inside WP.
- In the display of countries there is a flag image, which makes it easier to find and makes it richer. When displaying an empty value, there is an automatic determination of the current country using geolocation based on IP identification.
[youtube https://youtu.be/ZR9bkXPnsuc]

= DropZone =
- File will be shown in Elementor -> Submission
- File can be send by email
[youtube https://youtu.be/JSFna01AnwM]

= Advanced Honeypot - better reCaptcha alternative =
We added an advanced honeypot widget to elementor pro forms.
[youtube https://youtu.be/102sMMmQsPA]
This widget is very similar to the native elementor widget, except for very important differences. 
Elementor Form fields can be required or optional. 
Spiders that send spam through forms take into account the presence of traps and try to bypass them, for example,
they check for the presence of required fields in form and fill in only its so as not to fall into honeypot spam filters.
The honeypot field that the elementor provides can only be optional, because if you make it required, 
normal visitors will not be able to fill it out, since it is not displayed on the front (it is hidden) and the form simply will not submit by the browser, since the required fields are checked by the browser (even invisible).
At the same time, it turns out that the optional fields are not filled with spiders just to bypass the honeypot. 
Our honeypot implementation is different in that the honeypot field is always shown as required for spiders, but then this attribute is removed using JavaScript. 
The spider reads only the html and sees that the field is required and fills it in, this causes the spam filter to work. For real visitors, 
this field is invisible and JavaScript removes the mandatory attribute for this field, i.e. spam filter is not activated. 
In addition, the native honeypot field has a "display: none;" style written directly into the input tag that clearly tells the spiders that the field is not visible to users and is a potential trap. 
In our version of the widget, the honeypot field is hidden through styles that are in a common style block that is difficult for spiders to find our traps field.

== Installation ==
**Plugin installation.**
Installation is done as standard for WP. It is important that the plugins “Elementor” and “Elementor PRO” must be installed and activated beforehand.

**Plugin setup.**
The setup interface is as simple and intuitive as possible.

**Usage.**
Using the plugin is no different from using other form fields in the Elementor Forms Builder.


== Frequently Asked Questions ==

= Why do you need a plugin? Doesn't Elementor PRO have such functionality? =
Elementor PRO has the ability to make a selector, but it does not contain country flags and does not know how to do geolocation by IP.
Also in our plugin implemented autocomplete.

== Screenshots ==

1. General view of the widget when the country is already selected or when geolocation is triggered.
2. Autocomplete operation when trying to select the desired country.
3. Appearance of the administrative part of the plugin.
4. General view of the DropZone widget.
5. Example of sending files with preview thumbnails.
6. Field parameters setting interface.
7. Embedding a honeypot in a form.

== Changelog ==

= 1.7.15 =
* Fixed the vulnerability related to PHP file uploads.

= 1.7.14 =
* Fixed the vulnerability related to arbitrary file uploads.

= 1.7.13 =
* ADD: Implemented new phone prefix formatting.
* ADD: Implemented phone prefix recognition in the following tel field.

= 1.7.12 =
* ADD: Included configuration for utilizing a country code selector within pop-ups.
* FIX: Resolved issues related to form data submission in Elementor.

= 1.7.11 =
* ADD: Added navigational links in the plugin's description.
* ADD: Added intuitive icons in drag and drop country selector configurations.

= 1.7.10 = 
* Resolved attachment email delivery and filename encoding discrepancies.

= 1.7.9 =
* Corrected the behavior of phone prefix selector elements across various forms.
* Addressed bugs that previously hindered the immediate visibility of changes during editing in Elementor.

= 1.7.8 =
* ADD: Added the ability to hide scrollbar of phone number prefix selector.
* Resolved issues with control section for customizing the style of the phone number prefix selector in the Elementor form.

= 1.7.7 =
* ADD: Added the ability to drag and drop for reordering country options in the selector.
* Fixed country determination for localhost.
* ADD: Added control section for customizing the style of the phone number prefix selector in the Elementor form.
* Resolved issues with phone prefix selector default values and encoding of spaces in file names.
* Fixed the formation of a title that is displayed as a popup for the selected item.

= 1.7.6 =
* Minor fixes

= 1.7.5 =
* Fixed overlay of elementor native styles on phone prefix selector. 
* ADD: Added the ability to manage selector styles through the elementor form field style control panel.

= 1.7.4 =
* ADD: Added the ability to display phone prefixes in the old format (0088 instead of +88)
* ADD: Added the ability to hide the flag or country name in the dropdown list
* ADD: Added the ability to specify a default value
* Bugs fixed

= 1.7.3 =
* Fixed duplicate file naming bug. Fixed re-initialization of the form.

= 1.7.2 =
* Name changed to Startklar Elementor Addons
* ADD: Video instructions for DropZone and Country Code Selector

= 1.7.1 =
* ADD: Video instraction for Honeypot 

= 1.7 =
* NEW: Added Advanced Honeypot Widget

= 1.6 =
* NEW: Introducing attach files to email

= 1.5 =
* Fixed the disappearance of the delete button in the panel Dropzone preview

= 1.4 =
* Fixed a bug that blocked file uploads on the server side

= 1.3 =
* A new DropZoneJs widget for uploading files has been added to Elementor Forms Builder

= 1.2 =
* Minor changes in styles and widget display

= 1.1 =
* Update Description and some links

= 1.0 =
* First Release

