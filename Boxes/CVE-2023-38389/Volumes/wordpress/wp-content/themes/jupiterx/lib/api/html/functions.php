<?php
/**
 * The Jupiter HTML component contains a powerful set of functions to create flexible and easy overwritable HTML markup,
 * attributes and content.
 *
 * @package JupiterX\Framework\API\HTML
 *
 * @since   1.0.0
 */

/**
 * Register the output for the given ID.  This function enables the output to be:
 *
 *      1. modified by registering a callback to "{$id}_output"
 *      2. removed by using {@see jupiterx_remove_output()}.
 *
 * When in development mode, HTML comments containing the ID are added before and after the output, i.e. making it
 * easier to identify the content ID when inspecting an element in your web browser.
 *
 * Notes:
 *      1. Since this function uses {@see jupiterx_apply_filters()}, the $id argument may contain sub-hook(s).
 *      2. You can pass additional arguments to the functions that are hooked to <tt>$id</tt>.
 *
 * @since 1.0.0
 *
 * @param string $id     A unique string used as a reference. The $id argument may contain sub-hook(s).
 * @param string $output The given content to output.
 *
 * @return string|void
 */
function jupiterx_output( $id, $output ) {
	$args    = func_get_args();
	$args[0] = $id . '_output';

	$output = call_user_func_array( 'jupiterx_apply_filters', $args );

	if ( empty( $output ) ) {
		return;
	}

	if ( _jupiterx_is_html_dev_mode() ) {
		$id     = esc_attr( $id );
		$output = "<!-- open output: $id -->" . $output . "<!-- close output: $id -->";
	}

	return $output;
}

/**
 * Register and then echo the output for the given ID.  This function is a wrapper for {@see jupiterx_output()}.  See
 * {@see jupiterx_output()} for more details.
 *
 * @since 1.0.0
 * @uses  jupiterx_output()  To register output by ID.
 *
 * @param string $id     A unique string used as a reference. The $id argument may contain sub-hook(s).
 * @param string $output The given content to output.
 */
