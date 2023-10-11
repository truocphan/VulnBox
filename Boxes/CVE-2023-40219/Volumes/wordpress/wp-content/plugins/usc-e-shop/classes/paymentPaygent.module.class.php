<?php
/**
 * ペイジェント決済モジュール
 *
 * @package  Welcart
 * @author   Collne Inc.
 * @since    2.2.6
 * @version  1.0.0
 */

/**
 * Paygent Module.
 *
 * @package Welcart
 * @author  Collne Inc.
 * @since   2.2.6
 */
class Paygent_Module {

	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * マーチャントID
	 *
	 * @var string
	 */
	protected $merchant_id;

	/**
	 * 接続ID
	 *
	 * @var string
	 */
	protected $connect_id;

	/**
	 * 接続パスワード
	 *
	 * @var string
	 */
	protected $connect_password;

	/**
	 * クライアント証明書ファイル
	 *
	 * @var string
	 */
	protected $client_file;

	/**
	 * CA証明書ファイル
	 *
	 * @var string
	 */
	protected $ca_file;

	/**
	 * 電文バージョン
	 *
	 * @var string
	 */
	protected $telegram_version;

	/**
	 * カナ変換用 要求電文POSTパラメータ名
	 *
	 * @var array
	 */
	protected $replace_kana_param = array(
		'customer_family_name_kana',
		'customer_name_kana',
		'payment_detail_kana',
		'claim_kana',
		'receipt_name_kana',
	);

	/**
	 * カナ変換用 応答電文パラメータ名
	 *
	 * @var array
	 */
	public static $encording_kana_param = array(
		'customer_family_name',
		'customer_name',
		'customer_family_name_kana',
		'customer_name_kana',
		'payment_detail',
		'payment_detail_kana',
		'claim_kanji',
		'claim_kana',
		'acq_name',
		'issur_name',
		'receipt_name_kana',
		'description',
		'store_name',
		'buyer_name_kanji',
		'buyer_name_kana',
		'shipping_address_state',
		'shipping_address_city',
		'shipping_address_line2',
		'shipping_address_line1',
		'order_items_id',
		'order_items_title',
		'order_items_description',
	);

	/**
	 * Shift_JIS変換用 応答電文パラメータ名
	 *
	 * @var array
	 */
	public static $encording_sjis_param = array(
		'customer_family_name',
		'customer_name',
		'customer_family_name_kana',
		'customer_name_kana',
		'payment_detail',
		'payment_detail_kana',
		'claim_kanji',
		'claim_kana',
		'acq_name',
		'issur_name',
		'receipt_name_kana',
	);

	/**
	 * UTF-8対象電文
	 *
	 * @var array
	 */
	public static $telegram_kind_utf8 = array(
		'098',
		'380',
		'410',
		'411',
		'412',
		'413',
		'414',
		'415',
		'416',
		'417',
		'450',
	);

	/**
	 * 照会電文
	 *
	 * @var array
	 */
	protected $telegram_kind_ref = array(
		'027',
		'090',
	);

	/**
	 * 照会MAX件数
	 *
	 * @var int
	 */
	protected $select_max_cnt;

	/**
	 * 電文パラメータ
	 *
	 * @var array
	 */
	protected $telegram_param = array();

	/**
	 * 通信処理
	 *
	 * @var object
	 */
	protected $sender;

	/**
	 * 文字コード
	 *
	 * @var string
	 */
	protected $encoding = '';

	/**
	 * 処理結果
	 *
	 * @var object
	 */
	protected $response_data;

	/**
	 * 処理結果メッセージ
	 *
	 * @var string
	 */
	protected $result_message = '';

	/**
	 * 処理結果ステータス
	 *
	 * @var string
	 */
	protected $result_status;

	/**
	 * レスポンスコード
	 *
	 * @var string
	 */
	protected $response_code;

	/**
	 * レスポンス詳細
	 *
	 * @var array
	 */
	protected $response_detail;

	/**
	 * データ
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * 解析対象データ
	 *
	 * @var int
	 */
	protected $line;

	/**
	 * 次の読み出し開始位置
	 *
	 * @var int
	 */
	protected $current_pos;

	/**
	 * 最終読込み位置
	 *
	 * @var int
	 */
	protected $max_pos;

	/**
	 * 動作環境
	 *
	 * @var boolean
	 */
	protected $testmode;

	/**
	 * Construct.
	 */
	public function __construct() {
		$acting_settings        = $this->get_acting_settings();
		$this->testmode         = ( 'test' === $acting_settings['ope'] );
		$this->merchant_id      = $acting_settings['seq_merchant_id'];
		$this->connect_id       = $acting_settings['connect_id'];
		$this->connect_password = $acting_settings['connect_password'];
		$this->client_file      = $acting_settings['certificate_path'] . '/' . $acting_settings['client_file'];
		$this->ca_file          = $acting_settings['certificate_path'] . '/' . $acting_settings['ca_file'];
		$this->telegram_version = TELEGRAM_VERSION;
		$this->select_max_cnt   = 2000;
		$this->encoding         = 'UTF-8';
		$this->telegram_param   = array();
	}

	/**
	 * Return an instance of this class.
	 */
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * 共通ヘッダを設定
	 */
	public function set_req_header() {
		$this->telegram_param['merchant_id']      = $this->merchant_id;
		$this->telegram_param['connect_id']       = $this->connect_id;
		$this->telegram_param['connect_password'] = $this->connect_password;
		$this->telegram_param['telegram_version'] = $this->telegram_version;
	}

	/**
	 * 引数初期化
	 */
	public function init() {
		$this->telegram_param  = array();
		$this->data            = array();
		$this->response_data   = null;
		$this->result_message  = '';
		$this->result_status   = '';
		$this->response_code   = '';
		$this->response_detail = '';
	}

	/**
	 * 引数を設定
	 *
	 * @param string $key Key.
	 * @param string $value Value.
	 */
	public function req_put( $key, $value ) {
		$temp_val = $value;
		if ( null === $temp_val ) {
			$temp_val = '';
		}
		$this->telegram_param[ $key ] = $temp_val;
	}

