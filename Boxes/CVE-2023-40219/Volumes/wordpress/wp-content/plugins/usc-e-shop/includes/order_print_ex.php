<?php
/**
 * Output PDF.
 * (For reduced tax rate)
 *
 * @package Welcart
 */

global $usces;

require_once USCES_PLUGIN_DIR . '/pdf/tcpdf/tcpdf.php';
require_once USCES_PLUGIN_DIR . '/classes/orderData.class.php';

define( 'USCES_PDF_FONT_FILE_NAME', 'msgothic.php' );

$pdf        = new TCPDF( PDF_PAGE_ORIENTATION, PDF_UNIT, 'B5', true, 'UTF-8' );
$usces_pdfo = new orderDataObject( $_REQUEST['order_id'] );
$usces_pdfo = apply_filters( 'usces_filter_pdf_order_data', $usces_pdfo );
do_action( 'usces_action_order_print_start' );

usces_pdf_out( $pdf, $usces_pdfo );
die();

/**
 * Convert encoding
 *
 * @param string $str Text.
 * @return string
 */
function usces_conv_euc( $str ) {
	$str = apply_filters( 'usces_filter_pdf_conv_enc', $str );
	return $str;
}

/**
 * Output PDF
 *
 * @param object $pdf TCPDF.
 * @param array  $data Order data.
 */
function usces_pdf_out( $pdf, $data ) {
	global $usces;
	$usces_tax = Welcart_Tax::get_instance();

	$type = ( isset( $_REQUEST['type'] ) ) ? wp_unslash( $_REQUEST['type'] ) : '';

	$pdf->setPrintHeader( false );
	$pdf->setPrintFooter( false );

	/* Template file */
	if ( isset( $usces->options['print_size'] ) && 'A4' === $usces->options['print_size'] ) {
		$tplfile = USCES_PLUGIN_DIR . '/images/orderform_A4.pdf';
	} else {
		$tplfile = USCES_PLUGIN_DIR . '/images/orderform_B5.pdf';
	}
	$tplfile = apply_filters( 'usces_filter_pdf_template', $tplfile );

	$pdf->SetLeftMargin( 0 );
	$pdf->SetTopMargin( 0 );
	$pdf->addPage();

	/* Add font */
	$font_ob        = new TCPDF_FONTS();
	$font_file_name = apply_filters( 'usces_filter_pdf_font_file_name', USCES_PDF_FONT_FILE_NAME ); // Set font file and assign font name. This method is new.
	$font           = $font_ob->addTTFfont( USCES_PLUGIN_DIR . '/pdf/tcpdf/fonts/' . $font_file_name );
	$font           = apply_filters( 'usces_filter_pdf_cfont', $font, $font_ob ); // custom addTTFfont.

	/* PDF type */
	$creator_name = apply_filters( 'usces_filter_pdf_creator_name', html_entity_decode( get_option( 'blogname' ), ENT_QUOTES ) );
	$author_name  = apply_filters( 'usces_filter_pdf_author_name', html_entity_decode( $usces->options['company_name'] ) );
	$pdf->SetCreator( $creator_name );
	$pdf->SetAuthor( $author_name );
	switch ( $type ) {
		case 'mitumori':
			$pdf->SetTitle( 'estimate' );
			$filename = 'estimate_' . usces_get_deco_order_id( $data->order['ID'] ) . '.pdf';
			break;

		case 'nohin':
			$pdf->SetTitle( 'invoice' );
			$filename = 'invoice_' . usces_get_deco_order_id( $data->order['ID'] ) . '.pdf';
			break;

		case 'receipt':
			$pdf->SetTitle( 'receipt' );
			$filename = 'receipt_' . usces_get_deco_order_id( $data->order['ID'] ) . '.pdf';
			break;

		case 'bill':
			$pdf->SetTitle( 'bill' );
			$filename = 'bill_' . usces_get_deco_order_id( $data->order['ID'] ) . '.pdf';
			break;

		default:
			$title = apply_filters( 'usces_filter_pdf_title', $type );
			$pdf->SetTitle( $title );
			$filename = trim( $type ) . '_' . usces_get_deco_order_id( $data->order['ID'] ) . '.pdf';
	}
	$filename = apply_filters( 'usces_filter_pdf_filename', $filename, $_REQUEST['type'], $data );

	$pdf->SetDisplayMode( 'real', 'continuous' );

	$pdf->getAliasNbPages();

	$pdf->SetAutoPageBreak( true, 5 );

	$pdf->SetFillColor( 255, 255, 255 );

	/* Initial page number */
	$page = 1;

	/* Output header */
	usces_pdfSetHeader( $pdf, $data, $page, $font );

	$border = 0;

	$pdf->SetLeftMargin( 19.8 );
	$x    = 15.8;
	$y    = 101;
	$onep = apply_filters( 'usces_filter_pdf_page_height', 185 );
	$pdf->SetXY( $x, $y );
	$next_y    = $y;
	$line_y    = $next_y;
	$cart      = usces_get_ordercartdata( $data->order['ID'] );
	$cart      = apply_filters( 'usces_filter_pdf_cart_data', $cart, $data->order['ID'] );
	$materials = array(
		'total_items_price' => $data->order['item_total_price'],
		'discount'          => $data->order['discount'],
		'shipping_charge'   => $data->order['shipping_charge'],
		'cod_fee'           => $data->order['cod_fee'],
		'use_point'         => $data->order['usedpoint'],
		'carts'             => $cart,
		'condition'         => $data->condition,
		'order_id'          => $data->order['ID'],
	);
	$usces_tax->get_order_tax( $materials );

	$condition   = $data->condition;
	$tax_display = ( isset( $condition['tax_display'] ) ) ? $condition['tax_display'] : usces_get_tax_display();
	$tax_target  = ( isset( $condition['tax_target'] ) ) ? $condition['tax_target'] : $usces->options['tax_target'];
	$tax_mode    = ( isset( $condition['tax_mode'] ) ) ? $condition['tax_mode'] : $usces->options['tax_mode'];

	$fontsizes                               = array(
		'item_name'         => 8,
		'item_name_receipt' => 8,
		'details'           => 8,
		'quantity'          => 7,
		'unit'              => 7,
		'unitprice'         => 7,
		'row_price'         => 7,
	);
	list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['item_name'] );
	$index                                   = 0;

	$count_cart_standard = count( $usces_tax->cart_standard );
	$count_cart_reduced  = count( $usces_tax->cart_reduced );
	$count_cart          = $count_cart_standard + $count_cart_reduced;

	if ( 0 <= $usces_tax->subtotal_standard && 0 < $count_cart_standard ) {

		foreach ( $usces_tax->cart_standard as $cart_row ) {
			if ( $onep < $next_y ) { // Page break.

				$pdf->addPage();
				$page++;

				/* Output header */
				usces_pdfSetHeader( $pdf, $data, $page, $font );

				$x = 15.8;
				$y = 101;
				$pdf->SetXY( $x, $y );
				$next_y = $y;
			}

			/* Output detail */
			$next_y = usces_pdfSetDetail( $pdf, $data, $page, $font, $x, $y, $onep, $next_y, $border, $index, $cart, $cart_row, $fontsizes );
			$index++;
		}

		if ( $onep < $next_y ) { // Page break.

			$pdf->addPage();
			$page++;

			/* Output header */
			usces_pdfSetHeader( $pdf, $data, $page, $font );

			$x = 15.8;
			$y = 101;
			$pdf->SetXY( $x, $y );
			$next_y = $y;
		}

		if ( 'activate' === $tax_display && 'products' === $tax_target ) {
			$subtotal_label     = sprintf( __( 'Applies to %s%%', 'usces' ), $usces_tax->tax_rate_standard );
			$subtotal_tax_label = sprintf( __( '%s%% consumption tax', 'usces' ), $usces_tax->tax_rate_standard );
			if ( 'include' === $tax_mode ) {
				$subtotal_tax_standard = '(' . $usces->get_currency( $usces_tax->tax_standard ) . ')';
			} else {
				$subtotal_tax_standard = $usces->get_currency( $usces_tax->tax_standard );
			}

			$line_left = 103.5;
			// $line_left = 15.4;
			$line_right = 165.5;

			$line_y = $next_y - 1;
			$pdf->SetLineWidth( 0.04 );
			$pdf->Line( $line_left, $line_y, $line_right, $line_y );

			$line_y += 0.4;
			$pdf->SetXY( 104.3, $line_y );
			$pdf->MultiCell( 37.7, $lineheight, usces_conv_euc( $subtotal_label ), 0, 'C' );
			$pdf->SetXY( 142.9, $line_y );
			$pdf->MultiCell( 22.6, $lineheight, usces_conv_euc( $usces->get_currency( $usces_tax->subtotal_standard + $usces_tax->discount_standard ) ), 0, 'R' );

			$line_y += 3.8;
			$pdf->SetLineWidth( 0.04 );
			$pdf->Line( $line_left, $line_y, $line_right, $line_y );

			$line_y += 0.4;
			$pdf->SetXY( 104.3, $line_y );
			$pdf->MultiCell( 37.7, $lineheight, usces_conv_euc( $subtotal_tax_label ), 0, 'C' );
			$pdf->SetXY( 142.9, $line_y );
			$pdf->MultiCell( 22.6, $lineheight, usces_conv_euc( $subtotal_tax_standard ), 0, 'R' );

			$line_y += 3.8;
			$pdf->SetLineWidth( 0.1 );
			$pdf->Line( 15.4, $line_y, $line_right, $line_y );

			$next_y = $pdf->GetY() + 2;
		}
	}

	if ( 0 <= $usces_tax->subtotal_reduced && 0 < $count_cart_reduced ) {

		foreach ( $usces_tax->cart_reduced as $cart_row ) {
			if ( $onep < $next_y ) { // Page break.

				$pdf->addPage();
				$page++;

				/* Output header */
				usces_pdfSetHeader( $pdf, $data, $page, $font );

				$x = 15.8;
				$y = 101;
				$pdf->SetXY( $x, $y );
				$next_y = $y;
			}

			/* Output detail */
			$next_y = usces_pdfSetDetail( $pdf, $data, $page, $font, $x, $y, $onep, $next_y, $border, $index, $cart, $cart_row, $fontsizes, $usces_tax->reduced_taxrate_mark );
			$index++;
		}

		if ( $onep < $next_y ) { // Page break.

			$pdf->addPage();
			$page++;

			/* Output header */
			usces_pdfSetHeader( $pdf, $data, $page, $font );

			$x = 15.8;
			$y = 101;
			$pdf->SetXY( $x, $y );
			$next_y = $y;
		}

		if ( 'activate' === $tax_display && 'products' === $tax_target ) {
			$subtotal_label     = sprintf( __( 'Applies to %s%%', 'usces' ), $usces_tax->tax_rate_reduced );
			$subtotal_tax_label = sprintf( __( '%s%% consumption tax', 'usces' ), $usces_tax->tax_rate_reduced );
			if ( 'include' === $tax_mode ) {
				$subtotal_tax_reduced = '(' . $usces->get_currency( $usces_tax->tax_reduced ) . ')';
			} else {
				$subtotal_tax_reduced = $usces->get_currency( $usces_tax->tax_reduced );
			}

			$line_left = 103.5;
			// $line_left = 15.4;
			$line_right = 165.5;

			$line_y = $next_y;
			$pdf->SetLineWidth( 0.04 );
			$pdf->Line( $line_left, $line_y, $line_right, $line_y );

			$line_y += 0.4;
			$pdf->SetXY( 104.3, $line_y );
			$pdf->MultiCell( 37.7, $lineheight, usces_conv_euc( $subtotal_label ), 0, 'C' );
			$pdf->SetXY( 142.9, $line_y );
			$pdf->MultiCell( 22.6, $lineheight, usces_conv_euc( $usces->get_currency( $usces_tax->subtotal_reduced + $usces_tax->discount_reduced ) ), 0, 'R' );

			$line_y += 3.8;
			$pdf->SetLineWidth( 0.04 );
			$pdf->Line( $line_left, $line_y, $line_right, $line_y );

			$line_y += 0.4;
			$pdf->SetXY( 104.3, $line_y );
			$pdf->MultiCell( 37.7, $lineheight, usces_conv_euc( $subtotal_tax_label ), 0, 'C' );
			$pdf->SetXY( 142.9, $line_y );
			$pdf->MultiCell( 22.6, $lineheight, usces_conv_euc( $subtotal_tax_reduced ), 0, 'R' );

			// $line_y += 3.8;
			// $pdf->SetLineWidth( 0.04 );
			// $pdf->Line( $line_left, $line_y, $line_right, $line_y );
		}
	}

	if ( $onep < $next_y ) { // Page break.

		$pdf->addPage();
		$page++;

		/* Output header */
		usces_pdfSetHeader( $pdf, $data, $page, $font );

		$x = 15.8;
		$y = 101;
		$pdf->SetXY( $x, $y );
		$next_y = $y;
	}

	/* Output footer */
	usces_pdfSetFooter( $pdf, $data, $font, $usces_tax );

	@ob_end_clean();

	/* Output */
	$pdf->Output( $filename, 'I' );
}