function jupiterx_output_e( $id, $output ) {
	$args = func_get_args();
	echo call_user_func_array( 'jupiterx_output', $args ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- Escaped in jupiterx_output.
}

/**
 * Removes the HTML output for the given $id, meaning the output will not render.
 *
 * @since 1.0.0
 *
 * @param string $id The output's ID.
 *
 * @return bool|_JupiterX_Anonymous_Filters
 */
function jupiterx_remove_output( $id ) {
	return jupiterx_add_filter( $id . '_output', false, 99999999 );
}

/**
 * Build the opening HTML element's markup.  This function fires 3 separate hooks:
 *
 *      1. "{id}_before_markup" - which fires first before the element.
 *      2. "{$id}_prepend_markup" - which fires after the element when the element is not self-closing.
 *      3. "{$id}_after_markup" - which fires after the element when the element is self-closing.
 *
 * These 3 hooks along with the attributes make it really easy to modify, replace, extend, remove or hook the
 * markup and/or attributes.
 *
 * When in development mode, the "data-markup-id" attribute is added to the element, i.e. making it
 * easier to identify the content ID when inspecting an element in your web browser.
 *
 * Notes:
 *      1. Since this function uses {@see jupiterx_apply_filters()}, the $id argument may contain sub-hook(s).
 *      2. You can pass additional arguments to the functions that are hooked to <tt>$id</tt>.
 *
 * @since 1.0.0
 *
 * @param string       $id         A unique string used as a reference. The $id argument may contain sub-hooks(s).
 * @param string|bool  $tag        The HTML tag. When set to false or an empty string, the HTML markup tag will not be
 *                                 built, but both action hooks will fire. If set to null, the function bails out, i.e.
 *                                 the HTML markup tag will not be built and neither action hook fires.
 * @param string|array $attributes Optional. Query string or array of attributes. The array key defines the
 *                                 attribute name and the array value defines the attribute value.
 *
 * @return string|void
 */
function jupiterx_open_markup( $id, $tag, $attributes = array() ) {
	$args            = func_get_args();
	$attributes_args = $args;

	// Set markup tag filter id.
	$args[0] = $id . '_markup';

	// If there are attributes, remove them from $args.
	if ( $attributes ) {
		unset( $args[2] );
	}

	// Filter the tag.
	$tag = call_user_func_array( 'jupiterx_apply_filters', $args );

	// If the tag is set to null, bail out.
	if ( null === $tag ) {
		return;
	}

	global $_jupiterx_is_selfclose_markup;

	// Remove the $tag argument.
	unset( $args[1] );
	unset( $attributes_args[1] );

	// Set and then fire the before action hook.
	$args[0] = $id . '_before_markup';
	$output  = call_user_func_array( '_jupiterx_render_action', $args );

	// Build the opening tag when tag is available.
	if ( $tag ) {
		$output .= '<' . esc_attr( $tag ) . ' ' . call_user_func_array( 'jupiterx_add_attributes', $attributes_args ) . ( _jupiterx_is_html_dev_mode() ? ' data-markup-id="' . esc_attr( $id ) . '"' : null ) . ( $_jupiterx_is_selfclose_markup ? '/' : '' ) . '>';
	}

	// Set and then fire the after action hook.
	$args[0] = $id . ( $_jupiterx_is_selfclose_markup ? '_after_markup' : '_prepend_markup' );
	$output .= call_user_func_array( '_jupiterx_render_action', $args );

	// Reset the global variable to reduce memory usage.
	unset( $GLOBALS['_jupiterx_is_selfclose_markup'] );

	return $output;
}

/**
 * Echo the opening HTML tag's markup.  This function is a wrapper for {@see jupiterx_open_markup()}.  See
 * {@see jupiterx_open_markup()} for more details.
 *
 * @since 1.0.0
 *
 * @param string       $id         A unique string used as a reference. The $id argument may contain sub-hooks(s).
 * @param string|bool  $tag        The HTML tag. When set to false or an empty string, the HTML markup tag will not be
 *                                 built, but both action hooks will fire. If set to null, the function bails out, i.e.
 *                                 the HTML markup tag will not be built and neither action hook fires.
 * @param string|array $attributes Optional. Query string or array of attributes. The array key defines the
 *                                 attribute name and the array value defines the attribute value.
 *
 * @return void
 */
function jupiterx_open_markup_e( $id, $tag, $attributes = array() ) {
	$args = func_get_args();
	echo call_user_func_array( 'jupiterx_open_markup', $args ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- Escaped in jupiterx_open_markup().
}

/**
 * Build the self-closing HTML element's markup.
 *
 * This function is shortcut of {@see jupiterx_open_markup()}. It should be used for self-closing HTML markup such as
 * images or inputs.
 *
 * Note: You can pass additional arguments to the functions that are hooked to <tt>$id</tt>.
 *
 * @since 1.0.0
 *
 * @global bool        $_jupiterx_is_selfclose_markup When true, indicates a self-closing element should be built.
 *
 * @param string       $id         A unique string used as a reference. The $id argument may contain sub-hooks(s).
 * @param string|bool  $tag        The self-closing HTML tag. When set to false or an empty string, the HTML markup tag
 *                                 will not be built, but both action hooks will fire. If set to null, the function
 *                                 bails out, i.e. the HTML markup tag will not be built and neither action hook fires.
 * @param string|array $attributes Optional. Query string or array of attributes. The array key defines the attribute
 *                                 name and the array value defines the attribute value.
 *
 * @return string|void
 */
function jupiterx_selfclose_markup( $id, $tag, $attributes = array() ) {
	global $_jupiterx_is_selfclose_markup;

	$_jupiterx_is_selfclose_markup = true;

	$html = call_user_func_array( 'jupiterx_open_markup', func_get_args() );

	// Reset the global variable to reduce memory usage.
	unset( $GLOBALS['_jupiterx_is_selfclose_markup'] );

	return $html;
}

/**
 * Echo the self-closing HTML element's markup. This function is a wrapper for {@see jupiterx_selfclose_markup()}.  See
 * {@see jupiterx_selfclose_markup()} for more details.
 *
 * @since 1.0.0
 *
 * @param string       $id         A unique string used as a reference. The $id argument may contain sub-hooks(s).
 * @param string|bool  $tag        The self-closing HTML tag. When set to false or an empty string, the HTML markup tag
 *                                 will not be built, but both action hooks will fire. If set to null, the function
 *                                 bails out, i.e. the HTML markup tag will not be built and neither action hook fires.
 * @param string|array $attributes Optional. Query string or array of attributes. The array key defines the attribute
 *                                 name and the array value defines the attribute value.
 *
 * @return void
 */
function jupiterx_selfclose_markup_e( $id, $tag, $attributes = array() ) {
	echo call_user_func_array( 'jupiterx_selfclose_markup', func_get_args() ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- Escaped in jupiterx_open_markup().
}

/**
 * Build the closing HTML element's markup.  This function fires 2 separate hooks:
 *
 *      1. "{id}_append_markup" - which fires first before the closing tag.
 *      2. "{$id}_after_markup" - which fires after the closing tag.
 *
 * Note: You can pass additional arguments to the functions that are hooked to <tt>$id</tt>.
 *
 * @since 1.0.0
 *
 * @param string $id  Identical to the opening markup ID.
 * @param string $tag The HTML tag. When set to false or an empty string, the HTML markup tag will not be built, but
 *                    both action hooks will fire. If set to null, the function bails out, i.e. the HTML markup
 *                    tag will not be built and neither action hook fires.
 *
 * @return string|void
 */
function jupiterx_close_markup( $id, $tag ) {
	$args = func_get_args(); // phpcs:ignore PHPCompatibility.FunctionUse.ArgumentFunctionsReportCurrentValue.Changed

	// If the jupiterx_apply_filters is set to null, bail out.
	if ( null === jupiterx_apply_filters( $id . '_markup', $tag ) ) {
		return;
	}

	// Filter the tag.
	$args[1] = jupiterx_apply_filters( $id . '_markup', $tag );

	// Remove the $tag argument.
	unset( $args[1] );

	// Set and then fire the append action hook.
	$args[0] = $id . '_append_markup';
	$output  = call_user_func_array( '_jupiterx_render_action', $args );

	// Build the closing tag when tag is available.
	if ( $tag ) {
		$output .= '</' . esc_attr( $tag ) . '>';
	}

	// Set and then fire the after action hook.
	$args[0] = $id . '_after_markup';
	$output .= call_user_func_array( '_jupiterx_render_action', $args );

	return $output;
}

/**
 * Echo the closing HTML tag's markup.  This function is a wrapper for {@see jupiterx_close_markup()}.  See
 * {@see jupiterx_close_markup()} for more details.
 *
 * @since 1.0.0
 *
 * @param string $id  Identical to the opening markup ID.
 * @param string $tag The HTML tag. When set to false or an empty string, the HTML markup tag will not be built, but
 *                    both action hooks will fire. If set to null, the function bails out, i.e. the HTML markup
 *                    tag will not be built and neither action hook fires.
 *
 * @return void
 */
function jupiterx_close_markup_e( $id, $tag ) {
	$args = func_get_args();
	echo call_user_func_array( 'jupiterx_close_markup', $args ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- Escaped in jupiterx_close_markup().
}

/**
 * Modify the opening and closing or self-closing HTML tag.
 *
 * @since 1.0.0
 *
 * @param string          $id       The target markup's ID.
 * @param string|callback $markup   The replacement HTML tag. A callback is accepted if conditions need to be
 *                                  applied. If arguments are available, then they are passed to the callback.
 * @param int             $priority Optional. Used to specify the order in which the functions
 *                                  associated with a particular action are executed. Default is 10.
 * @param int             $args     Optional. The number of arguments the callback accepts. Default is 1.
 *
 * @return bool|_JupiterX_Anonymous_Filters
 */
function jupiterx_modify_markup( $id, $markup, $priority = 10, $args = 1 ) {
	return jupiterx_add_filter( $id . '_markup', $markup, $priority, $args );
}

/**
 * Remove the markup.
 *
 * This function will automatically remove the opening and the closing HTML tag. If the markup is self-closing,
 * the HTML tag will be removed accordingly.
 *
 * @since 1.0.0
 *
 * @param string $id             The target markup's ID.
 * @param bool   $remove_actions Optional. When true, the markup including the before and prepend/after hooks will be
 *                               removed. When false, only the HTML element will be removed.
 *
 * @return bool|_JupiterX_Anonymous_Filters
 */
function jupiterx_remove_markup( $id, $remove_actions = false ) {
	return jupiterx_add_filter( $id . '_markup', $remove_actions ? null : false );
}

/**
 * Reset the given markup's tag.  This function will automatically reset the opening and closing HTML tag or
 * self-closing tag to its original value.
 *
 * @since 1.0.0
 *
 * @param string $id The markup ID.
 *
 * @return void
 */
function jupiterx_reset_markup( $id ) {
	remove_all_filters( $id . '_markup' );
	remove_all_filters( preg_replace( '#(\[|\])#', '', $id ) . '_markup' );
}

/**
 * Register the wrap markup with Jupiter using the given markup ID.
 *
 * This function registers an anonymous callback to the following action hooks:
 *
 *    1. `$id_before_markup`:  When this hook fires, {@see jupiterx_open_markup()} is called to build the wrap's
 *        opening HTML markup.
 *    2. `$id_after_markup`: When this hook fires, {@see jupiterx_close_markup()} is called to build the wrap's
 *        closing HTML markup.
 *
 * Note: You can pass additional arguments to the functions that are hooked to <tt>$id</tt>.
 *
 * @since 1.0.0
 *
 * @param string       $id         The wrap markup's ID.
 * @param string       $new_id     A unique string used as a reference. The $id argument may contain sub-hook(s).
 * @param string       $tag        The wrap's HTML tag.
 * @param string|array $attributes Optional. Query string or array of attributes. The array key defines the
 *                                 attribute name and the array value define the attribute value. Setting
 *                                 the array value to '' will display the attribute value as empty
 *                                 (e.g. class=""). Setting it to 'false' will only display
 *                                 the attribute name (e.g. data-example). Setting it to 'null' will not
 *                                 display anything.
 *
 * @return bool
 */
function jupiterx_wrap_markup( $id, $new_id, $tag, $attributes = array() ) {

	if ( ! $tag ) {
		return false;
	}

	$args = func_get_args();
	unset( $args[0] );

	_jupiterx_add_anonymous_action( $id . '_before_markup', array( 'jupiterx_open_markup', $args ), 9999 );

	unset( $args[3] );

	_jupiterx_add_anonymous_action( $id . '_after_markup', array( 'jupiterx_close_markup', $args ), 1 );

	return true;
}

/**
 * Register the wrap inner content's markup with Jupiter using the given markup ID.
 *
 * This function registers an anonymous callback to the following action hooks:
 *
 *    1. `$id_prepend_markup`:  When this hook fires, {@see jupiterx_open_markup()} is called to build the wrap inner
 *        content's HTML markup just after the wrap's opening tag.
 *    2. `$id_append_markup`: When this hook fires, {@see jupiterx_close_markup()} is called to build the wrap inner
 *        content's HTML markup just before the wrap's closing tag.
 *
 * Note: You can pass additional arguments to the functions that are hooked to <tt>$id</tt>.
 *
 * @since 1.0.0
 *
 * @param string       $id         The wrap markup's ID.
 * @param string       $new_id     A unique string used as a reference. The $id argument may contain sub-hook(s).
 * @param string       $tag        The wrap inner content's HTML tag.
 * @param string|array $attributes Optional. Query string or array of attributes. The array key defines the
 *                                 attribute name and the array value define the attribute value. Setting
 *                                 the array value to '' will display the attribute value as empty
 *                                 (e.g. class=""). Setting it to 'false' will only display
 *                                 the attribute name (e.g. data-example). Setting it to 'null' will not
 *                                 display anything.
 *
 * @return bool
 */
function jupiterx_wrap_inner_markup( $id, $new_id, $tag, $attributes = array() ) {

	if ( ! $tag ) {
		return false;
	}

	$args = func_get_args();
	unset( $args[0] );

	_jupiterx_add_anonymous_action( $id . '_prepend_markup', array( 'jupiterx_open_markup', $args ), 1 );

	unset( $args[3] );

	_jupiterx_add_anonymous_action( $id . '_append_markup', array( 'jupiterx_close_markup', $args ), 9999 );

	return true;
}

/**
 * Convert an array of attributes into a properly formatted HTML string.
 *
 * The attributes are registered in Jupiter via the given ID.  Using this ID, we can hook into the filter, i.e.
 * "$id_attributes", to modify, replace, extend, or remove one or more of the registered attributes.
 *
 * Since this function uses {@see jupiterx_apply_filters()}, the $id argument may contain sub-hook(s).
 *
 * Note: You can pass additional arguments to the functions that are hooked to <tt>$id</tt>.
 *
 * @since 1.0.0
 *
 * @param string       $id         A unique string used as a reference. The $id argument may contain sub-hook(s).
 * @param string|array $attributes Optional. Query string or array of attributes. The array key defines the
 *                                 attribute name and the array value define the attribute value. Setting
 *                                 the array value to '' will display the attribute value as empty
 *                                 (e.g. class=""). Setting it to 'false' will only display
 *                                 the attribute name (e.g. data-example). Setting it to 'null' will not
 *                                 display anything.
 *
 * @return string The HTML attributes.
 */
function jupiterx_add_attributes( $id, $attributes = array() ) {
	$args    = func_get_args();
	$args[0] = $id . '_attributes';

	if ( empty( $args[1] ) ) {
		$args[1] = array();
	}

	$args[1] = wp_parse_args( $args[1] );

	$attributes = call_user_func_array( 'jupiterx_apply_filters', $args );

	return jupiterx_esc_attributes( $attributes );
}

/**
 * Reset markup attributes.
 *
 * This function will reset the targeted markup attributes to their original values. It must be called before
 * the targeted markup is called.
 *
 * The "data-markup-id" is added as a HTML attribute if the development mode is enabled. This makes it very
 * easy to find the content ID when inspecting an element in a web browser.
 *
 * @since 1.0.0
 *
 * @param string $id The markup ID.
 *
 * @return void
 */
function jupiterx_reset_attributes( $id ) {
	remove_all_filters( $id . '_attributes' );
	remove_all_filters( preg_replace( '#(\[|\])#', '', $id ) . '_attributes' );
}

/**
 * Add a value to an existing attribute or add a new attribute.
 *
 * This function must be called before the targeted markup is called.
 *
 * The "data-markup-id" is added as a HTML attribute if the development mode is enabled. This makes it very
 * easy to find the content ID when inspecting an element in a web browser.
 *
 * @since 1.0.0
 *
 * @param string $id        The markup ID.
 * @param string $attribute Name of the HTML attribute.
 * @param string $value     Value of the HTML attribute. If set to '' will display the attribute value as empty
 *                          (e.g. class=""). Setting it to 'false' will only display the attribute name
 *                          (e.g. data-example). Setting it to 'null' will not display anything.
 *
 * @return _JupiterX_Attribute
 */
function jupiterx_add_attribute( $id, $attribute, $value ) {
	$attribute = new _JupiterX_Attribute( $id, $attribute, $value );

	return $attribute->init( 'add' );
}

/**
 * Replace the attribute's value. If the attribute does not exist, it is added with the new value.
 *
 * This function must be called before the targeted markup is called.
 *
 * The "data-markup-id" is added as a HTML attribute if the development mode is enabled. This makes it very
 * easy to find the content ID when inspecting an element in a web browser.
 *
 * @since 1.0.0
 *
 * @param string      $id        The markup ID.
 * @param string      $attribute Name of the HTML attribute to target.
 * @param string      $value     Value of the HTML attribute to be replaced. Setting it to an empty (i.e. empty string,
 *                               false, or null) replaces all of the values for this attribute.
 * @param string|null $new_value Optional. Replacement (new) value of the HTML attribute. Setting it to an empty string
 *                               ('') or null will remove the $value (e.g. class=""). Setting it to 'false', the
 *                               browser will display only the attribute name
 *                               (e.g. data-example).
 *
 * @return _JupiterX_Attribute
 */
function jupiterx_replace_attribute( $id, $attribute, $value, $new_value = null ) {
	$attribute = new _JupiterX_Attribute( $id, $attribute, $value, $new_value );

	return $attribute->init( 'replace' );
}

/**
 * Remove a specific value from the attribute or remove the entire attribute.
 *
 * This function must be called before the targeted markup is called.
 *
 * The "data-markup-id" is added as a HTML attribute if the development mode is enabled. This makes it very
 * easy to find the content ID when inspecting an element in a web browser.
 *
 * @since 1.0.0
 *
 * @param string      $id        The markup ID.
 * @param string      $attribute Name of the HTML attribute to target.
 * @param string|null $value     Optional. The attribute value to remove. Set it to 'false' or null to completely
 *                               remove the attribute.
 *
 * @return _JupiterX_Attribute
 */
function jupiterx_remove_attribute( $id, $attribute, $value = null ) {
	$attribute = new _JupiterX_Attribute( $id, $attribute, $value, '' );

	return $attribute->init( 'remove' );
}

/**
 * Check if development mode is enabled taking in consideration legacy constant.
 *
 * @since  1.0.0
 * @ignore
 * @access private
 *
 * @return bool
 */
function _jupiterx_is_html_dev_mode() {

	if ( defined( 'JUPITERX_HTML_DEV_MODE' ) ) {
		return (bool) JUPITERX_HTML_DEV_MODE;
	}

	return (bool) jupiterx_get_option( 'dev_mode', false );
}