	/**
	 * 引数を取得
	 *
	 * @param  string $key Key.
	 * @return string
	 */
	public function req_get( $key ) {
		return $this->telegram_param[ $key ];
	}

	/**
	 * 処理結果取得
	 *
	 * @return array
	 */
	public function get_response_data() {
		return $this->response_data;
	}

	/**
	 * 処理結果メッセージ取得
	 *
	 * @return array
	 */
	public function get_result_message() {
		return $this->result_message;
	}

	/**
	 * 処理結果ステータス取得
	 *
	 * @return string
	 */
	public function get_result_status() {
		return $this->result_status;
	}

	/**
	 * レスポンスコード取得
	 *
	 * @return array
	 */
	public function get_response_code() {
		return $this->response_code;
	}

	/**
	 * レスポンス詳細取得
	 *
	 * @return array
	 */
	public function get_response_detail() {
		return $this->response_detail;
	}

	/**
	 * 要求電文POST処理
	 *
	 * @param  string $telegram_kind 電文種別ID.
	 * @return mixed
	 */
	public function post( $telegram_kind ) {

		/* 共通ヘッダ設定 */
		$this->set_req_header();
		$this->telegram_param['telegram_kind'] = $telegram_kind;

		/* URL取得 */
		$url = $this->get_url( $telegram_kind );
		if ( TEREGRAM_PARAM_OUTSIDE_ERROR == $url ) {
			return TEREGRAM_PARAM_OUTSIDE_ERROR;
		}

		/* Https_Request_Sender 取得 */
		$this->sender = new Https_Request_Sender( $url );

		/* クライアント証明書パス設定 */
		$this->sender->set_client_certificate_path( $this->client_file );

		/* CA証明書パス設定 */
		$this->sender->set_ca_certificate_path( $this->ca_file );

		/* カナ変換処理 */
		$this->replace_telegram_kana();

		/* エンコード処理 */
		$this->encording_request_param();

		/* 電文長チェック */
		$telegram_length = $this->sender->get_telegram_length( $this->telegram_param );
		if ( TELEGRAM_LENGTH < $telegram_length ) {
			return TEREGRAM_PARAM_REQUIRED_ERROR;
		}

		/* Post */
		$rslt = $this->sender->post_request_body( $this->telegram_param );
		if ( ! ( true === $rslt ) ) {
			$this->result_message = $this->sender->get_result_message();
			return $rslt;
		}

		/* Get Response */
		$res_body = $this->sender->get_response_body();

		/* Create Response Data */
		$this->response_data = $this->create_response_data( $res_body, $telegram_kind );

		$this->result_message = $this->get_response_code() . ': ' . $this->get_response_detail();

		return $rslt;
	}

	/**
	 * ResponseData を作成
	 *
	 * @param  array  $res_body 処理結果.
	 * @param  string $telegram_kind 電文種別ID.
	 * @return object
	 */
	public function create_response_data( $res_body, $telegram_kind ) {
		$response_data = null;
		if ( in_array( $telegram_kind, $this->telegram_kind_ref, true ) ) {
			$res = $this->parse_reference( $res_body, $telegram_kind );
			if ( $res ) {
				$response_data = current( $this->data );
			}
		} else {
			$res = $this->parse_payment( $res_body, $telegram_kind );
			if ( $res ) {
				$response_data = current( $this->data );
			}
		}
		return $response_data;
	}

