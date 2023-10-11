<?php
/**
 * Paygent Configuration Data.
 *
 * @package  Welcart
 * @author   Collne Inc.
 * @version  1.0.0
 * @since    2.2.6
 */

const TELEGRAM_KEY_LENGTH   = 8190; /* 電文パラメータ Key Length */
const TELEGRAM_VALUE_LENGTH = 102400; /* 電文パラメータ Value Length */
const TELEGRAM_LENGTH       = 102400; /* 電文長：102400byte（ファイル決済以外） */

const RESULT_STATUS_NORMAL = '0'; /* 処理結果：0 */
const RESULT_STATUS_ERROR  = '1'; /* 処理結果：1 */
const RESULT_STATUS_ALL    = 'ALL';
const RESPONSE_CODE_9003   = '9003'; /* レスポンスコード：9003 */

const HTTP_STATUS_INIT_VALUE = -1; /* HTTPステータスコード変数の初期値 */

const TELEGRAM_KIND_FILE_PAYMENT_RES = '201'; /* 電文種別：201（ファイル決済結果照会） */

const TELEGRAM_KIND_SEPARATOR   = ','; /* 照会系電文種別の区切り文字 */
const TELEGRAM_KIND_FIRST_CHARS = 2; /* 電文種別の先頭桁数（接続先URL取得） */

const PROPERTIES_REGEX       = '='; /* 応答電文用区切り文字 */
const PROPERTIES_REGEX_COUNT = 2; /* 応答電文用区切り数 */
const LINE_SEPARATOR         = "\r\n"; /* 改行文字 */
const HTML_ITEM              = '_html'; /* HTML項目 */

const LINENO_HEADER      = '1'; /* 行番号（ヘッダー部）= "1" */
const LINENO_DATA_HEADER = '2'; /* 行番号（データヘッダー部）: "2" */
const LINENO_DATA        = '3'; /* 行番号（データ部）: "3" */
const LINENO_TRAILER     = '4'; /* 行番号（トレーラー部）: "4" */

const LINE_RECORD_DIVISION        = 0; /* レコード区分 位置: 0 */
const LINE_HEADER_RESULT          = 1; /* ヘッダー部 処理結果 位置 1 */
const LINE_HEADER_RESPONSE_CODE   = 2; /* ヘッダー部 レスポンスコード 位置: 2 */
const LINE_HEADER_RESPONSE_DETAIL = 3; /* ヘッダー部 レスポンス詳細 位置: 3 */
const LINE_TRAILER_DATA_COUNT     = 1; /* トレーラー部 データ件数 位置: 1 */

const RESPONSEDATA_RESULT          = 'result'; /* 処理結果 */
const RESPONSEDATA_RESPONSE_CODE   = 'response_code'; /* レスポンスコード */
const RESPONSEDATA_RESPONSE_DETAIL = 'response_detail'; /* レスポンス詳細 */

const MODULE_PARAM_REQUIRED_ERROR   = 'E02001'; /* モジュールパラメータエラー */
const TEREGRAM_PARAM_REQUIRED_ERROR = 'E02002'; /* 電文要求パラメータエラー */
const TEREGRAM_PARAM_OUTSIDE_ERROR  = 'E02003'; /* 電文要求パラメータ固定値想定外エラー */
const CERTIFICATE_ERROR             = 'E02004'; /* 証明書エラー */
const KS_CONNECT_ERROR              = 'E02005'; /* 決済センター接続エラー */
const RESPONSE_TYPE_ERROR           = 'E02007'; /* 応答対応種別エラー */
const RESOURCE_FILE_NOT_FOUND_ERROR = 'E01001'; /* 設定ファイルなしエラー */
const RESOURCE_FILE_REQUIRED_ERROR  = 'E01002'; /* 設定ファイル不正エラー */
const OTHER_ERROR                   = 'E01901'; /* その他のエラー */
const CSV_OUTPUT_ERROR              = 'E01004'; /* CSV出力エラー */
const FILE_PAYMENT_ERROR            = 'E01005'; /* 取引ファイルエラー */
const ENCODING_ERROR                = 'E01006'; /* 文字コード設定エラー */