/**
 * Header
 *
 * @param object $pdf  TCPDF.
 * @param object $data Order data.
 * @param int    $page Page.
 * @param object $font Font.
 * @return void
 */
function usces_pdfSetHeader( $pdf, $data, $page, $font ) {
	global $usces;

	$type = ( isset( $_REQUEST['type'] ) ) ? wp_unslash( $_REQUEST['type'] ) : '';

	$fontsizes = array(
		'numbering_label'  => 9,
		'title'            => 15,
		'date'             => 9,
		'page_no'          => 13,
		'customer_company' => 12,
		'customer_attn'    => 8,
		'customer_address' => 8,
		'total_price'      => 20,
		'message'          => 9,
		'statement_label'  => 10,
		'delivery_label'   => 8,
		'delivery_address' => 6,
		'order_date'       => 10,
		'publisher'        => 9,
		'company_name'     => 8,
		'details_label'    => 8,
	);
	$fontsizes = apply_filters( 'useces_filter_order_pdfheader_fontsize', $fontsizes, $data );
	$fontsizes = apply_filters( 'usces_filter_pdf_header_fontsize', $fontsizes, $data ); // alias.

	switch ( $type ) {
		case 'mitumori':
			$title          = apply_filters( 'usces_filter_pdf_estimate_title', __( 'Quotation', 'usces' ), $data );
			$message        = sprintf( __( "Thank you for using '%s'. We estimate as follows.", 'usces' ), apply_filters( 'usces_filter_publisher', html_entity_decode( get_option( 'blogname' ), ENT_QUOTES ) ) );
			$message        = apply_filters( 'usces_filter_pdf_estimate_message', $message, $data );
			$juchubi        = apply_filters( 'usces_filter_pdf_estimate_validdays', __( 'Valid:7days', 'usces' ), $data );
			$siharai        = ' ';
			$sign_image     = apply_filters( 'usces_filter_pdf_estimate_sign', null );
			$effective_date = date_i18n( __( 'M j, Y', 'usces' ), strtotime( $data->order['date'] ) );
			break;

		case 'nohin':
			$title      = apply_filters( 'usces_filter_pdf_invoice_title', __( 'Delivery Statement', 'usces' ), $data );
			$message    = sprintf( __( "Thank you for using '%s'. We will deliver as follows.", 'usces' ), apply_filters( 'usces_filter_publisher', html_entity_decode( get_option( 'blogname' ), ENT_QUOTES ) ) );
			$message    = apply_filters( 'usces_filter_pdf_invoice_message', $message, $data );
			$juchubi    = __( 'date of your order', 'usces' ) . ' : ' . date_i18n( __( 'M j, Y', 'usces' ), strtotime( $data->order['date'] ) );
			$siharai    = __( 'your payment method', 'usces' ) . ' : ' . apply_filters( 'usces_filter_pdf_payment_name', $data->order['payment_name'], $data );
			$sign_image = apply_filters( 'usces_filter_pdf_invoice_sign', null );

			if ( ! empty( $data->order['delidue_date'] ) && '#none#' !== $data->order['delidue_date'] ) {
				$effective_date = date_i18n( __( 'M j, Y', 'usces' ), strtotime( $data->order['delidue_date'] ) );
			} else {
				if ( empty( $data->order['modified'] ) ) {
					$effective_date = date_i18n( __( 'M j, Y', 'usces' ), current_time( 'timestamp', 0 ) );
				} else {
					$effective_date = date_i18n( __( 'M j, Y', 'usces' ), strtotime( $data->order['modified'] ) );
				}
			}
			break;

		case 'receipt':
			$title      = apply_filters( 'usces_filter_pdf_receipt_title', __( 'Receipt', 'usces' ), $data );
			$message    = apply_filters( 'usces_filter_pdf_receipt_message', __( 'Your payment has been received.', 'usces' ), $data );
			$juchubi    = __( 'date of your order', 'usces' ) . ' : ' . date_i18n( __( 'M j, Y', 'usces' ), strtotime( $data->order['date'] ) );
			$siharai    = __( 'your payment method', 'usces' ) . ' : ' . apply_filters( 'usces_filter_pdf_payment_name', $data->order['payment_name'], $data );
			$sign_image = apply_filters( 'usces_filter_pdf_receipt_sign', null );

			$payment = $usces->getPayments( $data->order['payment_name'] );
			if ( 'COD' === $payment['settlement'] ) {
				if ( '' === $data->order['modified'] ) {
					$receipted_date = current_time( 'Y-m-d' );
				} else {
					$receipted_date = $data->order['modified'];
				}
			} else {
				$receipted_date = $usces->get_order_meta_value( 'receipted_date', $data->order['ID'] );
			}

			if ( empty( $receipted_date ) ) {
				$effective_date = date_i18n( __( 'M j, Y', 'usces' ), current_time( 'timestamp', 0 ) );
			} else {
				$effective_date = date_i18n( __( 'M j, Y', 'usces' ), strtotime( $receipted_date ) );
			}
			break;

		case 'bill':
			$title          = apply_filters( 'usces_filter_pdf_bill_title', __( 'Invoice', 'usces' ), $data );
			$message        = apply_filters( 'usces_filter_pdf_bill_message', __( 'Please remit payment at your earliest convenience.', 'usces' ), $data );
			$juchubi        = __( 'date of your order', 'usces' ) . ' : ' . date_i18n( __( 'M j, Y', 'usces' ), strtotime( $data->order['date'] ) );
			$siharai        = __( 'your payment method', 'usces' ) . ' : ' . apply_filters( 'usces_filter_pdf_payment_name', $data->order['payment_name'], $data );
			$sign_image     = apply_filters( 'usces_filter_pdf_bill_sign', null );
			$effective_date = date_i18n( __( 'M j, Y', 'usces' ), current_time( 'timestamp', 0 ) );
			break;
	}
	$effective_date  = apply_filters( 'usces_filter_pdf_effective_date', $effective_date, $type, $data );
	$numbering_label = apply_filters( 'usces_filter_pdf_numbering_label', 'No.', $type, $data );
	$order_number    = apply_filters( 'usces_filter_pdf_order_number', usces_get_deco_order_id( $data->order['ID'] ), $type, $data );
	$juchubi         = apply_filters( 'usces_filter_pdf_purchase_date', $juchubi, $type, $data );

	$pdf->SetLineWidth( 0.4 );
	$pdf->Line( 65, 23, 110, 23 );
	$pdf->SetLineWidth( 0.1 );
	$pdf->Line( 124, 19, 167, 19 );
	list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['numbering_label'] );
	$pdf->SetFont( $font, '', $fontsize );
	$pdf->SetXY( 125, 15.0 );
	$pdf->Write( $lineheight, $numbering_label );

	/* Title */
	list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['title'] );
	$pdf->SetFont( $font, '', $fontsize );
	$pdf->SetXY( 63, 16 );
	$pdf->MultiCell( 50, $lineheight, usces_conv_euc( $title ), 0, 'C' );

	/* Date */
	list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['date'] );
	$pdf->SetFont( $font, '', $fontsize );
	$pdf->SetXY( 64, 24.2 );
	$pdf->MultiCell( 45.5, $lineheight, usces_conv_euc( $effective_date ), 0, 'C' );

	/* Order No. */
	$pdf->SetXY( 131, 15 );
	$pdf->MultiCell( 36, $lineheight, $order_number, 0, 'R' );

	/* Page No. */
	list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['page_no'] );
	$pdf->SetFont( $font, '', $fontsize );
	$pdf->SetXY( 15.5, 15.4 );
	$pdf->Cell( 20, 7, ' ' . $page . '/ ' . $pdf->getAliasNbPages(), 1 );

	$width    = 80;
	$leftside = 15;
	$pdf->SetLeftMargin( $leftside );

	$person_honor  = ( 'JP' === $usces->options['system']['currency'] ) ? ' 様' : '';
	$company_honor = ( 'JP' === $usces->options['system']['currency'] ) ? '御中' : '';
	$currency_post = ( 'JP' === $usces->options['system']['currency'] ) ? '-' : '';

	if ( 'receipt' === $type ) {
		$top = 40;

		$company                                 = usces_get_pdf_company( $data->order['ID'], 'customer' );
		$company                                 = apply_filters( 'usces_filter_pdf_customer_company', $company, $data->order['ID'] );
		list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['customer_company'] );
		$pdf->SetFont( $font, '', $fontsize );
		$pdf->SetXY( $leftside, $top );

		if ( empty( $company ) ) {
			$pdf->MultiCell( $width, $lineheight, usces_conv_euc( usces_get_pdf_name( $data ) ), 0, 'L' );
			$x = $leftside + $width;
			$y = $pdf->GetY() - $lineheight;
			$pdf->SetXY( $x, $y );
			$pdf->Write( $lineheight, usces_conv_euc( apply_filters( 'usces_filters_pdf_person_honor', $person_honor ) ) );
			$y = $pdf->GetY() + $lineheight + $linetop + 1;
			$pdf->SetLineWidth( 0.1 );
			$pdf->Line( $leftside, $y, $leftside + $width + 7, $y );
		} else {
			$pdf->MultiCell( $width, $lineheight, usces_conv_euc( $company ), 0, 'L' );
			$x = $leftside + $width;
			$y = $pdf->GetY() - $lineheight;
			$pdf->SetXY( $x, $y );
			$pdf->Write( $lineheight, usces_conv_euc( apply_filters( 'usces_filters_pdf_company_honor', $company_honor ) ) );
			$y = $pdf->GetY() + $lineheight + $linetop;
			$pdf->SetLineWidth( 0.1 );
			$pdf->Line( $leftside, $y, $leftside + $width + 7, $y );
			$y                                       = $pdf->GetY() + $lineheight + $linetop + 1;
			list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['customer_attn'] );
			$pdf->SetFont( $font, '', $fontsize );
			$pdf->SetXY( $leftside, $y );
			$pdf->MultiCell( $width, $lineheight, usces_conv_euc( __( 'Attn', 'usces' ) . ' : ' . usces_conv_euc( usces_get_pdf_name( $data ) ) . apply_filters( 'usces_filters_pdf_person_honor', $person_honor ) ), 0, 'L' );
			$y = $pdf->GetY() + $linetop + 1;
		}

		/* Total */
		$y                                       = $pdf->GetY() + $lineheight + 7;
		list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['total_price'] );
		$pdf->SetFont( $font, '', $fontsize );
		$pdf->SetXY( $leftside + 2, $y );
		$pdf->MultiCell( $width, $lineheight + 2, usces_conv_euc( $usces->get_currency( $data->order['total_full_price'], true, false ) . apply_filters( 'usces_filters_pdf_currency_post', $currency_post ) ), 1, 'C' );

		/* Message */
		$y                                       = $pdf->GetY() + $lineheight;
		list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['message'] );
		$pdf->SetFont( $font, '', $fontsize );
		$pdf->SetXY( $leftside, $y );
		$pdf->MultiCell( $width + 70, $lineheight, usces_conv_euc( $message ), 0, 'L' );

		/* Label */
		list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['statement_label'] );
		$pdf->SetFont( $font, '', $fontsize );
		$y = 89;
		$pdf->SetXY( $leftside, $y );
		$pdf->MultiCell( 75, $lineheight, usces_conv_euc( __( 'Statement', 'usces' ) ), 0, 'L' );

		/* Payment method */
		$pdf->SetXY( $leftside + 68, $y );
		$pdf->Cell( 75, $lineheight, usces_conv_euc( $siharai ), 0, 1, 'L' );

	} elseif ( 'nohin' === $type ) {
		/* 「配送先を宛名とする」 */
		if ( 1 === (int) $usces->options['system']['pdf_delivery'] ) {
			$top = 30;

			/* 配送先情報が揃っていない場合、購入者情報を表示 */
			if ( 0 === strlen( $data->deliveri['name1'] ) || 0 === strlen( $data->deliveri['address1'] ) || 0 === strlen( $data->deliveri['address2'] ) ) {
				$company                                 = usces_get_pdf_company( $data->order['ID'], 'customer' );
				$company                                 = apply_filters( 'usces_filter_pdf_customer_company', $company, $data->order['ID'] );
				list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['customer_company'] );
				$pdf->SetFont( $font, '', $fontsize );
				$pdf->SetXY( $leftside, $top );

				if ( empty( $company ) ) {
					$pdf->MultiCell( $width, $lineheight, usces_conv_euc( usces_get_pdf_name( $data ) ), 0, 'L' );
					$x = $leftside + $width;
					$y = $pdf->GetY() - $lineheight;
					$pdf->SetXY( $x, $y );
					$pdf->Write( $lineheight, usces_conv_euc( apply_filters( 'usces_filters_pdf_person_honor', $person_honor ) ) );
					$y = $pdf->GetY() + $lineheight + $linetop + 2;
				} else {
					$pdf->MultiCell( $width, $lineheight, usces_conv_euc( $company ), 0, 'L' );
					$x = $leftside + $width;
					$y = $pdf->GetY() - $lineheight;
					$pdf->SetXY( $x, $y );
					$pdf->Write( $lineheight, usces_conv_euc( apply_filters( 'usces_filters_pdf_company_honor', $company_honor ) ) );
					$y                                       = $pdf->GetY() + $lineheight + $linetop;
					list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['customer_attn'] );
					$pdf->SetFont( $font, '', $fontsize );
					$pdf->SetXY( $leftside, $y );
					$pdf->MultiCell( $width, $lineheight, usces_conv_euc( __( 'Attn', 'usces' ) . ' : ' . usces_conv_euc( usces_get_pdf_name( $data ) ) . apply_filters( 'usces_filters_pdf_person_honor', $person_honor ) ), 0, 'L' );
					$y = $pdf->GetY() + $linetop + 2;
				}

				/* Address */
				list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['customer_address'] );
				$pdf->SetFont( $font, '', $fontsize );

				usces_get_pdf_address( $pdf, $data, $y, $linetop, $leftside, $width, $lineheight );

				if ( ! empty( $data->customer['tel'] ) ) {
					$pdf->MultiCell( $width, $lineheight, usces_conv_euc( 'TEL ' . $data->customer['tel'] ), 0, 'L' );
				}
				if ( ! empty( $data->customer['fax'] ) ) {
					$y = $pdf->GetY() + $linetop;
					$pdf->SetXY( $leftside, $y );
					$pdf->MultiCell( $width, $lineheight, usces_conv_euc( 'FAX ' . $data->customer['fax'] ), 0, 'L' );
				}

			} else {
				$delivery_company                        = usces_get_pdf_company( $data->order['ID'], 'delivery' );
				list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['customer_company'] );
				$pdf->SetFont( $font, '', $fontsize );
				$pdf->SetXY( $leftside, $top );

				if ( empty( $delivery_company ) ) {
					$pdf->MultiCell( $width, $lineheight, usces_conv_euc( usces_get_pdf_shipping_name( $data ) ), 0, 'L' );
					$x = $leftside + $width;
					$y = $pdf->GetY() - $lineheight;
					$pdf->SetXY( $x, $y );
					$pdf->Write( $lineheight, usces_conv_euc( apply_filters( 'usces_filters_pdf_person_honor', $person_honor ) ) );
					$y = $pdf->GetY() + $lineheight + $linetop + 2;
				} else {
					$pdf->MultiCell( $width, $lineheight, usces_conv_euc( $delivery_company ), 0, 'L' );
					$x = $leftside + $width;
					$y = $pdf->GetY() - $lineheight;
					$pdf->SetXY( $x, $y );
					$pdf->Write( $lineheight, usces_conv_euc( apply_filters( 'usces_filters_pdf_company_honor', $company_honor ) ) );
					$y                                       = $pdf->GetY() + $lineheight + $linetop;
					list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['customer_attn'] );
					$pdf->SetFont( $font, '', $fontsize );
					$pdf->SetXY( $leftside, $y );
					$pdf->MultiCell( $width, $lineheight, usces_conv_euc( __( 'Attn', 'usces' ) . ' : ' . usces_conv_euc( usces_get_pdf_shipping_name( $data ) ) . apply_filters( 'usces_filters_pdf_person_honor', $person_honor ) ), 0, 'L' );
					$y = $pdf->GetY() + $linetop + 2;
				}

				/* Address */
				list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['customer_address'] );
				$pdf->SetFont( $font, '', $fontsize );

				usces_get_pdf_shipping_address( $pdf, $data, $y, $linetop, $leftside, $width, $lineheight );

				if ( ! empty( $data->deliveri['tel'] ) ) {
					$pdf->MultiCell( $width, $lineheight, usces_conv_euc( 'TEL ' . $data->deliveri['tel'] ), 0, 'L' );
				}
				if ( ! empty( $data->deliveri['fax'] ) ) {
					$y = $pdf->GetY() + $linetop;
					$pdf->SetXY( $leftside, $y );
					$pdf->MultiCell( $width, $lineheight, usces_conv_euc( 'FAX ' . $data->deliveri['fax'] ), 0, 'L' );
				}
			}

		/* 「購入者情報を宛名とする」 */
		} else {
			$top = 30;

			$company                                 = usces_get_pdf_company( $data->order['ID'], 'customer' );
			$company                                 = apply_filters( 'usces_filter_pdf_customer_company', $company, $data->order['ID'] );
			list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['customer_company'] );
			$pdf->SetFont( $font, '', $fontsize );
			$pdf->SetXY( $leftside, $top );

			if ( empty( $company ) ) {
				$pdf->MultiCell( $width, $lineheight, usces_conv_euc( usces_get_pdf_name( $data ) ), 0, 'L' );
				$x = $leftside + $width;
				$y = $pdf->GetY() - $lineheight;
				$pdf->SetXY( $x, $y );
				$pdf->Write( $lineheight, usces_conv_euc( apply_filters( 'usces_filters_pdf_person_honor', $person_honor ) ) );
				$y = $pdf->GetY() + $lineheight + $linetop + 2;
			} else {
				$pdf->MultiCell( $width, $lineheight, usces_conv_euc( $company ), 0, 'L' );
				$x = $leftside + $width;
				$y = $pdf->GetY() - $lineheight;
				$pdf->SetXY( $x, $y );
				$pdf->Write( $lineheight, usces_conv_euc( apply_filters( 'usces_filters_pdf_company_honor', $company_honor ) ) );
				$y                                       = $pdf->GetY() + $lineheight + $linetop;
				list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['customer_attn'] );
				$pdf->SetFont( $font, '', $fontsize );
				$pdf->SetXY( $leftside, $y );
				$pdf->MultiCell( $width, $lineheight, usces_conv_euc( __( 'Attn', 'usces' ) . ' : ' . usces_conv_euc( usces_get_pdf_name( $data ) ) . apply_filters( 'usces_filters_pdf_person_honor', $person_honor ) ), 0, 'L' );
				$y = $pdf->GetY() + $linetop + 2;
			}

			/* Address */
			list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['customer_address'] );
			$pdf->SetFont( $font, '', $fontsize );

			usces_get_pdf_address( $pdf, $data, $y, $linetop, $leftside, $width, $lineheight );

			if ( ! empty( $data->customer['tel'] ) ) {
				$pdf->MultiCell( $width, $lineheight, usces_conv_euc( 'TEL ' . $data->customer['tel'] ), 0, 'L' );
			}
			if ( ! empty( $data->customer['fax'] ) ) {
				$y = $pdf->GetY() + $linetop;
				$pdf->SetXY( $leftside, $y );
				$pdf->MultiCell( $width, $lineheight, usces_conv_euc( 'FAX ' . $data->customer['fax'] ), 0, 'L' );
			}

			/* 配送先情報 */
			$customer_name    = trim( $data->customer['name1'] ) . trim( $data->customer['name2'] );
			$deliveri_name    = trim( $data->deliveri['name1'] ) . trim( $data->deliveri['name2'] );
			$customer_zip     = trim( $data->customer['zip'] );
			$deliveri_zip     = trim( $data->deliveri['zipcode'] );
			$customer_address = trim( $data->customer['address1'] ) . trim( $data->customer['address2'] ) . trim( $data->customer['address3'] );
			$deliveri_address = trim( $data->deliveri['address1'] ) . trim( $data->deliveri['address2'] ) . trim( $data->deliveri['address3'] );

			/* 発送先情報があるか */
			if ( ! empty( $deliveri_address ) ) {
				/* 購入者と発送先の情報が異なる */
				if ( $customer_name != $deliveri_name || $customer_zip != $deliveri_zip || $customer_address != $deliveri_address ) {
					/* Line */
					$y = $pdf->GetY() + $linetop;
					$pdf->SetLineWidth( 0.1 );
					$pdf->Line( $leftside, $y, $leftside + $width + 5, $y );

					/* 【配送先】タイトル */
					list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['delivery_label'] );
					$y                                       = $pdf->GetY() + $linetop + 1;
					$pdf->SetFont( $font, '', $fontsize );
					$pdf->SetXY( $leftside, $y );
					$pdf->MultiCell( $width, $lineheight, usces_conv_euc( __( '** A shipping address **', 'usces' ) ), 0, 'L' );

					/* 配送先宛名 */
					$delivery_company                        = usces_get_pdf_company( $data->order['ID'], 'delivery' );
					list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['delivery_address'] );
					$y = $pdf->GetY() + $linetop;
					$pdf->SetFont( $font, '', $fontsize );
					$pdf->SetXY( $leftside, $y );
					if ( empty( $delivery_company ) ) {
						$pdf->MultiCell( $width, $lineheight, usces_conv_euc( usces_get_pdf_shipping_name( $data ) ), 0, 'L' );
						$x = $leftside + $width;
						$y = $pdf->GetY() - $lineheight - $linetop;
						$pdf->SetXY( $x, $y );
						$pdf->Write( $lineheight, usces_conv_euc( apply_filters( 'usces_filters_pdf_person_honor', $person_honor ) ) ); // 様.
						$y = $pdf->GetY() + $lineheight + $linetop;
					} else {
						$pdf->MultiCell( $width, $lineheight, usces_conv_euc( $delivery_company ), 0, 'L' );
						$x = $leftside + $width;
						$y = $pdf->GetY() - $lineheight;
						$pdf->SetXY( $x, $y );
						$pdf->Write( $lineheight, usces_conv_euc( apply_filters( 'usces_filters_pdf_company_honor', $company_honor ) ) ); // 御中.
						$y                                       = $pdf->GetY() + $lineheight + $linetop;
						list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['delivery_address'] );
						$pdf->SetFont( $font, '', $fontsize );
						$pdf->SetXY( $leftside, $y );
						$pdf->MultiCell( $width, $lineheight, usces_conv_euc( __( 'Attn', 'usces' ) . ' : ' . usces_conv_euc( usces_get_pdf_shipping_name( $data ) ) . apply_filters( 'usces_filters_pdf_person_honor', $person_honor ) ), 0, 'L' );
						$y = $pdf->GetY() + $linetop;
					}

					/* 配送先住所 */
					list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['delivery_address'] );
					$pdf->SetFont( $font, '', $fontsize );
					usces_get_pdf_shipping_address( $pdf, $data, $y, $linetop, $leftside, $width, $lineheight );

					/* 配送先電話番号 */
					if ( ! empty( $data->deliveri['tel'] ) ) {
						$pdf->MultiCell( $width, $lineheight, usces_conv_euc( 'TEL ' . $data->deliveri['tel'] ), 0, 'L' );
					}
				}
			}
		}
		$y = $pdf->GetY() + $linetop + 0.5;

		$pdf->SetLineWidth( 0.1 );
		$pdf->Line( $leftside, $y, $leftside + $width + 5, $y );

		/* Message */
		$y                                       = 80;
		list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['message'] );
		$pdf->SetFont( $font, '', $fontsize );
		$pdf->SetXY( $leftside, $y );
		$pdf->MultiCell( $width + 70, $lineheight, usces_conv_euc( $message ), 0, 'L' );

		/* Order date */
		list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['order_date'] );
		$pdf->SetFont( $font, '', $fontsize );
		$y = 89;
		$pdf->SetXY( $leftside, $y );
		$pdf->MultiCell( 75, $lineheight, usces_conv_euc( $juchubi ), 0, 'L' );

		/* Payment method */
		$pdf->SetXY( $leftside + 68, $y );
		$pdf->Cell( 75, $lineheight, usces_conv_euc( $siharai ), 0, 1, 'L' );

	} else {
		$top = 30;

		$company                                 = usces_get_pdf_company( $data->order['ID'], 'customer' );
		$company                                 = apply_filters( 'usces_filter_pdf_customer_company', $company, $data->order['ID'] );
		list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['customer_company'] );
		$pdf->SetFont( $font, '', $fontsize );
		$pdf->SetXY( $leftside, $top );

		if ( empty( $company ) ) {
			$pdf->MultiCell( $width, $lineheight, usces_conv_euc( usces_get_pdf_name( $data ) ), 0, 'L' );
			$x = $leftside + $width;
			$y = $pdf->GetY() - $lineheight;
			$pdf->SetXY( $x, $y );
			$pdf->Write( $lineheight, usces_conv_euc( apply_filters( 'usces_filters_pdf_person_honor', $person_honor ) ) );
			$y = $pdf->GetY() + $lineheight + $linetop + 2;
		} else {
			$pdf->MultiCell( $width, $lineheight, usces_conv_euc( $company ), 0, 'L' );
			$x = $leftside + $width;
			$y = $pdf->GetY() - $lineheight;
			$pdf->SetXY( $x, $y );
			$pdf->Write( $lineheight, usces_conv_euc( apply_filters( 'usces_filters_pdf_company_honor', $company_honor ) ) );
			$y                                       = $pdf->GetY() + $lineheight + $linetop;
			list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['customer_attn'] );
			$pdf->SetFont( $font, '', $fontsize );
			$pdf->SetXY( $leftside, $y );
			$pdf->MultiCell( $width, $lineheight, usces_conv_euc( __( 'Attn', 'usces' ) . ' : ' . usces_conv_euc( usces_get_pdf_name( $data ) ) . apply_filters( 'usces_filters_pdf_person_honor', $person_honor ) ), 0, 'L' );
			$y = $pdf->GetY() + $linetop + 2;
		}

		/* Address */
		list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['customer_address'] );
		$pdf->SetFont( $font, '', $fontsize );

		usces_get_pdf_address( $pdf, $data, $y, $linetop, $leftside, $width, $lineheight );

		if ( ! empty( $data->customer['tel'] ) ) {
			$pdf->MultiCell( $width, $lineheight, usces_conv_euc( 'TEL ' . $data->customer['tel'] ), 0, 'L' );
		}
		if ( ! empty( $data->customer['fax'] ) ) {
			$y = $pdf->GetY() + $linetop;
			$pdf->SetXY( $leftside, $y );
			$pdf->MultiCell( $width, $lineheight, usces_conv_euc( 'FAX ' . $data->customer['fax'] ), 0, 'L' );
		}
		$y = $pdf->GetY() + $linetop + 0.5;

		$pdf->SetLineWidth( 0.1 );
		$pdf->Line( $leftside, $y, $leftside + $width + 5, $y );

		/* Message */
		$y                                       = 80;
		list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['message'] );
		$pdf->SetFont( $font, '', $fontsize );
		$pdf->SetXY( $leftside, $y );
		$pdf->MultiCell( $width + 70, $lineheight, usces_conv_euc( $message ), 0, 'L' );

		/* Order date */
		list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['order_date'] );
		$pdf->SetFont( $font, '', $fontsize );
		$y = 89;
		$pdf->SetXY( $leftside, $y );
		$pdf->MultiCell( 75, $lineheight, usces_conv_euc( $juchubi ), 0, 'L' );

		/* Payment method */
		$pdf->SetXY( $leftside + 68, $y );
		$pdf->Cell( 75, $lineheight, usces_conv_euc( $siharai ), 0, 1, 'L' );
	}

	/* My company */
	if ( ! empty( $sign_image ) ) {
		$sign_data = apply_filters( 'usces_filter_pdf_sign_data', array( 140, 40, 25, 25 ) );
		$pdf->Image( $sign_image, $sign_data[0], $sign_data[1], $sign_data[2], $sign_data[3] );
	}
	$x = 110;
	$y = 45;
	$pdf->SetLeftMargin( $x );
	list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['publisher'] );
	$pdf->SetFont( $font, '', $fontsize );
	$pdf->SetXY( $x, $y );
	$pdf->MultiCell( 60, $lineheight, usces_conv_euc( apply_filters( 'usces_filter_publisher', html_entity_decode( get_option( 'blogname' ), ENT_QUOTES ) ) ), 0, 'L' );
	list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['company_name'] );
	$pdf->SetFont( $font, '', $fontsize );
	$pdf->MultiCell( 60, $lineheight, usces_conv_euc( apply_filters( 'usces_filter_pdf_mycompany', $usces->options['company_name'] ) ), 0, 'L' );
	usces_get_pdf_myaddress( $pdf, $lineheight );
	if ( ! empty( $usces->options['tel_number'] ) ) {
		$pdf->MultiCell( 60, $lineheight, usces_conv_euc( apply_filters( 'usces_filter_pdf_mycompany_tel', 'TEL: ' . $usces->options['tel_number'] ) ), 0, 'L' );
	}
	if ( ! empty( $usces->options['fax_number'] ) ) {
		$pdf->MultiCell( 60, $lineheight, usces_conv_euc( apply_filters( 'usces_filter_pdf_mycompany_fax', 'FAX: ' . $usces->options['fax_number'] ) ), 0, 'L' );
	}
	if ( ! empty( $usces->options['business_registration_number'] ) ) {
		$pdf->MultiCell( 60, $lineheight, usces_conv_euc( apply_filters( 'usces_filter_pdf_business_registration_number', __( 'Business registration number', 'usces' ) . ': ' . $usces->options['business_registration_number'] ) ), 0, 'L' );
	}

	do_action( 'usces_action_pdf_header', $pdf, $data, $font );

	/* Body label */
	list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['details_label'] );
	$pdf->SetFont( $font, '', $fontsize );
	$pdf->SetXY( 15.5, 94.9 );
	$pdf->MultiCell( 87.8, $lineheight, usces_conv_euc( __( 'item name', 'usces' ) ), 0, 'C' );
	$pdf->SetXY( 103.7, 94.9 );
	$pdf->MultiCell( 11.4, $lineheight, usces_conv_euc( __( 'Quant', 'usces' ) ), 0, 'C' );
	$pdf->SetXY( 115.8, 94.9 );
	$pdf->MultiCell( 11.0, $lineheight, usces_conv_euc( __( 'Unit', 'usces' ) ), 0, 'C' );
	$pdf->SetXY( 127.2, 94.9 );
	$pdf->MultiCell( 15.0, $lineheight, usces_conv_euc( __( 'Price', 'usces' ) ), 0, 'C' );
	$pdf->SetXY( 142.9, 94.9 );
	$pdf->MultiCell( 22.4, $lineheight, usces_conv_euc( __( 'Amount', 'usces' ) . '(' . __( usces_crcode( 'return' ), 'usces' ) . ')' ), 0, 'C' );

	/* Horizontal lines */
	$pdf->SetLineWidth( 0.5 );
	$pdf->Line( 15.4, 100, 165.5, 100 );
}