	/**
	 * 接続先URLを取得
	 *
	 * @param  string $telegram_kind 電文種別ID.
	 * @return string FALSE: 失敗(TEREGRAM_PARAM_OUTSIDE_ERROR)、成功:取得した URL
	 */
	public function get_url( $telegram_kind ) {
		$url = null;
		if ( $this->testmode ) {
			switch ( $telegram_kind ) {
				case PAYGENT_CREDIT: /* カード決済オーソリ */
				case PAYGENT_AUTH_CANCEL: /* カード決済オーソリキャンセル */
				case PAYGENT_CARD_COMMIT: /* カード決済売上 */
				case PAYGENT_CARD_COMMIT_CANCEL: /* カード決済売上キャンセル */
				case PAYGENT_CARD_COMMIT_AUTH_REVISE: /* カード決済補正オーソリ */
				case PAYGENT_CARD_COMMIT_REVISE: /* カード決済補正売上 */
				case PAYGENT_CARD_STOCK_SET: /* カード情報設定 */
				case PAYGENT_CARD_STOCK_DEL: /* カード情報削除 */
				case PAYGENT_CARD_STOCK_GET: /* カード情報照会 */
					$url = 'https://sandbox.paygent.co.jp/n/card/request';
					break;
				case PAYGENT_CARD_3DS2: /* 3Dセキュア2.0認証 */
					$url = 'https://sandbox.paygent.co.jp/n/threeds/request';
					break;
				case PAYGENT_CONVENI_NUM: /* コンビニ決済（番号方式）申込 */
					$url = 'https://sandbox.paygent.co.jp/n/conveni/request';
					break;
				case PAYGENT_ATM: /* ATM決済 */
					$url = 'https://sandbox.paygent.co.jp/n/atm/request';
					break;
				case PAYGENT_BANK: /* 銀行ネット決済 */
					$url = 'https://sandbox.paygent.co.jp/n/bank/request';
					break;
				case PAYGENT_BANK_ASP: /* 銀行ネット決済ASP申込 */
					$url = 'https://sandbox.paygent.co.jp/n/bank/requestasp';
					break;
				case PAYGENT_PAIDY_AUTH_CANCEL: /* Paidyオーソリキャンセル */
				case PAYGENT_PAIDY_CAPTURE: /* Paidy売上 */
				case PAYGENT_PAIDY_REFUND: /* Paidy返金 */
				case PAYGENT_PAIDY_REF: /* Paidy決済情報検証 */
					$url = 'https://sandbox.paygent.co.jp/n/paidy/request';
					break;
				case PAYGENT_DIFF_REF: /* 決済情報差分照会 */
					$url = 'https://sandbox.paygent.co.jp/n/ref/paynotice';
					break;
				case PAYGENT_REF: /* 決済情報照会 */
					$url = 'https://sandbox.paygent.co.jp/n/ref/paymentref';
					break;
			}
		} else {
			switch ( $telegram_kind ) {
				case PAYGENT_CREDIT: /* カード決済オーソリ */
				case PAYGENT_AUTH_CANCEL: /* カード決済オーソリキャンセル */
				case PAYGENT_CARD_COMMIT: /* カード決済売上 */
				case PAYGENT_CARD_COMMIT_CANCEL: /* カード決済売上キャンセル */
				case PAYGENT_CARD_COMMIT_AUTH_REVISE: /* カード決済補正オーソリ */
				case PAYGENT_CARD_COMMIT_REVISE: /* カード決済補正売上 */
				case PAYGENT_CARD_STOCK_SET: /* カード情報設定 */
				case PAYGENT_CARD_STOCK_DEL: /* カード情報削除 */
				case PAYGENT_CARD_STOCK_GET: /* カード情報照会 */
					$url = 'https://module.paygent.co.jp/n/card/request';
					break;
				case PAYGENT_CARD_3DS2: /* 3Dセキュア2.0認証 */
					$url = 'https://module.paygent.co.jp/n/threeds/request';
					break;
				case PAYGENT_CONVENI_NUM: /* コンビニ決済（番号方式）申込 */
					$url = 'https://module.paygent.co.jp/n/conveni/request';
					break;
				case PAYGENT_ATM: /* ATM決済 */
					$url = 'https://module.paygent.co.jp/n/atm/request';
					break;
				case PAYGENT_BANK: /* 銀行ネット決済 */
					$url = 'https://module.paygent.co.jp/n/bank/request';
					break;
				case PAYGENT_BANK_ASP: /* 銀行ネット決済ASP申込 */
					$url = 'https://module.paygent.co.jp/n/bank/requestasp';
					break;
				case PAYGENT_PAIDY_AUTH_CANCEL: /* Paidyオーソリキャンセル */
				case PAYGENT_PAIDY_CAPTURE: /* Paidy売上 */
				case PAYGENT_PAIDY_REFUND: /* Paidy返金 */
				case PAYGENT_PAIDY_REF: /* Paidy決済情報検証 */
					$url = 'https://module.paygent.co.jp/n/paidy/request';
					break;
				case PAYGENT_DIFF_REF: /* 決済情報差分照会 */
					$url = 'https://module.paygent.co.jp/n/ref/paynotice';
					break;
				case PAYGENT_REF: /* 決済情報照会 */
					$url = 'https://module.paygent.co.jp/n/ref/paymentref';
					break;
			}
		}
		if ( empty( $url ) ) {
			$url = TEREGRAM_PARAM_OUTSIDE_ERROR;
		}
		return $url;
	}

	/**
	 * 要求電文パラメータ 半角カナ 置換処理
	 */
	public function replace_telegram_kana() {
		foreach ( $this->telegram_param as $key => $value ) {
			if ( in_array( strtolower( $key ), $this->replace_kana_param ) ) {
				$this->telegram_param[ $key ] = String_Utitily::convert_katakana_zen2han( $value );
			}
		}
	}

	/**
	 * 要求電文パラメータ エンコード処理
	 */
	public function encording_request_param() {
		foreach ( $this->telegram_param as $key => $value ) {
			if ( in_array( strtolower( $key ), self::$encording_sjis_param ) ) {
				$this->telegram_param[ $key ] = String_Utitily::convert_encoding_sjis( $value );
			}
		}
	}

	/**
	 * 応答電文パラメータ エンコード処理
	 *
	 * @param  string $data パラメータ.
	 * @return string
	 */
	public static function encording_response_kana( $data ) {
		foreach ( $data as $key => $value ) {
			// if ( in_array( strtolower( $key ), self::$encording_kana_param ) ) {
			// $data[ $key ] = String_Utitily::convert_encoding_utf8( $value );
			// }
			foreach ( self::$encording_kana_param as $encording_kana ) {
				$pos = strpos( strtolower( $key ), $encording_kana );
				if ( false !== $pos ) {
					$data[ $key ] = String_Utitily::convert_encoding_utf8( $value );
				}
			}
		}
		return $data;
	}

