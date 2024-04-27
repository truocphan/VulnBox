##### [Version 32.0.18](https://github.com/Codeinwp/woocommerce-product-addon/compare/v32.0.17...v32.0.18) (2024-04-17)

### Improvements
- **Updated internal dependencies:** Enhanced performance and security.

##### [Version 32.0.17](https://github.com/Codeinwp/woocommerce-product-addon/compare/v32.0.16...v32.0.17) (2024-04-03)

### Fixes
- Resolved an issue where uploaded files were not displayed in the cart, checkout, or order dashboard.

##### [Version 32.0.16](https://github.com/Codeinwp/woocommerce-product-addon/compare/v32.0.15...v32.0.16) (2024-04-01)

### Improvements
- **Updated internal dependencies**

##### [Version 32.0.15](https://github.com/Codeinwp/woocommerce-product-addon/compare/v32.0.14...v32.0.15) (2024-03-29)

### Fixes
- Fixed file input type matching on the frontend that was causing issues with file upload
- Updated internal dependencies

##### [Version 32.0.14](https://github.com/Codeinwp/woocommerce-product-addon/compare/v32.0.13...v32.0.14) (2024-03-26)

### Bug Fixes
- Fixed conflict with WP Customer Reviews
- Fixed issue when fields not displayed on product pages
- Fixed file upload issue using a Select button

##### [Version 32.0.13](https://github.com/Codeinwp/woocommerce-product-addon/compare/v32.0.12...v32.0.13) (2024-03-07)

### Bug Fixes
- Conditional fields not displaying in the cart
- Predefined values not working with conditions

##### [Version 32.0.12](https://github.com/Codeinwp/woocommerce-product-addon/compare/v32.0.11...v32.0.12) (2024-03-01)

### Fixes
- Fixed the issue with fields not appearing on the front-end with the latest release
- Updated logo

##### [Version 32.0.11](https://github.com/Codeinwp/woocommerce-product-addon/compare/v32.0.10...v32.0.11) (2024-02-29)

### Bug Fixes
- Fixed error with the latest version related to current_user_can check

##### [Version 32.0.10](https://github.com/Codeinwp/woocommerce-product-addon/compare/v32.0.9...v32.0.10) (2024-02-28)

### Bug Fixes
- [PHP 8.2] Fixed deprecation notices
- Fixed the choose file issue
- Fixed REST API compatibility issue with PHP8
- Fixed edit cart issue
- Fixed repeater fields issue
- Fixed user permission issue
- Fixed texter popup height/width issue
- Added shortcode rendering support in the field description
- Fixed cart subtotal issue checkbox fixed fee option
- Fixed date looses issue
- Fixed typo for Square name in Image Cropper
- Updated dependencies
- Enhanced security

##### [Version 32.0.9](https://github.com/Codeinwp/woocommerce-product-addon/compare/v32.0.8...v32.0.9) (2023-08-31)

- Fix: Do not enforce step min quantity if quantity is already set
- Fixed compatibility issue with HPOS

##### [Version 32.0.8](https://github.com/Codeinwp/woocommerce-product-addon/compare/v32.0.7...v32.0.8) (2023-06-06)

- Fix: group being duplicated
- Fix: negative price not taken into consideration

##### [Version 32.0.7](https://github.com/Codeinwp/woocommerce-product-addon/compare/v32.0.6...v32.0.7) (2023-05-03)

- Harden security

##### [Version 32.0.6](https://github.com/Codeinwp/woocommerce-product-addon/compare/v32.0.5...v32.0.6) (2023-04-19)

- [Fix] Resolved an issue where the Edit Cart feature was losing the value of certain fields based on conditional logic.
- [Fix] Fixed a bug where the Edit Cart feature was not working when no changes were made to product options. (P.S.: PPOM Pro side of this issue was released with PPOM Pro v25.1.3)
- [Fix] Implemented a security fix