const CSV_DEF_SEPARATOR     = ','; /* デフォルトの項目区切り文字 */
const CSV_DEF_ITEM_ENVELOPE = '"'; /* デフォルトの項目データ囲み文字 */
const CSV_ENCODING_SJIS     = 'Shift_JIS'; /* ファイル出力用Encoding Shift_JIS */
const CSV_ENCODING_EUC      = 'EUC_JP'; /* ファイル出力用Encoding EUC-JP */
const CSV_ENCODING_MS932    = 'SJIS-win'; /* ファイル出力用Encoding MS932 */
const CSV_WINDOWS_NEWLINE   = "\r\n"; /* ファイル出力時の改行コード */
const CSV_UNIX_NEWLINE      = "\n"; /* ファイル出力時の改行コード */
const CSV_MAC_NEWLINE       = "\r"; /* ファイル出力時の改行コード */

/* 決済種別 */
const SETTLEMENT_LINK   = 1;
const SETTLEMENT_MODULE = 2;
const SETTLEMENT_MIX    = 3;

/* 支払いの種類 paymenttype */
const PAYGENT_ATM                     = '010'; /* ATM決済 */
const PAYGENT_CREDIT                  = '020'; /* カード決済オーソリ */
const PAYGENT_AUTH_CANCEL             = '021'; /* カード決済オーソリキャンセル */
const PAYGENT_CARD_COMMIT             = '022'; /* カード決済売上 */
const PAYGENT_CARD_COMMIT_CANCEL      = '023'; /* カード決済売上キャンセル */
const PAYGENT_CARD_COMMIT_AUTH_REVISE = '028'; /* カード決済補正オーソリ */
const PAYGENT_CARD_COMMIT_REVISE      = '029'; /* カード決済補正売上 */
const PAYGENT_CARD_3DS2               = '450'; /* 3Dセキュア2.0認証 */
const PAYGENT_CARD_STOCK_SET          = '025'; /* カード情報設定 */
const PAYGENT_CARD_STOCK_UPD          = '116'; /* カード情報更新 */
const PAYGENT_CARD_STOCK_DEL          = '026'; /* カード情報削除 */
const PAYGENT_CARD_STOCK_GET          = '027'; /* カード情報照会 */
const PAYGENT_CONVENI_NUM             = '030'; /* コンビニ決済（番号方式）申込 */
const PAYGENT_BANK                    = '050'; /* 銀行ネット決済 */
const PAYGENT_BANK_ASP                = '060'; /* 銀行ネット決済ASP申込 */
const PAYGENT_PAIDY_AUTH_CANCEL       = '340'; /* Paidyオーソリキャンセル */
const PAYGENT_PAIDY_CAPTURE           = '341'; /* Paidy売上 */
const PAYGENT_PAIDY_REFUND            = '342'; /* Paidy返金 */
const PAYGENT_PAIDY_REF               = '343'; /* Paidy決済情報検証 */
const PAYGENT_DIFF_REF                = '091'; /* 決済情報差分照会 */
const PAYGENT_REF                     = '094'; /* 決済情報照会 */

/* 決済種別CD */
const PAYMENT_TYPE_ATM         = '01';
const PAYMENT_TYPE_CREDIT      = '02';
const PAYMENT_TYPE_CONVENI_NUM = '03';
const PAYMENT_TYPE_BANK        = '05';
const PAYMENT_TYPE_PAIDY       = '22';