/**
 * Detail
 *
 * @param object $pdf       TCPDF.
 * @param object $data      Order data.
 * @param int    $page      Page no.
 * @param object $font      Font.
 * @param float  $x         X axis.
 * @param float  $y         Y axis.
 * @param float  $onep      Page height.
 * @param float  $next_y    Next Y axis.
 * @param array  $border    Border.
 * @param int    $index     Index.
 * @param array  $cart      Cart data.
 * @param array  $cart_row  Cart data.
 * @param array  $fontsizes Font size.
 * @param string $mark      Reduced taxrate mark.
 */
function usces_pdfSetDetail( $pdf, $data, $page, $font, $x, $y, $onep, $next_y, $border, $index, $cart, $cart_row, $fontsizes, $mark = '' ) {
	global $usces;

	$type           = ( isset( $_REQUEST['type'] ) ) ? wp_unslash( $_REQUEST['type'] ) : '';
	$post_id        = $cart_row['post_id'];
	$sku            = urldecode( $cart_row['sku'] );
	$cart_item_name = $usces->getCartItemName_byOrder( $cart_row );
	$optstr         = '';
	if ( isset( $cart_row['options'] ) && is_array( $cart_row['options'] ) && count( $cart_row['options'] ) > 0 ) {
		foreach ( $cart_row['options'] as $key => $value ) {
			if ( ! empty( $key ) ) {
				$key   = urldecode( $key );
				$value = maybe_unserialize( $value );
				if ( is_array( $value ) ) {
					$c       = '';
					$optstr .= $key . ' = ';
					foreach ( $value as $v ) {
						$optstr .= $c . rawurldecode( $v );
						$c       = ', ';
					}
					$optstr .= "\n";
				} else {
					$optstr .= $key . ' = ' . rawurldecode( $value ) . "\n";
				}
			}
		}
		$optstr = apply_filters( 'usces_filter_option_pdf', $optstr, $cart_row['options'] );
	}
	$optstr = apply_filters( 'usces_filter_all_option_pdf', $optstr, $cart_row['options'], $post_id, $sku, $cart_row['advance'], $cart_row );

	$output_options = ( 'receipt' === $type ) ? apply_filters( 'usces_filter_pdf_output_options_receipt', true, $data ) : true;

	$args      = compact( 'cart', 'cart_row', 'post_id', 'sku', 'index' );
	$fontsizes = apply_filters( 'useces_filter_order_pdfbody_fontsize', $fontsizes, $args, $data );
	$fontsizes = apply_filters( 'usces_filter_pdf_body_fontsize', $fontsizes, $args, $data ); // alias.

	$line_y = $next_y;

	list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['item_name'] );
	$pdf->SetFont( $font, '', $fontsize );
	$pdf->SetXY( $x - 0.2, $line_y );
	$pdf->MultiCell( 4, $lineheight, '*', 0, 'C' );
	$pdf->SetXY( $x + 3.0, $line_y );
	$pdf->MultiCell( 84.6, $lineheight, usces_conv_euc( apply_filters( 'usces_filter_cart_item_name_nl', $cart_item_name . $mark, $args ) ), 0, 'L' );

	if ( ! empty( $optstr ) && $output_options ) {
		list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['details'] );
		$pdf->SetFont( $font, '', $fontsize );
		$pdf->SetXY( $x + 6.0, $pdf->GetY() + $linetop );
		$pdf->MultiCell( 81.6, $lineheight - 0.2, usces_conv_euc( $optstr ), 0, 'L' );
	}

	$pdf_args = compact( 'page', 'x', 'y', 'onep', 'next_y', 'border', 'index', 'cart_row', 'fontsizes', 'lineheight', 'linetop', 'font' );
	do_action( 'usces_action_order_print_cart_row', $pdf, $data, $pdf_args );
	$next_y = $pdf->GetY() + 2;

	list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['quantity'] );
	$pdf->SetFont( $font, '', $fontsize );
	$pdf->SetXY( $x + 88.0, $line_y );
	$pdf->MultiCell( 11.5, $lineheight, usces_conv_euc( $cart_row['quantity'] ), 0, 'R' );
	list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['unit'] );
	$pdf->SetFont( $font, '', $fontsize );
	$pdf->SetXY( $x + 99.6, $line_y );
	$pdf->MultiCell( 11.5, $lineheight, usces_conv_euc( $usces->getItemSkuUnit( $post_id, $sku ) ), 0, 'C' );
	$pdf->SetXY( $x + 111.5, $line_y );
	list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['unitprice'] );
	$pdf->SetFont( $font, '', $fontsize );
	$pdf->MultiCell( 15.2, $lineheight, apply_filters( 'usces_filter_cart_row_unitprice_pdf', usces_conv_euc( $usces->get_currency( $cart_row['price'] ) ), $cart_row ), 0, 'R' );
	$pdf->SetXY( $x + 126.9, $line_y );
	list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['row_price'] );
	$pdf->SetFont( $font, '', $fontsize );
	$pdf->MultiCell( 22.8, $lineheight, apply_filters( 'usces_filter_cart_row_price_pdf', usces_conv_euc( $usces->get_currency( $cart_row['price'] * $cart_row['quantity'] ) ), $cart_row ), 0, 'R' );

	$pdf->SetXY( $x, $next_y );
	return $next_y;
}