	/**
	 * 応答電文を分解
	 *
	 * @param  array  $body レスポンスボディ.
	 * @param  string $telegram_kind 電文種別ID.
	 * @return boolean TRUE: 成功、他：エラーコード
	 */
	public function parse_payment( $body, $telegram_kind ) {

		/* 保持データを初期化 */
		$map = array();

		/* "_html" キー存在フラグ */
		$html_key_flg = false;

		/* "_html" キー値 */
		$html_key = '';

		/* "_html" キー出現以後のデータ保持 */
		$html_value = '';

		$lines = explode( LINE_SEPARATOR, $body );

		foreach ( $lines as $i => $line ) {
			$line_item = String_Utitily::split( $line, PROPERTIES_REGEX, PROPERTIES_REGEX_COUNT );

			/* 読込終了 */
			$tmp_len = strlen( $line_item[0] ) - strlen( HTML_ITEM );
			if ( 0 <= $tmp_len && strpos( $line_item[0], HTML_ITEM, $tmp_len ) === $tmp_len ) {
				/* Key が "_html" の場合 */
				$html_key     = $line_item[0];
				$html_key_flg = true;
			}
			if ( $html_key_flg ) {
				if ( ! ( strlen( $line_item[0] ) - strlen( HTML_ITEM ) >= 0 && strpos( $line_item[0], HTML_ITEM, strlen( $line_item[0] ) - strlen( HTML_ITEM ) ) === strlen( $line_item[0] ) - strlen( HTML_ITEM ) ) ) {
					/* "_html" Key が読み取られた場合 */
					$html_value .= $line;
					$html_value .= LINE_SEPARATOR;
				}
			} else {
				if ( 1 < count( $line_item ) ) {
					if ( RESPONSEDATA_RESULT == $line_item[0] ) {
						/* 処理結果を設定 */
						$this->result_status = $line_item[1];
					} elseif ( RESPONSEDATA_RESPONSE_CODE == $line_item[0] ) {
						/* レスポンスコードを設定 */
						$this->response_code = $line_item[1];
					} elseif ( RESPONSEDATA_RESPONSE_DETAIL == $line_item[0] ) {
						/* レスポンス詳細を設定 */
						if ( in_array( $telegram_kind, self::$telegram_kind_utf8 ) ) {
							$this->response_detail = $line_item[1];
						} else {
							$this->response_detail = String_Utitily::convert_encoding_utf8( $line_item[1] );
						}
					} else {
						/* Mapに設定 */
						$map[ $line_item[0] ] = $line_item[1];
					}
				}
			}
		}

		if ( $html_key_flg ) {
			/* "_html" Key が出現した場合、設定 */
			if ( strlen( LINE_SEPARATOR ) <= strlen( $html_value ) ) {
				if ( strpos( $html_value, LINE_SEPARATOR, strlen( $html_value ) - strlen( LINE_SEPARATOR ) ) === strlen( $html_value ) - strlen( LINE_SEPARATOR ) ) {
					$html_value = substr( $html_value, 0, strlen( $html_value ) - strlen( LINE_SEPARATOR ) );
				}
			}
			$map[ $html_key ] = $html_value;
		}

		if ( 0 < count( $map ) ) {
			/* Map が設定されている場合 */
			$map          = self::encording_response_kana( $map );
			$this->data[] = $map;
		}

		if ( String_Utitily::is_empty( $this->result_status ) ) {
			return false;
		}

		return true;
	}

	/**
	 * ResponseData を分解
	 *
	 * @param  array  $body レスポンス.
	 * @param  string $telegram_kind 電文種別ID.
	 * @return mixed TRUE:成功、他：エラーコード
	 */
	public function parse_reference( $body, $telegram_kind ) {

		/* 保持データを初期化 */
		$map = array();

		$lines = explode( LINE_SEPARATOR, $body );

		foreach ( $lines as $i => $line ) {
			$line_item     = $this->parse_csv_data( $line );
			$line_item_cnt = count( $line_item );
			if ( 0 < $line_item_cnt ) {
				if ( LINENO_HEADER == $line_item[ LINE_RECORD_DIVISION ] ) {
					/* ヘッダー部の行の場合 */
					if ( LINE_HEADER_RESULT < $line_item_cnt ) {
						/* 処理結果を設定 */
						$this->result_status = $line_item[ LINE_HEADER_RESULT ];
					}
					if ( LINE_HEADER_RESPONSE_CODE < $line_item_cnt ) {
						/* レスポンスコードを設定 */
						$this->response_code = $line_item[ LINE_HEADER_RESPONSE_CODE ];
					}
					if ( LINE_HEADER_RESPONSE_DETAIL < $line_item_cnt ) {
						/* レスポンス詳細を設定 */
						if ( in_array( $telegram_kind, self::$telegram_kind_utf8 ) ) {
							$this->response_detail = $line_item[ LINE_HEADER_RESPONSE_DETAIL ];
						} else {
							$this->response_detail = String_Utitily::convert_encoding_utf8( $line_item[ LINE_HEADER_RESPONSE_DETAIL ] );
						}
					}
				} elseif ( LINENO_DATA_HEADER == $line_item[ LINE_RECORD_DIVISION ] ) {
					/* データヘッダー部の行の場合 */
					$this->data_header = array();
					for ( $i = 1; $i < $line_item_cnt; $i++ ) {
						/* データヘッダーを設定（レコード区分は除く） */
						$this->data_header[] = $line_item[ $i ];
					}
				} elseif ( LINENO_DATA == $line_item[ LINE_RECORD_DIVISION ] ) {
					/* データ部の行の場合、データヘッダー部が既に展開済みである事を想定 */
					$map = array();

					if ( count( $this->data_header ) == ( $line_item_cnt - 1 ) ) {
						/* データヘッダー数と、データ項目数（レコード区分除く）は一致 */
						for ( $i = 1; $i < $line_item_cnt; $i++ ) {
							/* 対応するデータヘッダーを Key に、Mapへ設定 */
							$map[ $this->data_header[ $i - 1 ] ] = $line_item[ $i ];
						}
					} else {
						/* データヘッダー数と、データ項目数が一致しない場合 */
						return false;
					}

					if ( 0 < count( $map ) ) {
						/* Map が設定されている場合 */
						$map          = self::encording_response_kana( $map );
						$this->data[] = $map;
					}
				} elseif ( LINENO_TRAILER == $line_item[ LINE_RECORD_DIVISION ] ) {
					/* トレーラー部の行の場合 */
					if ( LINE_TRAILER_DATA_COUNT < $line_item_cnt ) {
						/* データサイズ */
					}
				}
			}
		}

		if ( String_Utitily::is_empty( $this->result_status ) ) {
			/* 処理結果が 空文字 もしくは null の場合 */
			return false;
		}

		return true;
	}