/* 決済ステータス */
const STATUS_REQUESTED                       = '10'; /* 申込済 */
const STATUS_AUTHORIZE_NG                    = '11'; /* オーソリNG */
const STATUS_PAYMENT_EXPIRED                 = '12'; /* 支払期限切 */
const STATUS_3DSECURE_INTERRUPTION           = '13'; /* 3Dセキュア中断 */
const STATUS_3DSECURE_CERTIFICATION          = '14'; /* 3Dセキュア認証 */
const STATUS_REGISTRATION_SUSPENDED          = '15'; /* 申込中断 */
const STATUS_PAYMENT_INVALIDITY_NO_CLEAR     = '16'; /* 支払期限切（消込対象外） */
const STATUS_AUTHORIZE_OK                    = '20'; /* オーソリOK */
const STATUS_AUTHORIZE_COMPLETED             = '21'; /* オーソリ完了 */
const STATUS_SALES_REQUEST_PENDING           = '30'; /* 売上要求中 */
const STATUS_AUTHORIZE_CANCELING             = '31'; /* オーソリ取消中 */
const STATUS_AUTHORIZE_CANCELED              = '32'; /* オーソリ取消済 */
const STATUS_AUTHORIZE_EXPIRED               = '33'; /* オーソリ期限切 */
const STATUS_CLEARED                         = '40'; /* 消込済 */
const STATUS_CLEARED_SALES_CANCEL_INVALIDITY = '41'; /* 消込済（売上取消期限切） */
const STATUS_SALES_CANCELING                 = '42'; /* 売上取消中 */
const STATUS_PRELIMINARY_DETECTED            = '43'; /* 速報検知済 */
const STATUS_COMPLETE_CLEARED                = '44'; /* 消込完了 */
const STATUS_SALES_CANCELING_TALLY           = '50'; /* 売上取消集計中 */
const STATUS_SALES_CANCELED                  = '60'; /* 売上取消済 */
const STATUS_PRELIMINARY_CANCELED            = '61'; /* 速報取消済 */
const STATUS_COMPLETE_CANCELED               = '62'; /* 取消完了 */

/* 無限ループを避ける */
const PAYGENT_REF_LOOP = 1000;

/* 利用上限金額 */
const CHARGE_MAX       = 500000;
const SEVEN_CHARGE_MAX = 300000;

/* 電文バージョン */
const TELEGRAM_VERSION = '1.0';

/* コンビニコード */
const CODE_SEVENELEVEN = '00C001';
const CODE_LOWSON      = '00C002';
const CODE_MINISTOP    = '00C004';
const CODE_FAMILYMART  = '00C005';
const CODE_SUNKUS      = '00C006';
const CODE_CIRCLEK     = '00C007';
const CODE_YAMAZAKI    = '00C014';
const CODE_SEICOMART   = '00C016';

/* 決済種別 */
const NUMBERING_TYPE_CYCLE = 0;
const NUMBERING_TYPE_FIX   = 1;

/* 結果取得区分 */
const RESULT_GET_TYPE_WAIT    = 0;
const RESULT_GET_TYPE_NO_WAIT = 1;

/* 審査結果通知メール */
const EXAM_RESULT_NOTIFICATION_TYPE_AUTO   = 0;
const EXAM_RESULT_NOTIFICATION_TYPE_MANUAL = 1;

/* 自動キャンセル区分 */
const AUTO_CANCEL_TYPE_WAIT    = 0;
const AUTO_CANCEL_TYPE_NO_WAIT = 1;

/* 文字入力制限（byte） */
const PAYGENT_BANK_STEXT_LEN            = '12';
const PAYGENT_CONVENI_STEXT_LEN         = '14';
const PAYGENT_CONVENI_MTEXT_LEN         = '20';
const PAYGENT_TEL_ITEM_LEN              = 11;
const PAYGENT_S_TEL_ITEM_LEN            = 4;
const PAYGENT_LINK_STEXT_LEN            = '12';
const PAYGENT_VIRTUAL_ACCOUNT_STEXT_LEN = '48';
const PAYGENT_VIRTUAL_ACCOUNT_MTEXT_LEN = '100';

/* コンビニ確認番号 */
const CONVENI_CONFIRMATION_NUMBER = '400008';