/**
 * Footer
 *
 * @param object $pdf       TCPDF.
 * @param object $data      Order data.
 * @param object $font      Font.
 * @param object $usces_tax Tax.
 */
function usces_pdfSetFooter( $pdf, $data, $font, $usces_tax ) {
	global $usces;

	$type                = ( isset( $_REQUEST['type'] ) ) ? wp_unslash( $_REQUEST['type'] ) : '';
	$condition           = $data->condition;
	$tax_display         = ( isset( $condition['tax_display'] ) ) ? $condition['tax_display'] : usces_get_tax_display();
	$tax_target          = ( isset( $condition['tax_target'] ) ) ? $condition['tax_target'] : $usces->options['tax_target'];
	$tax_mode            = ( isset( $condition['tax_mode'] ) ) ? $condition['tax_mode'] : $usces->options['tax_mode'];
	$member_system       = ( isset( $condition['membersystem_state'] ) ) ? $condition['membersystem_state'] : $usces->options['membersystem_state'];
	$member_system_point = ( isset( $condition['membersystem_point'] ) ) ? $condition['membersystem_point'] : $usces->options['membersystem_point'];
	$point_coverage      = ( isset( $condition['point_coverage'] ) ) ? $condition['point_coverage'] : $usces->options['point_coverage'];

	$fontsizes = array(
		'footer_label' => 9,
		'footer_value' => 8,
		'footer_note'  => 8,
	);
	$fontsizes = apply_filters( 'useces_filter_order_pdffooter_fontsize', $fontsizes, $data );
	$fontsizes = apply_filters( 'usces_filter_pdf_footer_fontsize', $fontsizes, $data ); // alias.

	$y = $pdf->GetY() + 2;

	$label_data = array();
	$value_data = array();
	$cart       = usces_get_ordercartdata( $data->order['ID'] );
	$cart       = apply_filters( 'usces_filter_pdf_cart_data', $cart, $data->order['ID'] );
	$shipped    = usces_have_shipped( $cart );

	$pdf->Rect( 14, 197.8, 153, 45, 'F' ); // Footer field.

	$label_data[] = apply_filters( 'usces_filter_pdf_item_total_price_label', __( 'total items', 'usces' ) );
	$value_data[] = apply_filters( 'usces_filter_pdf_item_total_price_value', $usces->get_currency( $data->order['item_total_price'] ) );
	$label_data[] = apply_filters( 'usces_filter_pdf_discount_label', apply_filters( 'usces_filter_disnount_label', __( 'Campaign discount', 'usces' ), $data ), $data );
	$value_data[] = apply_filters( 'usces_filter_pdf_discount_value', apply_filters( 'usces_filter_disnount_vlue', $usces->get_currency( $data->order['discount'] ) ) );

	if ( 'activate' === $tax_display ) {
		if ( 'include' === $tax_mode ) {
			$include_tax_standard = '(' . usces_crform( $usces_tax->tax_standard, false, false, 'return', true ) . ')';
			$include_tax_reduced  = '(' . usces_crform( $usces_tax->tax_reduced, false, false, 'return', true ) . ')';
			$tax                  = '(' . $usces->get_currency( $usces_tax->tax_standard + $usces_tax->tax_reduced ) . ')';
		} else {
			$tax = $usces->get_currency( $data->order['tax'] );
		}

		$labeldata = array(
			'order_condition'        => $data->condition,
			'order_item_total_price' => $data->order['item_total_price'],
			'order_discount'         => $data->order['discount'],
			'order_shipping_charge'  => $data->order['shipping_charge'],
			'order_cod_fee'          => $data->order['cod_fee'],
		);

		if ( 'activate' === $member_system && 'activate' === $member_system_point ) {
			if ( 1 === (int) $point_coverage ) {
				if ( 'products' === $tax_target ) {
					$label_data[] = apply_filters( 'usces_filter_pdf_tax_label', usces_tax_label( $labeldata, 'return' ) );
					$value_data[] = apply_filters( 'usces_filter_pdf_tax_value', apply_filters( 'usces_filter_tax_vlue', $tax, $data ), $data );
					if ( $shipped ) {
						$label_data[] = apply_filters( 'usces_filter_pdf_shipping_label', apply_filters( 'usces_filter_shipping_label', __( 'Shipping', 'usces' ) ) );
						$value_data[] = apply_filters( 'usces_filter_pdf_shipping_value', apply_filters( 'usces_filter_shipping_vlue', $usces->get_currency( $data->order['shipping_charge'] ) ), $data );
						$label_data[] = apply_filters( 'usces_filter_pdf_cod_label', apply_filters( 'usces_filter_cod_label', __( 'COD fee', 'usces' ), $data->order['ID'] ), $data );
						$value_data[] = apply_filters( 'usces_filter_pdf_cod_value', apply_filters( 'usces_filter_cod_vlue', $usces->get_currency( $data->order['cod_fee'] ) ), $data );
					}
				} else {
					if ( $shipped ) {
						$label_data[] = apply_filters( 'usces_filter_pdf_shipping_label', apply_filters( 'usces_filter_shipping_label', __( 'Shipping', 'usces' ) ) );
						$value_data[] = apply_filters( 'usces_filter_pdf_shipping_value', apply_filters( 'usces_filter_shipping_vlue', $usces->get_currency( $data->order['shipping_charge'] ) ), $data );
						$label_data[] = apply_filters( 'usces_filter_pdf_cod_label', apply_filters( 'usces_filter_cod_label', __( 'COD fee', 'usces' ), $data->order['ID'] ), $data );
						$value_data[] = apply_filters( 'usces_filter_pdf_cod_value', apply_filters( 'usces_filter_cod_vlue', $usces->get_currency( $data->order['cod_fee'] ) ), $data );
					}
					$label_data[] = apply_filters( 'usces_filter_pdf_tax_label', usces_tax_label( $labeldata, 'return' ) );
					$value_data[] = apply_filters( 'usces_filter_pdf_tax_value', apply_filters( 'usces_filter_tax_vlue', $tax, $data ), $data );
				}
				$label_data[] = apply_filters( 'usces_filter_pdf_point_label', apply_filters( 'usces_filter_point_label', __( 'Used points', 'usces' ) ) );
				$value_data[] = apply_filters( 'usces_filter_pdf_point_value', apply_filters( 'usces_filter_point_vlue', $usces->get_currency( $data->order['usedpoint'] ) ), $data );
			} else {
				if ( 'products' === $tax_target ) {
					$label_data[] = apply_filters( 'usces_filter_pdf_tax_label', usces_tax_label( $labeldata, 'return' ) );
					$value_data[] = apply_filters( 'usces_filter_pdf_tax_value', apply_filters( 'usces_filter_tax_vlue', $tax, $data ), $data );
					$label_data[] = apply_filters( 'usces_filter_pdf_point_label', apply_filters( 'usces_filter_point_label', __( 'Used points', 'usces' ) ) );
					$value_data[] = apply_filters( 'usces_filter_pdf_point_value', apply_filters( 'usces_filter_point_vlue', $usces->get_currency( $data->order['usedpoint'] ) ), $data );
					if ( $shipped ) {
						$label_data[] = apply_filters( 'usces_filter_pdf_shipping_label', apply_filters( 'usces_filter_shipping_label', __( 'Shipping', 'usces' ) ) );
						$value_data[] = apply_filters( 'usces_filter_pdf_shipping_value', apply_filters( 'usces_filter_shipping_vlue', $usces->get_currency( $data->order['shipping_charge'] ) ), $data );
						$label_data[] = apply_filters( 'usces_filter_pdf_cod_label', apply_filters( 'usces_filter_cod_label', __( 'COD fee', 'usces' ), $data->order['ID'] ), $data );
						$value_data[] = apply_filters( 'usces_filter_pdf_cod_value', apply_filters( 'usces_filter_cod_vlue', $usces->get_currency( $data->order['cod_fee'] ) ), $data );
					}
				} else {
					$label_data[] = apply_filters( 'usces_filter_pdf_point_label', apply_filters( 'usces_filter_point_label', __( 'Used points', 'usces' ) ) );
					$value_data[] = apply_filters( 'usces_filter_pdf_point_value', apply_filters( 'usces_filter_point_vlue', $usces->get_currency( $data->order['usedpoint'] ) ), $data );
					if ( $shipped ) {
						$label_data[] = apply_filters( 'usces_filter_pdf_shipping_label', apply_filters( 'usces_filter_shipping_label', __( 'Shipping', 'usces' ) ) );
						$value_data[] = apply_filters( 'usces_filter_pdf_shipping_value', apply_filters( 'usces_filter_shipping_vlue', $usces->get_currency( $data->order['shipping_charge'] ) ), $data );
						$label_data[] = apply_filters( 'usces_filter_pdf_cod_label', apply_filters( 'usces_filter_cod_label', __( 'COD fee', 'usces' ), $data->order['ID'] ), $data );
						$value_data[] = apply_filters( 'usces_filter_pdf_cod_value', apply_filters( 'usces_filter_cod_vlue', $usces->get_currency( $data->order['cod_fee'] ) ), $data );
					}
					$label_data[] = apply_filters( 'usces_filter_pdf_tax_label', usces_tax_label( $labeldata, 'return' ) );
					$value_data[] = apply_filters( 'usces_filter_pdf_tax_value', apply_filters( 'usces_filter_tax_vlue', $tax, $data ), $data );
				}
			}
		} else {
			if ( 'products' === $tax_target ) {
				$label_data[] = apply_filters( 'usces_filter_pdf_tax_label', usces_tax_label( $labeldata, 'return' ) );
				$value_data[] = apply_filters( 'usces_filter_pdf_tax_value', apply_filters( 'usces_filter_tax_vlue', $tax, $data ), $data );
				if ( $shipped ) {
					$label_data[] = apply_filters( 'usces_filter_pdf_shipping_label', apply_filters( 'usces_filter_shipping_label', __( 'Shipping', 'usces' ) ) );
					$value_data[] = apply_filters( 'usces_filter_pdf_shipping_value', apply_filters( 'usces_filter_shipping_vlue', $usces->get_currency( $data->order['shipping_charge'] ) ), $data );
					$label_data[] = apply_filters( 'usces_filter_pdf_cod_label', apply_filters( 'usces_filter_cod_label', __( 'COD fee', 'usces' ), $data->order['ID'] ), $data );
					$value_data[] = apply_filters( 'usces_filter_pdf_cod_value', apply_filters( 'usces_filter_cod_vlue', $usces->get_currency( $data->order['cod_fee'] ) ), $data );
				}
			} else {
				if ( $shipped ) {
					$label_data[] = apply_filters( 'usces_filter_pdf_shipping_label', apply_filters( 'usces_filter_shipping_label', __( 'Shipping', 'usces' ) ) );
					$value_data[] = apply_filters( 'usces_filter_pdf_shipping_value', apply_filters( 'usces_filter_shipping_vlue', $usces->get_currency( $data->order['shipping_charge'] ) ), $data );
					$label_data[] = apply_filters( 'usces_filter_pdf_cod_label', apply_filters( 'usces_filter_cod_label', __( 'COD fee', 'usces' ), $data->order['ID'] ), $data );
					$value_data[] = apply_filters( 'usces_filter_pdf_cod_value', apply_filters( 'usces_filter_cod_vlue', $usces->get_currency( $data->order['cod_fee'] ) ), $data );
				}
				$label_data[] = apply_filters( 'usces_filter_pdf_tax_label', usces_tax_label( $labeldata, 'return' ) );
				$value_data[] = apply_filters( 'usces_filter_pdf_tax_value', apply_filters( 'usces_filter_tax_vlue', $tax, $data ), $data );
			}
		}
	} else {
		if ( 'activate' === $member_system && 'activate' === $member_system_point ) {
			if ( 1 === (int) $point_coverage ) {
				if ( $shipped ) {
					$label_data[] = apply_filters( 'usces_filter_pdf_shipping_label', apply_filters( 'usces_filter_shipping_label', __( 'Shipping', 'usces' ) ) );
					$value_data[] = apply_filters( 'usces_filter_pdf_shipping_value', apply_filters( 'usces_filter_shipping_vlue', $usces->get_currency( $data->order['shipping_charge'] ) ), $data );
					$label_data[] = apply_filters( 'usces_filter_pdf_cod_label', apply_filters( 'usces_filter_cod_label', __( 'COD fee', 'usces' ), $data->order['ID'] ), $data );
					$value_data[] = apply_filters( 'usces_filter_pdf_cod_value', apply_filters( 'usces_filter_cod_vlue', $usces->get_currency( $data->order['cod_fee'] ) ), $data );
				}
				$label_data[] = apply_filters( 'usces_filter_pdf_point_label', apply_filters( 'usces_filter_point_label', __( 'Used points', 'usces' ) ) );
				$value_data[] = apply_filters( 'usces_filter_pdf_point_value', apply_filters( 'usces_filter_point_vlue', $usces->get_currency( $data->order['usedpoint'] ) ), $data );
			} else {
				$label_data[] = apply_filters( 'usces_filter_pdf_point_label', apply_filters( 'usces_filter_point_label', __( 'Used points', 'usces' ) ) );
				$value_data[] = apply_filters( 'usces_filter_pdf_point_value', apply_filters( 'usces_filter_point_vlue', $usces->get_currency( $data->order['usedpoint'] ) ), $data );
				if ( $shipped ) {
					$label_data[] = apply_filters( 'usces_filter_pdf_shipping_label', apply_filters( 'usces_filter_shipping_label', __( 'Shipping', 'usces' ) ) );
					$value_data[] = apply_filters( 'usces_filter_pdf_shipping_value', apply_filters( 'usces_filter_shipping_vlue', $usces->get_currency( $data->order['shipping_charge'] ) ), $data );
					$label_data[] = apply_filters( 'usces_filter_pdf_cod_label', apply_filters( 'usces_filter_cod_label', __( 'COD fee', 'usces' ), $data->order['ID'] ), $data );
					$value_data[] = apply_filters( 'usces_filter_pdf_cod_value', apply_filters( 'usces_filter_cod_vlue', $usces->get_currency( $data->order['cod_fee'] ) ), $data );
				}
			}
		} else {
			if ( $shipped ) {
				$label_data[] = apply_filters( 'usces_filter_pdf_shipping_label', apply_filters( 'usces_filter_shipping_label', __( 'Shipping', 'usces' ) ) );
				$value_data[] = apply_filters( 'usces_filter_pdf_shipping_value', apply_filters( 'usces_filter_shipping_vlue', $usces->get_currency( $data->order['shipping_charge'] ) ), $data );
				$label_data[] = apply_filters( 'usces_filter_pdf_cod_label', apply_filters( 'usces_filter_cod_label', __( 'COD fee', 'usces' ), $data->order['ID'] ), $data );
				$value_data[] = apply_filters( 'usces_filter_pdf_cod_value', apply_filters( 'usces_filter_cod_vlue', $usces->get_currency( $data->order['cod_fee'] ) ), $data );
			}
		}
	}

	$payment   = $usces->getPayments( $data->order['payment_name'] );
	$transfers = apply_filters( 'usces_filter_pdf_transfer', array( 'transferAdvance', 'transferDeferred' ), $data );
	$note_text = '';
	if ( 'bill' === $type && in_array( $payment['settlement'], $transfers ) ) {
		$transferee  = __( 'Transfer', 'usces' ) . " : \r\n";
		$transferee .= $usces->options['transferee'] . "\r\n";
		$note_text   = apply_filters( 'usces_filter_mail_transferee', $transferee, $payment );
	} else {
		if ( ! empty( $data->order['note'] ) ) {
			if ( false === strpos( $data->order['note'], "\r\n" ) ) {
				$note_text = mb_substr( $data->order['note'], 0, 360, 'UTF-8' );
			} else {
				$line      = 0;
				$note_line = explode( "\r\n", $data->order['note'] );
				foreach ( $note_line as $note ) {
					$len = mb_strlen( $note, 'UTF-8' );
					$cnt = ceil( $len / 30 );
					if ( 0 === $cnt ) {
						$cnt = 1;
					}
					if ( 12 >= $line + $cnt ) {
						$note_text .= $note . "\r\n";
					} else {
						$more       = 12 - $line;
						$note_text .= mb_substr( $note, 0, $more * 30, 'UTF-8' ) . "\r\n";
					}
					$line += $cnt;
					if ( 12 < $line ) {
						break;
					}
				}
			}
		}
	}
	list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['footer_note'] );
	$pdf->SetFont( $font, '', $fontsize );
	$line_y = $y;
	$pdf->SetXY( 16, $line_y );
	$pdf->MultiCell( 86.8, $lineheight, usces_conv_euc( apply_filters( 'usces_filter_pdf_note', $note_text, $data, $type ) ), 0, 'J' );

	list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['footer_label'] );
	$pdf->SetFont( $font, '', $fontsize );
	$label_data = apply_filters( 'usces_filter_pdf_footer_label', $label_data, $data );
	$line_y     = $y;
	foreach ( $label_data as $label ) {
		$pdf->SetXY( 104.3, $line_y );
		$pdf->MultiCell( 37.7, $lineheight, usces_conv_euc( $label ), 0, 'C' );
		$line_y += 6;
	}
	$line_y += 0.6;
	$pdf->SetXY( 104.3, $line_y );
	$pdf->MultiCell( 37.77, $lineheight, usces_conv_euc( apply_filters( 'usces_filter_pdf_total_full_price_label', __( 'Total Amount', 'usces' ) ) ), 0, 'C' );

	list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['footer_label'] );
	$pdf->SetFont( $font, '', $fontsize );
	$value_data = apply_filters( 'usces_filter_pdf_footer_value', $value_data, $data );
	$line_y     = $y;
	foreach ( $value_data as $value ) {
		$pdf->SetXY( 142.9, $line_y );
		$pdf->MultiCell( 22.6, $lineheight, usces_conv_euc( $value ), 0, 'R' );
		$line_y += 6;
	}
	$line_y += 0.6;
	$pdf->SetXY( 142.9, $line_y );
	$pdf->MultiCell( 22.67, $lineheight, usces_conv_euc( apply_filters( 'usces_filter_pdf_total_full_price_value', apply_filters( 'usces_filter_total_full_price_value', $usces->get_currency( $data->order['total_full_price'] ) ), $data ) ), 0, 'R' );

	/* Horizontal lines */
	$line_left  = 15.4;
	$line_right = $line_left + 150.1;

	$line_y = $y - 1;
	$pdf->SetLineWidth( 0.5 );
	$pdf->Line( $line_left, $line_y, $line_right, $line_y );
	$pdf->SetLineWidth( 0.04 );
	$line_count = count( $value_data );
	for ( $l = 0; $l < $line_count; $l++ ) {
		$pdf->Line( 103.5, $line_y, $line_right, $line_y );
		$line_y += 6;
	}
	$pdf->SetLineWidth( 0.5 );
	// $line_y += 6;
	$pdf->Line( 103.5, $line_y, $line_right, $line_y );
	$line_y += 7.2;
	$pdf->Line( 103.5, $line_y, $line_right, $line_y );
	// $pdf->Line( 15.4, $line_y, $line_right, $line_y );

	if ( 'activate' === $tax_display && 'all' === $tax_target ) {
		$line_y += 1;
		if ( 'include' === $tax_mode ) {
			$po = '(';
			$pc = ')';
		} else {
			$po = '';
			$pc = '';
		}
		$pdf->SetXY( 53, $line_y );
		$pdf->MultiCell( 5, $lineheight, usces_conv_euc( '(' ), 0, 'C' );
		$pdf->SetXY( 44.3, $line_y );
		$pdf->MultiCell( 37.7, $lineheight, usces_conv_euc( sprintf( __( 'Applies to %s%%', 'usces' ), $usces_tax->tax_rate_standard ) ), 0, 'C' );
		$pdf->SetXY( 82.9, $line_y );
		$pdf->MultiCell( 22.6, $lineheight, usces_conv_euc( $usces->get_currency( $usces_tax->subtotal_standard + $usces_tax->discount_standard ) ), 0, 'R' );
		$pdf->SetXY( 104.3, $line_y );
		$pdf->MultiCell( 37.7, $lineheight, usces_conv_euc( sprintf( __( '%s%% consumption tax', 'usces' ), $usces_tax->tax_rate_standard ) ), 0, 'C' );
		$pdf->SetXY( 142.9, $line_y );
		$pdf->MultiCell( 22.6, $lineheight, usces_conv_euc( $po . $usces->get_currency( $usces_tax->tax_standard ) . $pc ), 0, 'R' );
		$pdf->SetXY( 163.5, $line_y );
		$pdf->MultiCell( 5, $lineheight, usces_conv_euc( ')' ), 0, 'C' );
		$line_y += 5.2;
		$pdf->SetXY( 53, $line_y );
		$pdf->MultiCell( 5, $lineheight, usces_conv_euc( '(' ), 0, 'C' );
		$pdf->SetXY( 44.3, $line_y );
		$pdf->MultiCell( 37.7, $lineheight, usces_conv_euc( sprintf( __( 'Applies to %s%%', 'usces' ), $usces_tax->tax_rate_reduced ) ), 0, 'C' );
		$pdf->SetXY( 82.9, $line_y );
		$pdf->MultiCell( 22.6, $lineheight, usces_conv_euc( $usces->get_currency( $usces_tax->subtotal_reduced + $usces_tax->discount_reduced ) ), 0, 'R' );
		$pdf->SetXY( 104.3, $line_y );
		$pdf->MultiCell( 37.7, $lineheight, usces_conv_euc( sprintf( __( '%s%% consumption tax', 'usces' ), $usces_tax->tax_rate_reduced ) ), 0, 'C' );
		$pdf->SetXY( 142.9, $line_y );
		$pdf->MultiCell( 22.6, $lineheight, usces_conv_euc( $po . $usces->get_currency( $usces_tax->tax_reduced ) . $pc ), 0, 'R' );
		$pdf->SetXY( 163.5, $line_y );
		$pdf->MultiCell( 5, $lineheight, usces_conv_euc( ')' ), 0, 'C' );
		$line_y += 3.2;
	}

	$line_y += 1;
	list( $fontsize, $lineheight, $linetop ) = usces_set_font_size( $fontsizes['footer_note'] );
	$pdf->SetFont( $font, '', $fontsize );
	$pdf->SetXY( 125.5, $line_y );
	$pdf->MultiCell( 40, $lineheight, usces_conv_euc( $usces_tax->reduced_taxrate_mark . __( ' is reduced tax rate', 'usces' ) ), 0, 'R' );

	do_action( 'usces_action_order_print_footer', $pdf, $data, 'end' );
	do_action( 'usces_action_pdf_footer', $pdf, $data, $font, 'end' );
}