##### [Version 32.0.5](https://github.com/Codeinwp/woocommerce-product-addon/compare/v32.0.4...v32.0.5) (2023-04-11)

* [Fix] Implemented input sanitization for PPOM Field Input during the first save on PPOM Group creation.
* [Fix] [PPOM Pro] Fixed issue where Cart Edit feature caused loss of field selections on product page.
* [Fix] Fixed issue where duplicated button failed to copy field unless renamed and saved.
* [Fix] Fixed issue with deleting PPOM groups causing PHP notice in WP Debug mode.
* Themeisle SDK update

##### [Version 32.0.4](https://github.com/Codeinwp/woocommerce-product-addon/compare/v32.0.3...v32.0.4) (2023-03-31)

- [Fix] The options of the meta field are not visible on smaller window size
- [Fix] Layout conflict with Neve on product page on the quantity field and add to cart button
- [Fix] [PPOM Pro] Editing created field throws an error
- [Fix] [PPOM Pro] No alt text for image input type
- [Fix] [PPOM Pro] Total price get 0 when the price matrix is used for discounts on higher quantities
- [Fix] [PPOM Pro] File upload doesn't work with iOS Safari browser if field shows up based on condition
- [Fix] [PPOM Pro] Conditional repeater prevents the product from being added to the cart
- [Fix] [PPOM Pro] Images field cumulates price of all options if the labels are not in English
- [Fix] [PPOM Pro] Image cropper doesn't work on PHP8
- Themeisle SDK Update
- Min PHP Version updated as 7.2
- Minimum WooCommerce Version updated as 6.5

##### [Version 32.0.3](https://github.com/Codeinwp/woocommerce-product-addon/compare/v32.0.2...v32.0.3) (2023-02-23)

- [Fix] Translations on some strings have been fixed.
- [Fix] [PPOM Pro] Image Cropper field doesn't work if enabled with Popup in Safari browser on mobile
- [Fix] [PPOM Pro] Added shadow to color palette choices to better visibility
- [Tweak] Deselect Support for the ImageSelect Field
- [Fix] Fixes on the PPOM Settings page.
- [Fix] [Date Input] UI/UX improved to emphasize JQuery date picker is needed for some features (Min Date, Max Date, Date Formats, Default Value, First Day of Week, Year Range, Disable Weekends, Disable Past Dates) of PPOM Date Field.
- [Fix] [Date Input] Max Date fixes (from now on, can be worked independently from disable past dates mode)
- [Fix] [Date Input] Manual date entering by keyboard was disallowed for the JQuery date picker.
- [Fix] [Date Input] Default date issue of the JQuery date picker has been fixed. (It was not working with relative values such as +5d or +1m 3d .)
- [Fix] [Date Input] Fix on the Disable past dates feature (compatibility support with Min date feature)
- [Fix] [PPOM Pro] Broken date range layout was fixed.
- [Fix] Adding two fields with same name make them overwrite each other (data name validation was added for new fields)
- [Fix] [PPOM Pro] Cart Edit is not working when Pop Up Edit is enabled
- [Fix] [PPOM Pro] Empty Field Generation, which happens when PPOM Pro is activated, has been fixed.
- Themeisle-SDK version was updated.

##### [Version 32.0.2](https://github.com/Codeinwp/woocommerce-product-addon/compare/v32.0.1...v32.0.2) (2022-11-24)

