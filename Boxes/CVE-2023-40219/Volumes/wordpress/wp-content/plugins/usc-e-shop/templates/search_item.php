<?php
/**
 * Search item page template
 *
 * @package Welcart
 */

$uscpaged = isset( $_REQUEST['paged'] ) ? absint( wp_unslash( $_REQUEST['paged'] ) ) : 1;

$html = '<script type="text/javascript">
function usces_nextpage() {
	document.getElementById(\'usces_paged\').value = ' . ( $uscpaged + 1 ) . ';
	document.searchindetail.submit();
}
function usces_prepage() {
	document.getElementById(\'usces_paged\').value = ' . ( $uscpaged - 1 ) . ';
	document.searchindetail.submit();
}
function newsubmit() {
	document.getElementById(\'usces_paged\').value = 1;
}
</script>';

$html .= '<div id="searchbox">';

if ( isset( $_REQUEST['usces_search'] ) ) {

	$catresult    = usces_search_categories();
	$search_query = array(
		'category__and'  => $catresult,
		'posts_per_page' => 10,
		'paged'          => $uscpaged,
	);
	$search_query = apply_filters( 'usces_filter_search_query', $search_query );

	$my_query = new WP_Query( $search_query );

	$html .= '<div class="title">' . esc_html__( 'Search results', 'usces' ) . '&nbsp;&nbsp;' . number_format( $my_query->found_posts ) . esc_html__( 'cases', 'usces' ) . '</div>';

	if ( $my_query->have_posts() ) {

		$html .= apply_filters( 'usces_filter_search_result_pre', null, $my_query );

		$html .= '<div class="navigation clearfix">';
		if ( 1 < $uscpaged ) {
			$html .= '<a style="float:left; cursor:pointer;" onclick="usces_prepage();">' . esc_html__( '&laquo; Previous article', 'usces' ) . '</a>';
		}
		if ( $uscpaged < $my_query->max_num_pages ) {
			$html .= '<a style="float:right; cursor:pointer;" onclick="usces_nextpage();">' . esc_html__( 'Next article &raquo;', 'usces' ) . '</a>';
		}
		$html .= '</div>';

		$itemhtml = '<div class="searchitems">';
		while ( $my_query->have_posts() ) {
			$my_query->the_post();
			usces_the_item();

			$itemhtml .= '<div class="itemlist clearfix"><div class="loopimg">
				<a href="' . get_permalink( $post->ID ) . '">' . usces_the_itemImage( 0, 100, 100, $post, 'return' ) . '</a>
				</div>
				<div class="loopexp">
					<div class="itemtitle"><a href="' . get_permalink( $post->ID ) . '">' . esc_html( $post->post_title ) . '</a></div>
					<div class="field">' . $post->post_content . '</div>
				</div>
				</div>';
		}
		$itemhtml .= '</div><!-- searchitems -->';
		$html     .= apply_filters( 'usces_filter_search_result', $itemhtml, $my_query );

		$html .= '<div class="navigation clearfix">';
		if ( 1 < $uscpaged ) {
			$html .= '<a style="float:left; cursor:pointer;" onclick="usces_prepage();">' . esc_html__( '&laquo; Previous article', 'usces' ) . '</a>';
		}
		if ( $uscpaged < $my_query->max_num_pages ) {
			$html .= '<a style="float:right; cursor:pointer;" onclick="usces_nextpage();">' . esc_html__( 'Next article &raquo;', 'usces' ) . '</a>';
		}
		$html .= '</div>';

		wp_reset_postdata();

	} else {

		$html .= '<div class="searchitems">';
		$html .= '<p>' . esc_html__( 'The article was not found.', 'usces' ) . '</p>';
		$html .= '</div><!-- searchitems -->';
	}
}
$html .= '<form name="searchindetail" action="' . esc_url( USCES_CART_URL ) . $this->delim . 'usces_page=search_item" method="post">
<div class="field">
<label class="outlabel">' . esc_html__( 'Categories: AND Search', 'usces' ) . '</label>' . usces_categories_checkbox( 'return' ) . '
</div>';

$usces_search_button = '<input name="usces_search_button" class="usces_search_button" type="submit" value="' . esc_html__( 'Search', 'usces' ) . '" onclick="newsubmit()" />';
$html               .= apply_filters( 'usces_filter_search_button', $usces_search_button );

$html .= '<input name="paged" id="usces_paged" type="hidden" value="' . esc_attr( $uscpaged ) . '" />
<input name="usces_search" type="hidden" />
</form>';
$html .= '</div><!-- searchbox -->';