/**
 * Set font size
 *
 * @param float $size Font size.
 * @return array
 */
function usces_set_font_size( $size ) {
	$lineheight = $size / 2.6;
	$linetop    = $lineheight / 12;
	return array( $size, $lineheight, $linetop );
}

/**
 * Customer name
 *
 * @param object $data Order data.
 * @return string
 */
function usces_get_pdf_name( $data ) {
	$options   = get_option( 'usces' );
	$applyform = usces_get_apply_addressform( $options['system']['addressform'] );
	$name      = '';

	switch ( $applyform ) {
		case 'JP':
			$name = $data->customer['name1'] . ' ' . $data->customer['name2'];
			break;
		case 'US':
		default:
			$name = $data->customer['name2'] . ' ' . $data->customer['name1'];
	}
	return $name;
}

/**
 * Shipping name
 *
 * @param object $data Order data.
 * @return string
 */
function usces_get_pdf_shipping_name( $data ) {
	$options   = get_option( 'usces' );
	$applyform = usces_get_apply_addressform( $options['system']['addressform'] );
	$name      = '';

	switch ( $applyform ) {
		case 'JP':
			$name = $data->deliveri['name1'] . ' ' . $data->deliveri['name2'];
			break;
		case 'US':
		default:
			$name = $data->deliveri['name2'] . ' ' . $data->deliveri['name1'];
	}
	return $name;
}