- [Fix] Unnecessary spaces in Text Area Field
- WC 7.1 Compatibility Info updated.
- [Fix] Use the correct text-domain in the translated string.
- [Fix] Change on the Fixed Fee TAX Price Calculation: If WC prices include taxes; inconsistent TAX calculation was happening on the Fixed Fees between the product page/cart page, that's fixed. https://github.com/Codeinwp/ppom-pro/issues/41
- [Fix] Change on the Fixed Fee TAX Price Calculation: Use the tax class of the dependent product for the fixed fee instead of the standard tax https://github.com/Codeinwp/ppom-pro/issues/82
- [Fix] [PPOM Pro] If a Price Matrix field was shown according to the two or more conditional field dependencies; the price matrix price was passed to the cart as 0. That's fixed. https://github.com/Codeinwp/ppom-pro/issues/40
- [Promote] Conditional Field Repeater freemium/locked tab
- [PPOM Pro] Compatibility for PPOM PRO Bulk Quantity Field.
- [Feat] New WP Filter Hook (ppom_legacy_input_meta_classes) was added. https://github.com/Codeinwp/woocommerce-product-addon/pull/78
- [Fix] Some minor fixes on Frontend Input Classes https://github.com/Codeinwp/woocommerce-product-addon/pull/78
- [Feat] Changelog viewer admin screen was created.
- Compatibility support for PPOM Pro v25.1.0

##### [Version 32.0.1](https://github.com/Codeinwp/woocommerce-product-addon/compare/v32.0.0...v32.0.1) (2022-10-19)

- [Fix] PPOM Field prices are not passed into the cart issue (affects only non-pro users) has been fixed. (that was a regression occurred after the v32.0.0 release)
- [Fix] in the various areas (cart, checkout, admin order details etc.); PPOM Field Titles are shown as data-name instead of human-readable field title issue has been fixed. (that was a regression occurred after the v32.0.0 release)
- [Fix] A minor fix on the ppom_hooks_save_cropped_image global function.

#### [Version 32.0.0](https://github.com/Codeinwp/woocommerce-product-addon/compare/v31.0.1...v32.0.0) (2022-10-18)


- [Fix] Order Again PPOM compatibility support has been added. [#47](https://github.com/Codeinwp/woocommerce-product-addon/issues/47) 
- [Fix] [Pro compatibility] Fix the conditional field issue of the PPOM Pro Image Dropdown field [#48](https://github.com/Codeinwp/woocommerce-product-addon/issues/48) 
- [Fix] [Pro compatibility] Fix for PPOM Pro (Image Cropper doesn't work with some AJAX add to cart plugins)
- [Fix] Reload the PPOM Field Group Listing screen if a field is deleted.
- [Fix] [Pro compatibility] Conditional Field Support of the Image Dropdown field of the PPOM Pro has been fixed.
- [Fix] [Pro compatibility] Product featured image is replaced by the ImageSelect field without making a selection
- Promote locked fields

##### [Version 31.0.1](https://github.com/Codeinwp/woocommerce-product-addon/compare/v31.0.0...v31.0.1) (2022-09-16)

- Themeisle-SDK upgraded to latest version (v3.2.30)

#### [Version 31.0.0](https://github.com/Codeinwp/woocommerce-product-addon/compare/v30.1.4...v31.0.0) (2022-09-12)

- Improvement on PPOM Pro compatibility
- Readme.txt updated.

##### [Version 30.1.4](https://github.com/Codeinwp/woocommerce-product-addon/compare/v30.1.3...v30.1.4) (2022-09-02)

- [Fix] The fatal error related to the tsdk_utmify() function being missing has been fixed.

##### [Version 30.1.3](https://github.com/Codeinwp/woocommerce-product-addon/compare/v30.1.2...v30.1.3) (2022-09-02)

- [Fix] Browser console logs have been removed.
- [Fix] The bug that occurs when a group saving empty fields has been fixed.
- [Tweak] Do not allow removing of the Administrator role from PPOM Permissions

##### [Version 30.1.2](https://github.com/Codeinwp/woocommerce-product-addon/compare/v30.1.1...v30.1.2) (2022-08-15)

- [Fix] A PHP Notice fixed and a technical improvement has been made.
- Readme.txt updated
- Improvement on the themeisle-sdk compatibility.

##### [Version 30.1.1](https://github.com/Codeinwp/woocommerce-product-addon/compare/v30.1.0...v30.1.1) (2022-08-12)

- change plugin maintainer