	/**
	 * CSVデータ文字列から項目データ配列を取得する
	 *
	 * @param  string $line 解析対象文字列（1行分のデータ）.
	 * @return array データ配列
	 */
	private function parse_csv_data( $line ) {
		if ( false == isset( $line ) ) {
			return array();
		}

		$this->line        = $line;
		$this->max_pos     = strlen( $this->line );
		$this->current_pos = 0;

		/* 項目データを格納する */
		$items = array();
		/* 囲み文字あり／なしの状態判定フラグ */
		$exist_envelope = false;

		while ( $this->current_pos <= $this->max_pos ) {
			/* データ区切り位置を取得する */
			$end_pos = $this->get_end_position( $this->current_pos );
			/* １項目分のデータを読み取る */
			$temp     = substr( $line, $this->current_pos, $end_pos - $this->current_pos );
			$temp_cnt = strlen( $temp );

			$work = '';
			/* 項目データなしの場合 */
			if ( 0 == $temp_cnt ) {
				$work = '';
			} else {
				/* 囲い文字があるかチェックする */
				if ( CSV_DEF_ITEM_ENVELOPE == $temp[0] ) {
					$exist_envelope = true;
				}

				$is_data = false;
				for ( $i = 0; $i < $temp_cnt; ) {
					$chr_tmp = $temp[ $i ];
					if ( $exist_envelope && CSV_DEF_ITEM_ENVELOPE == $temp[ $i ] ) {
						$i++;
						if ( $is_data ) {
							if ( ( $i < strlen( $temp ) ) && ( CSV_DEF_ITEM_ENVELOPE == $temp[ $i ] ) ) {
								/* 囲み文字が２つ続けて現れたときは、文字データとして取得する */
								$work .= $temp[ $i++ ];
							} else {
								$is_data = ! $is_data;
							}
						} else {
							$is_data = ! $is_data;
						}
					} else {
						$work .= $temp[ $i++ ];
					}
				}
			}
			/* １項目分のデータを登録する */
			$items[] = $work;

			/* 次の読取位置の更新 */
			$this->current_pos = $end_pos + 1;
		}
		return $items;
	}

	/**
	 * データ区切り位置を返す
	 *
	 * @param  int $start 検索開始位置.
	 * @return int １データの区切り位置を返す
	 */
	private function get_end_position( $start ) {

		/* 文字列／文字列外の状態判定フラグ */
		$state = false;
		/* 囲み文字あり／なしの状態判定フラグ */
		$exist_envelope = false;
		/* 読み込んだ文字 */
		$ch = null;
		/* 区切り位置 */
		$end = 0;

		if ( $start >= $this->max_pos ) {
			return $start;
		}

		/* 囲み文字の有無判定 */
		if ( CSV_DEF_ITEM_ENVELOPE == $this->line[ $start ] ) {
			$exist_envelope = true;
		}

		$end = $start;

		while ( $end < $this->max_pos ) {
			/* １文字読み込む */
			$ch = $this->line[ $end ];
			/* 文字の判定 */
			if ( false == $state && CSV_DEF_SEPARATOR == $ch ) {
				/* 文字列中の区切り文字でなければ、データ区切り*/
				break;
			} elseif ( $exist_envelope && CSV_DEF_ITEM_ENVELOPE == $ch ) {
				/* 囲み文字が現れたら、文字列／文字列外の状態判定を反転 */
				if ( $state ) {
					$state = false;
				} else {
					$state = true;
				}
			}
			/* 文字位置のカウントアップ */
			$end++;
		}
		return $end;
	}

	/**
	 * 決済オプション取得
	 *
	 * @return array
	 */
	private function get_acting_settings() {
		global $usces;

		$acting_settings = ( isset( $usces->options['acting_settings']['paygent'] ) ) ? $usces->options['acting_settings']['paygent'] : array();
		return $acting_settings;
	}
}

/**
 * Https Request Sender.
 *
 * @package Welcart
 * @author  Collne Inc.
 * @since   2.3
 */
class Https_Request_Sender {

	/**
	 * KeyStore Password
	 *
	 * @var string
	 */
	protected $keystore_password = 'changeit';

	/**
	 * レスポンスヘッダ
	 *
	 * @var object
	 */
	protected $response_header = null;

	/**
	 * レスポンスボディ
	 *
	 * @var object
	 */
	protected $response_body = null;

	/**
	 * ステータスコード
	 *
	 * @var string
	 */
	protected $status_code;

	/**
	 * 接続先 URL
	 *
	 * @var string
	 */
	protected $url;

	/**
	 * クライアント証明書パス
	 *
	 * @var string
	 */
	protected $client_certificate_path;

	/**
	 * 認証局証明書パス
	 *
	 * @var string
	 */
	protected $ca_certificate_path;

	/**
	 * SSL通信用ソケット
	 *
	 * @var object
	 */
	protected $ch;

	/**
	 * タイムアウト値
	 *
	 * @var int
	 */
	protected $timeout;

	/**
	 * 処理結果メッセージ
	 *
	 * @var string
	 */
	protected $result_message = '';

	/**
	 * コンストラクタ
	 * 接続先URLを設定
	 *
	 * @param string $url 接続先URL.
	 */
	public function __construct( $url ) {
		$this->url             = $url;
		$this->response_body   = null;
		$this->response_header = null;
		$this->timeout         = 35;
	}

	/**
	 * クライアント証明書パスを設定
	 *
	 * @param string $file_name クライアント証明書パス.
	 */
	public function set_client_certificate_path( $file_name ) {
		$this->client_certificate_path = $file_name;
	}

	/**
	 * 認証局証明書パスを設定
	 *
	 * @param string $file_name 認証局証明書パス.
	 */
	public function set_ca_certificate_path( $file_name ) {
		$this->ca_certificate_path = $file_name;
	}

	/**
	 * 処理結果メッセージ
	 *
	 * @return string
	 */
	public function get_result_message() {
		return $this->result_message;
	}

	/**
	 * Postを実施
	 *
	 * @param  array $form_data Map.
	 * @return mixed TRUE:成功、他:エラーコード
	 */
	public function post_request_body( $form_data ) {
		$this->init_curl();
		$ret_code = $this->send( $form_data );
		$this->close_curl();
		return $ret_code;
	}

	/**
	 * 受信データを返す
	 *
	 * @return string
	 */
	public function get_response_body() {
		return $this->response_body;
	}

	/**
	 * 電文長取得
	 *
	 * @param  string $form_data 電文.
	 * @return int 電文長(byte)
	 */
	public function get_telegram_length( $form_data ) {
		if ( null == $form_data ) {
			return 0;
		}

		$sb  = $this->url;
		$sb .= '?';
		foreach ( $form_data as $key => $value ) {
			$sb .= $key;
			$sb .= '=';
			$sb .= $value;
			$sb .= '&';
		}

		$rs = '';
		if ( 0 < strlen( $sb ) ) {
			$rs = substr( $sb, 0, strlen( $sb ) - 1 );
		}
		return strlen( $rs );
	}