/**
 * Customer address
 *
 * @param object $pdf        TCPDF.
 * @param object $data       Order data.
 * @param float  $y          Y axis.
 * @param float  $linetop    Line top.
 * @param float  $leftside   Left side.
 * @param float  $width      Width.
 * @param float  $lineheight Line height.
 */
function usces_get_pdf_address( $pdf, $data, $y, $linetop, $leftside, $width, $lineheight ) {
	$options   = get_option( 'usces' );
	$applyform = usces_get_apply_addressform( $options['system']['addressform'] );
	$name      = '';
	$pref      = ( __( '-- Select --', 'usces' ) == $data->customer['pref'] || '-- Select --' == $data->customer['pref'] ) ? '' : $data->customer['pref'];

	switch ( $applyform ) {
		case 'JP':
			$pdf->SetXY( $leftside, $y );
			if ( ! empty( $data->customer['zip'] ) ) {
				$pdf->MultiCell( $width, $lineheight, usces_conv_euc( __( 'zip code', 'usces' ) . ' ' . $data->customer['zip'] ), 0, 'L' );
			}
			if ( ! empty( $pref ) || ! empty( $data->customer['address1'] ) || ! empty( $data->customer['address2'] ) || ! empty( $data->customer['address3'] ) ) {
				$pdf->MultiCell( $width, $lineheight, usces_conv_euc( $pref . $data->customer['address1'] . $data->customer['address2'] ) . ' ' . $data->customer['address3'], 0, 'L' );
			}
			break;
		case 'US':
		default:
			$pdf->SetXY( $leftside, $y );
			if ( ! empty( $pref ) || ! empty( $data->customer['address1'] ) || ! empty( $data->customer['address2'] ) || ! empty( $data->customer['address3'] ) ) {
				$pdf->MultiCell( $width, $lineheight, usces_conv_euc( $data->customer['address2'] . ' ' . $data->customer['address3'] . ' ' . $data->customer['address1'] . ' ' . $pref . ' ' . $data->customer['country'] ), 0, 'L' );
			}
			$y = $pdf->GetY() + $linetop;
			$pdf->SetXY( $leftside, $y );
			if ( ! empty( $data->customer['zip'] ) ) {
				$pdf->MultiCell( $width, $lineheight, usces_conv_euc( __( 'zip code', 'usces' ) . ' ' . $data->customer['zip'] ), 0, 'L' );
			}
			break;
	}
}