	/**
	 * 要求電文を作成
	 *
	 * @param  array $form_data 要求電文.
	 * @return string 作成した要求電文（URL）
	 */
	public function convert2urlencoded_string( $form_data ) {
		if ( null == $form_data ) {
			return '';
		}

		$encoded_string = '';
		foreach ( $form_data as $key => $value ) {
			$tmp             = $key;
			$encoded_string .= urlencode( $tmp );
			$encoded_string .= '=';
			$tmp             = $value;
			$encoded_string .= urlencode( $tmp );
			$encoded_string .= '&';
		}

		$rs = '';
		if ( 0 < strlen( $encoded_string ) ) {
			$rs = substr( $encoded_string, 0, strlen( $encoded_string ) - 1 );
		}
		return $rs;
	}

	/**
	 * 接続のための初期化処理
	 *
	 * @return mixed
	 */
	public function init_curl() {
		$rslt = true;

		/* 初期化 */
		$this->ch = curl_init( $this->url );

		$rslt = $rslt && curl_setopt( $this->ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0 );
		$rslt = $rslt && curl_setopt( $this->ch, CURLOPT_RETURNTRANSFER, true );
		$rslt = $rslt && curl_setopt( $this->ch, CURLOPT_POST, true );
		$rslt = $rslt && curl_setopt( $this->ch, CURLOPT_HEADER, true );

		/* 証明書 */
		$rslt = $rslt && curl_setopt( $this->ch, CURLOPT_SSL_VERIFYHOST, false );

		/* クライアント証明書 */
		$rslt = $rslt && curl_setopt( $this->ch, CURLOPT_SSLCERT, $this->client_certificate_path );
		$rslt = $rslt && curl_setopt( $this->ch, CURLOPT_SSLKEYPASSWD, $this->keystore_password );

		/* CA証明書 */
		$rslt = $rslt && curl_setopt( $this->ch, CURLOPT_SSL_VERIFYPEER, true );
		$rslt = $rslt && curl_setopt( $this->ch, CURLOPT_CAINFO, $this->ca_certificate_path );

		/* タイムアウト */
		$rslt = $rslt && curl_setopt( $this->ch, CURLOPT_TIMEOUT, $this->timeout );

		return $rslt;
	}

	/**
	 * リクエスト生成と送信
	 *
	 * @param  array $form_data Map 要求電文.
	 * @return mixed TRUE:成功、他:エラーコード
	 */
	public function send( $form_data ) {
		$query = $this->convert2urlencoded_string( $form_data, false );

		$header   = array();
		$header[] = 'Content-Type=application/x-www-form-urlencoded';
		$header[] = 'charset=Windows-31J';
		$header[] = 'Content-Length: ' . ( String_Utitily::is_empty( $query ) ? '0' : strlen( $query ) );
		$header[] = 'User-Agent: curl_php';
		curl_setopt( $this->ch, CURLOPT_HTTPHEADER, $header );
		curl_setopt( $this->ch, CURLOPT_POSTFIELDS, $query );

		$str = curl_exec( $this->ch );
		if ( false === $str && 0 != curl_errno( $this->ch ) ) {
			return $this->proc_error();
		}
		$ret_code = $this->parse_response( $str );
		return $ret_code;
	}

	/**
	 * Curl のエラー処理
	 *
	 * @return mixed True:問題なし、他：エラーコード
	 */
	public function proc_error() {
		$error_no  = curl_errno( $this->ch );
		$error_msg = $error_no . ': ' . curl_error( $this->ch );
		$ret_code  = true;

		if ( CURLE_COULDNT_CONNECT >= $error_no ) {
			$ret_code = KS_CONNECT_ERROR;
		} elseif ( CURLE_COULDNT_CONNECT == $error_no ) {
			$ret_code = KS_CONNECT_ERROR;
		} elseif ( CURLE_SSL_CERTPROBLEM == $error_no ) {
			$ret_code = CERTIFICATE_ERROR;
		} elseif ( CURLE_SSL_CACERT == $error_no ) {
			$ret_code = CERTIFICATE_ERROR;
		} elseif ( CURLE_SSL_CACERT_BADFILE == $error_no ) {
			$ret_code = CERTIFICATE_ERROR;
		} elseif ( CURLE_HTTP_RETURNED_ERROR == $error_no ) {
			$ret_code = KS_CONNECT_ERROR;
		} else {
			$ret_code = KS_CONNECT_ERROR;
		}
		$this->result_message = $ret_code . ': ' . $error_msg;

		if ( ! file_exists( $this->client_certificate_path ) ) {
			$this->result_message .= '(file is not exists: ' . $this->client_certificate_path . ')';
		} elseif ( ! is_readable( $this->client_certificate_path ) ) {
			$this->result_message .= '(file is not readable: ' . $this->client_certificate_path . ')';
		}
		if ( ! file_exists( $this->ca_certificate_path ) ) {
			$this->result_message .= '(file is not exists: ' . $this->ca_certificate_path . ')';
		} elseif ( ! is_readable( $this->ca_certificate_path ) ) {
			$this->result_message .= '(file is not readable: ' . $this->ca_certificate_path . ')';
		}
		return $ret_code;
	}

	/**
	 * レスポンスを受信
	 *
	 * @param  string $data  レスポンス文字列.
	 * @return mixed TRUE:成功、他:エラーコード
	 */
	public function parse_response( $data ) {

		/* レスポンス受信 */
		$line           = null;
		$ret_code       = HTTP_STATUS_INIT_VALUE;
		$res_body_start = 0;
		$lines          = explode( LINE_SEPARATOR, $data );

		/* ヘッダまでを読み込む */
		foreach ( $lines as $i => $line ) {
			if ( String_Utitily::is_empty( $line ) ) {
				break;
			}
			$res_body_start += strlen( $line ) + strlen( LINE_SEPARATOR );
			if ( HTTP_STATUS_INIT_VALUE === $ret_code ) {
				/* ステータスの解析 */
				$ret_code = $this->parse_status_line( $line );
				if ( true === $ret_code ) {
					continue;
				}
				return $ret_code;
			}
			/* ヘッダの解析 */
			if ( ! $this->parse_response_header( $line ) ) {
				continue;
			}
		}

		$info                = curl_getinfo( $this->ch );
		$res_body_start      = -( $info['size_download'] );
		$this->response_body = substr( $data, $res_body_start );

		return true;
	}