/**
 * Shipping address
 *
 * @param object $pdf        TCPDF.
 * @param object $data       Order data.
 * @param float  $y          Y axis.
 * @param float  $linetop    Line top.
 * @param float  $leftside   Left side.
 * @param float  $width      Width.
 * @param float  $lineheight Line height.
 */
function usces_get_pdf_shipping_address( $pdf, $data, $y, $linetop, $leftside, $width, $lineheight ) {
	$options   = get_option( 'usces' );
	$applyform = usces_get_apply_addressform( $options['system']['addressform'] );
	$name      = '';
	$pref      = ( __( '-- Select --', 'usces' ) == $data->deliveri['pref'] || '-- Select --' == $data->deliveri['pref'] ) ? '' : $data->deliveri['pref'];

	switch ( $applyform ) {
		case 'JP':
			$pdf->SetXY( $leftside, $y );
			if ( ! empty( $data->deliveri['zipcode'] ) ) {
				$pdf->MultiCell( $width, $lineheight, usces_conv_euc( __( 'zip code', 'usces' ) . ' ' . $data->deliveri['zipcode'] ), 0, 'L' );
			}
			if ( ! empty( $pref ) || ! empty( $data->deliveri['address1'] ) || ! empty( $data->deliveri['address2'] ) || ! empty( $data->deliveri['address3'] ) ) {
				$pdf->MultiCell( $width, $lineheight, usces_conv_euc( $pref . $data->deliveri['address1'] . $data->deliveri['address2'] . ' ' . $data->deliveri['address3'] ), 0, 'L' );
			}
			break;
		case 'US':
		default:
			$pdf->SetXY( $leftside, $y );
			if ( ! empty( $pref ) || ! empty( $data->deliveri['address1'] ) || ! empty( $data->deliveri['address2'] ) || ! empty( $data->deliveri['address3'] ) ) {
				$pdf->MultiCell( $width, $lineheight, usces_conv_euc( $data->deliveri['address2'] . ' ' . $data->deliveri['address3'] . ' ' . $data->deliveri['address1'] . ' ' . $pref . ' ' . $data->deliveri['country'] ), 0, 'L' );
			}
			$y = $pdf->GetY() + $linetop;
			$pdf->SetXY( $leftside, $y );
			if ( ! empty( $data->deliveri['zipcode'] ) ) {
				$pdf->MultiCell( $width, $lineheight, usces_conv_euc( __( 'zip code', 'usces' ) . ' ' . $data->deliveri['zipcode'] ), 0, 'L' );
			}
			break;
	}
}