	/**
	 * ステータスラインを解析
	 *
	 * @param  string $line ステータスライン.
	 * @return mixed TRUE:成功、他:エラーコード
	 */
	public function parse_status_line( $line ) {
		if ( String_Utitily::is_empty( $line ) ) {
			return KS_CONNECT_ERROR;
		}

		$status_line = String_Utitily::split( $line, ' ', 3 );

		if ( String_Utitily::is_numeric( $status_line[1] ) ) {
			$this->status_code = intval( $status_line[1] );
		} else {
			return KS_CONNECT_ERROR;
		}
		if ( strpos( $status_line[0], 'HTTP/' ) != 0 || ! String_Utitily::is_numeric_length( $status_line[1], 3 ) ) {
			return KS_CONNECT_ERROR;
		}
		if ( ! ( ( 200 <= $this->status_code ) && ( $this->status_code <= 206 ) ) ) {
			return KS_CONNECT_ERROR;
		}
		return true;
	}

	/**
	 * レスポンスヘッダを一行解析して、内部に格納
	 * レスポンスヘッダの値が存在しない場合は、nullを設定
	 *
	 * @param  string $line サーバから受け取ったレスポンス行.
	 * @return boolean true=ヘッダ解析・格納完了, false=ヘッダではない（ヘッダ部終了）
	 */
	public function parse_response_header( $line ) {
		if ( String_Utitily::is_empty( $line ) ) {
			return false;
		}

		$header_str = String_Utitily::split( $line, ':', 2 );
		if ( null == $this->response_header ) {
			$this->response_header = array();
		}
		if ( 1 == count( $header_str ) || 0 == strlen( trim( $header_str[1] ) ) ) {
			$this->response_header[ $header_str[0] ] = null;
		} else {
			$this->response_header[ $header_str[0] ] = trim( $header_str[1] );
		}
		return true;
	}

	/**
	 * Close curl
	 */
	public function close_curl() {
		if ( null != $this->ch ) {
			curl_close( $this->ch );
			$this->ch = null;
		}
	}
}

/**
 * String Utitily.
 *
 * @package Welcart
 * @author  Collne Inc.
 * @since   2.3
 */
class String_Utitily {

	/**
	 * 共通で変換するカタカナ文字列のマッピング情報を格納しているマップ
	 *
	 * @var array
	 */
	protected $katakana_map = array();

	/**
	 * 使用可能な全角カタカナ
	 *
	 * @var array
	 */
	protected $zen_kana = array(
		'ア',
		'イ',
		'ウ',
		'エ',
		'オ',
		'カ',
		'キ',
		'ク',
		'ケ',
		'コ',
		'サ',
		'シ',
		'ス',
		'セ',
		/*'ソ', */
		'タ',
		'チ',
		'ツ',
		'テ',
		'ト',
		'ナ',
		'ニ',
		'ヌ',
		'ネ',
		'ノ',
		'ハ',
		'ヒ',
		'フ',
		'ヘ',
		'ホ',
		'マ',
		'ミ',
		'ム',
		'メ',
		'モ',
		'ヤ',
		'ユ',
		'ヨ',
		'ラ',
		'リ',
		'ル',
		'レ',
		'ロ',
		'ワ',
		'ヲ',
		'ン',
		'ガ',
		'ギ',
		'グ',
		'ゲ',
		'ゴ',
		'ザ',
		'ジ',
		'ズ',
		'ゼ',
		'ゾ',
		'ダ',
		'ヂ',
		'ヅ',
		'デ',
		'ド',
		'バ',
		'ビ',
		'ブ',
		'ベ',
		'ボ',
		'ヴ',
		'パ',
		'ピ',
		'プ',
		'ペ',
		'ポ',
		'ァ',
		'ィ',
		'ゥ',
		'ェ',
		'ォ',
		'ャ',
		'ュ',
		'ョ',
		'ッ',
		'ー',
	);

	/**
	 * 使用可能な半角カタカナ
	 *
	 * @var array
	 */
	protected $han_kana = array(
		'ｱ',
		'ｲ',
		'ｳ',
		'ｴ',
		'ｵ',
		'ｶ',
		'ｷ',
		'ｸ',
		'ｹ',
		'ｺ',
		'ｻ',
		'ｼ',
		'ｽ',
		'ｾ',
		'ｿ',
		'ﾀ',
		'ﾁ',
		'ﾂ',
		'ﾃ',
		'ﾄ',
		'ﾅ',
		'ﾆ',
		'ﾇ',
		'ﾈ',
		'ﾉ',
		'ﾊ',
		'ﾋ',
		'ﾌ',
		'ﾍ',
		'ﾎ',
		'ﾏ',
		'ﾐ',
		'ﾑ',
		'ﾒ',
		'ﾓ',
		'ﾔ',
		'ﾕ',
		'ﾖ',
		'ﾗ',
		'ﾘ',
		'ﾙ',
		'ﾚ',
		'ﾛ',
		'ﾜ',
		'ｦ',
		'ﾝ',
		'ｶﾞ',
		'ｷﾞ',
		'ｸﾞ',
		'ｹﾞ',
		'ｺﾞ',
		'ｻﾞ',
		'ｼﾞ',
		'ｽﾞ',
		'ｾﾞ',
		'ｿﾞ',
		'ﾀﾞ',
		'ﾁﾞ',
		'ﾂﾞ',
		'ﾃﾞ',
		'ﾄﾞ',
		'ﾊﾞ',
		'ﾋﾞ',
		'ﾌﾞ',
		'ﾍﾞ',
		'ﾎﾞ',
		'ｳﾞ',
		'ﾊﾟ',
		'ﾋﾟ',
		'ﾌﾟ',
		'ﾍﾟ',
		'ﾎﾟ',
		'ｧ',
		'ｨ',
		'ｩ',
		'ｪ',
		'ｫ',
		'ｬ',
		'ｭ',
		'ｮ',
		'ｯ',
		'ｰ',
	);