/**
 * My address
 *
 * @param object $pdf        TCPDF.
 * @param float  $lineheight Line height.
 */
function usces_get_pdf_myaddress( $pdf, $lineheight ) {
	$options   = get_option( 'usces' );
	$applyform = usces_get_apply_addressform( $options['system']['addressform'] );
	$name      = '';

	switch ( $applyform ) {
		case 'JP':
			$address = ( empty( $options['address2'] ) ) ? $options['address1'] : $options['address1'] . "\n" . $options['address2'];
			if( !empty( $options['zip_code'] ) ) {
				$pdf->MultiCell( 60, $lineheight, usces_conv_euc( __( 'zip code', 'usces' ) . ' ' . $options['zip_code'] ), 0, 'L' );
			}
			$pdf->MultiCell( 60, $lineheight, usces_conv_euc( $address ), 0, 'L' );
			break;
		case 'US':
		default:
			$address = ( empty( $options['address2'] ) ) ? $options['address1'] : $options['address2'] . "\n" . $options['address1'];
			$pdf->MultiCell( 60, $lineheight, usces_conv_euc( $address ), 0, 'L' );
			if ( ! empty( $options['zip_code'] ) ) {
				$pdf->MultiCell( 60, $lineheight, usces_conv_euc( __( 'zip code', 'usces' ) . ' ' . $options['zip_code'] ), 0, 'L' );
			}
			break;
	}
}

/**
 * Company name
 *
 * @param int    $order_id Order ID.
 * @param string $type     Custom field type.
 * @return string
 */
function usces_get_pdf_company( $order_id, $type ) {
	global $usces;

	if ( 'customer' === $type ) {
		$company_pre = 'cscs_';
	} elseif ( 'delivery' === $type ) {
		$company_pre = 'csde_';
	} else {
		return '';
	}
	$company_key = apply_filters( 'usces_filter_pdf_company_key', 'company' );
	$company     = $usces->get_order_meta_value( $company_pre . $company_key, $order_id );
	$meta        = usces_has_custom_field_meta( $type );
	if ( ! isset( $meta[ $company_key ] ) ) {
		$company = '';
	}
	return $company;
}