	/**
	 * Construct.
	 */
	public function __construct() {
		$zen_kana_cnt = count( $this->zen_kana );
		if ( $zen_kana_cnt == count( $this->katakana_map ) ) {
			return;
		}
		for ( $i = 0; $i < $zen_kana_cnt; $i++ ) {
			$this->katakana_map[ $this->zen_kana[ $i ] ] = $this->han_kana[ $i ];
		}
	}

	/**
	 * パラメータが null または空文字かを判断する
	 *
	 * @param  string $str 判定する文字列.
	 * @return boolean nullまたは空文字の場合、true
	 */
	public static function is_empty( $str ) {
		return ( ! isset( $str ) || strlen( trim( $str ) ) <= 0 );
	}

	/**
	 * Split(分割数制限版)
	 *
	 * @param  string $str 分割対象文字列.
	 * @param  string $delim 区切り文字.
	 * @param  int    $limit 結果の閾値.
	 * @return array  分割後の文字配列
	 */
	public static function split( $str, $delim, $limit = -1 ) {
		$delim_len = strlen( $delim );
		$pos       = 0;
		$index     = 0;
		$list      = array();
		if ( 0 != $delim_len ) {
			while ( ! ( ( $index = strpos( $str, $delim, $pos ) ) === false ) ) {
				$list[] = substr( $str, $pos, $index - $pos );
				$pos    = $index + $delim_len;
				if ( $pos >= strlen( $str ) ) {
					break;
				}
			}
			if ( $pos == strlen( $str ) ) {
				$list[] = ''; /* the last is the delimiter. */
			} elseif ( $pos < strlen( $str ) ) {
				$list[] = substr( $str, $pos );
			}
		} else {
			$str_len = strlen( $str );
			for ( $i = 0; $i < $str_len; $i++ ) {
				$c      = $str[ $i ];
				$list[] = '' . $c;
			}
		}

		$rs = &$list;

		$rs_cnt = count( $rs );
		if ( ( 0 < $limit ) && ( $limit < $rs_cnt ) ) {
			/* limit より、分割数が多い場合、分割数を limit に合わせる */
			$temp = array();
			$pos  = 0;
			for ( $i = 0; $i < $limit - 1; $i++ ) {
				$temp[] = $rs[ $i ];
				$pos   += strlen( $rs[ $i ] ) + $delim_len;
			}
			$temp[ $limit - 1 ] = substr( $str, $pos );
			for ( $i = $limit; $i < $rs_cnt; $i++ ) {
				$sb = $temp[ $limit - 1 ];
			}
			$rs = $temp;
		}
		return $rs;
	}

	/**
	 * 数値判定
	 *
	 * @param  string $str 数値判定対象文字列.
	 * @return boolean true=数値 false=数値以外
	 */
	public static function is_numeric( $str ) {
		$rb = is_numeric( $str );
		return $rb;
	}

	/**
	 * 数値、桁数判定
	 *
	 * @param  string $str 数値判定対象文字列.
	 * @param  int    $len 判定対象 Length.
	 * @return boolean true=桁数内数値 false=数値でない or 桁数違い
	 */
	public static function is_numeric_length( $str, $len ) {
		$rb = false;
		if ( self::is_numeric( $str ) ) {
			if ( strlen( $str ) == $len ) {
				$rb = true;
			}
		}
		return $rb;
	}

	/**
	 * 全角カタカナ文字を半角カタカナの該当文字に変換する
	 * 指定された文字列がnullの場合はnullを返す
	 *
	 * @param  string $src 変換する元の文字列.
	 * @return string 変換後の文字列
	 */
	public static function convert_katakana_zen2han( $src ) {
		if ( null == $src ) {
			return null;
		}
		$str = mb_convert_kana( $src, 'kV', 'UTF-8' );
		return $str;
	}

	/**
	 * 指定された文字列を指定されたマッピング情報に基づき変換した結果の文字列を返す
	 * 指定された文字列が null の場合は null を返す
	 *
	 * @param  string $src 変換する元の文字列.
	 * @param  array  $convert_map 変換の対象となる文字と変換後のマッピング情報を格納しているマップ.
	 * @return string 変換後の文字列
	 */
	public static function convert( $src, $convert_map ) {
		if ( null == $src ) {
			return null;
		}
		$result = '';
		$chars  = $this->to_chars( $src );
		foreach ( $chars as $c ) {
			if ( array_key_exists( $c, $convert_map ) ) {
				$result .= $convert_map[ $c ];
			} else {
				$result .= $c;
			}
		}
		return $result;
	}

	/**
	 * 文字列を1文字づつ配列に変換
	 *
	 * @param  string $str 変換する元の文字列.
	 * @return array
	 */
	public static function to_chars( $str ) {
		$str_len = mb_strlen( $str );
		$chars   = array();
		for ( $i = 0; $i < $str_len; $i++ ) {
			$out     = mb_substr( $str, $i, 1 );
			$chars[] = $out;
			$intx    = 0;
		}
		return $chars;
	}

	/**
	 * UTF8にエンコードする
	 *
	 * @param  string $string エンコード前の文字列.
	 * @return string エンコード後の文字列
	 */
	public static function convert_encoding_utf8( $string ) {
		$string = mb_convert_encoding( $string, 'UTF-8', 'sjis-win' );
		return $string;
	}

	/**
	 * Shift_JISにエンコードする
	 *
	 * @param  string $string エンコード前の文字列.
	 * @return string エンコード後の文字列
	 */
	public static function convert_encoding_sjis( $string ) {
		$string = mb_convert_encoding( $string, 'sjis-win', 'UTF-8' );
		return $string;
	}
}
