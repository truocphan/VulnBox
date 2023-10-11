/**
 * iframeのID指定用変数
 */
var iframeId;

/**
 * EMV3DSecure認証コンテナ
 */
var threedsContainer;

/**
 * 加盟店webサーバのurl
 */
var merchantUrl;

/**
 * リスクベース認証結果の一時保存
 */
var securePaData;

/**
 * securePaのmd
 */
var xid_sha512;

/**
 * タイムアウト解除用の変数
 */
let timeout;

// iframeからのイベント受信を行う。CROS対策方式
window.addEventListener("message",receiveMessage,false);
function receiveMessage(event) {
	// 環境ごとに変化
	if( event.origin !== "https://linkpt.cardservice.co.jp" ) {
		return;
	}

	const jsonObj = JSON.parse(event.data);
	if( jsonObj.event == '3DSMethodSkipped' || jsonObj.event == '3DSMethodFinished' ) {
		clearTimeout(timeout);
		_doAuth(xid_sha512,merchantUrl);
	} else if( jsonObj.event === 'AuthResultReady' ) { // チャレンジフローの終了時にiframeから受信するメッセージ
		securePaData.transStatus = jsonObj.transStatus;
		_onAuthResult(securePaData); // PaResをフロントから送信する。
	}
}

/**
 * 加盟店利用関数3.PaReqで返されたパラメータ
 * (EnrolReqの切っ掛けとなったリクエストへのレスポンス)
 * をそのまま取得する。
 * レスポンスで新しく画面を開く加盟店も、PaReq部分をこの関数に渡せばOK。
 * ajaxの加盟店はレスポンスをそのままここに渡す。
 * 2/16 paymentResultパラメータ不要化
 * @param params
 */
function setPareqParams(md,paReq,termUrl,threeDSMethod,iframeUrl) {
	// 3DS認証可否の確認
	if( threeDSMethod == null ) {
		// 決済成功ページを表示
		doPost();
	} else {
		iframeId = String(Math.floor(100000+Math.random()*900000));

		setThreedsContainer();

		// 3DS認証利用OKの場合
		// termUrlを加盟店サーバURLとしてグローバル変数にセット
		merchantUrl = decodeURIComponent(termUrl);
		// mdの値をグローバル変数にセット
		xid_sha512 = md;
		// ブラウザ情報収集用のiframeをコンテナに設置
		var decodeUrl = decodeURIComponent(iframeUrl);
		var appendNode = document.createElement('iframe');
		appendNode.setAttribute('id','3ds_secureapi_'+iframeId);
		appendNode.setAttribute('width','0');
		appendNode.setAttribute('height','0');
		appendNode.setAttribute('style','visibility: hidden;');
		appendNode.setAttribute('src',decodeUrl);
		threedsContainer.appendChild(appendNode);

		// 20秒待ってもgpayからcallbackがない場合、次の認証処理を実施するためのタイマー（エラーになる想定）
		timeout = setTimeout( function() {
			_doAuth(md,merchantUrl);
		},20000);
	}
}

/**
 * Get the authentication from Active Server
 * @param threeDSServerTransID
 * @param callbackFn
 */
function result(threeDSServerTransID,callbackFn) {
	getResult(threeDSServerTransID,callbackFn);
}

/**
 * Post authData to 3ds requestor with url
 * @param processName：呼出元
 * @param url
 * @param authData
 * @param onSuccess
 * @param onError
 * @param contentType
 */
function _doPost(processName,url,authData,onSuccess,onError,contentType="application/json") {
	var request = new XMLHttpRequest();
	request.open(
		'POST',
		url,
		true,
	);

	request.setRequestHeader('Content-Type',contentType);
	if( contentType === "application/json" ) {
		authData = JSON.stringify(authData);
	}

	request.onload = function(data) {
		// JQueryからXHRRequestへの変換時の互換性確保。JQueryのdate = XHRのdata.target.response
		let response = data.target.response;
		try { // jQuery互換：レスポンスがjsonの場合パースしておく。
			response = JSON.parse(response);
		} catch(e) {
		}
		if( request.status >= 200 && request.status < 400 ) {
			onSuccess(response);
		} else {
			onError({ "message": "「"+request.status+"」"+processName+" 処理エラー"});
		}
	};

	request.onerror = function(data) {
		onError({ "message": "「"+request.status+"」"+processName+" 処理エラー"});
	};

	request.send(authData);
}

/**
 * リスクベース認証を要求し、フリクションレス/チャレンジフローを開始する。
 * @param md
 * @param termUrl
 * @private
 */
function _doAuth(md,termUrl) {
	// 加盟店SecureAPI利用 PaReq送信
	content =
		'<?xml version="1.0" encoding="utf-8"?>'
		+'<request service="secure_link_3d" action="securePa">'
		+'<xid>'+md+'</xid>'
		+'</request>';
	_doPost(
		'PaReq',
		'https://linkpt.cardservice.co.jp/cgi-bin/token/token.cgi',
		content,
		function(xml) {
			try {
				// 結果に応じてチャレンジ/フリクションレスフローを開始する。
				let domparser = new DOMParser();
				let doc = domparser.parseFromString(xml,"application/xml");
				var data = {};

				if( typeof doc.getElementsByTagName("status")[0] !== 'undefined' ) {
					data.status = doc.getElementsByTagName("status")[0].textContent;
				} else {
					data.status = 'maintenance';
				}

				if( typeof doc.getElementsByTagName("xid")[0] !== 'undefined' ) {
					data.xid = doc.getElementsByTagName("xid")[0].textContent;
				}

				if( typeof doc.getElementsByTagName("transStatus")[0] !== 'undefined' ) {
					data.transStatus = doc.getElementsByTagName("transStatus")[0].textContent;
				} else {
					data.transStatus = "N";
				}

				if( data.status == 'success' || data.status == 'outside' ) {
					securePaData = data;

					// フリクションレスのとき、challengeUrlを持たない。
					// challengeUrlが設定されている（チャレンジ）時だけ、challengeUrlを設定して渡す。
					if( typeof doc.getElementsByTagName("challengeUrl")[0] !== 'undefined' ) {
						data.challengeUrl = doc.getElementsByTagName("challengeUrl")[0].textContent;
					}
				} else {
					data.xid = md; // doc.getElementsByTagName("xid")[0].textContent;
					data.transStatus = "N";
				}
				
			} catch(error) {
				// XMLパースエラーなど発生時、失敗時の関数を呼ぶ。
				_onError({ "message": error.message });
			}
			_onDoAuthSuccess(data);
		},
		function(error) {
			_onError({ "message": error.message });
		},
		"application/xml"
	);
}

/**
 * リスクベース認証完了後に動作
 * Callback function for _doAuth
 * @param data
 * @private
 */
function _onDoAuthSuccess(data) {
	if( data.transStatus === "C" || data.transStatus === "D" ) {
		var loading_img = document.getElementById('zeus-loading');
		loading_img.style.display = 'none';
		// チャレンジフロー（C, D）
		data.challengeUrl ? startChallenge(data.challengeUrl) : _onError(
			{ "message": "追加認証要求URLがありません。" });
	} else {
		/* iframe remove */
		var iframe = document.getElementById('3ds_secureapi_'+iframeId);
		iframe.remove();
		// チャレンジ以外
		_onAuthResult(data);
	}
}

/**
 * Setup iframe for challenge flow (Step 14(C))
 * @param url is the challenge url returned from 3DS Server
 */
function startChallenge(url) {
	var challengeUrl = decodeURIComponent(url);
	var appendNode = document.createElement('iframe');
	appendNode.setAttribute('id','3ds_challenge');
	appendNode.setAttribute('width','100%');
	appendNode.setAttribute('height','300px');
	appendNode.setAttribute('style','border:0');
	appendNode.setAttribute('src',challengeUrl);
	appendNode.setAttribute('onload','loadedChallenge()');
	setThreedsContainer();
	threedsContainer.appendChild(appendNode);
}

/**
 * チャレンジ/フリクションレスフロー完了時に呼ばれるメソッド
 */
function _onAuthResult(data) {
	sendPaRes(data);
}

/**
 * PaResをPaReqで受け取ったTermUrlに向けて送信
 */
function sendPaRes(data) {
	var paResData = {};
	paResData.MD = data.xid;
	paResData.PaRes = data.transStatus;
	paResData.status = data.status;
	_doPost('PaRes',merchantUrl,paResData,_onPaResSuccess,_onError);
}

/**
 * PaRes成功後のデフォルト関数。挙動なし。
 * この宣言以降で同名関数の宣言によって挙動上書き可能。
 */
function _onPaResSuccess(data) {
	document.write(data);
}

/**
 * この関数を上書きすることでエラー時の挙動を制御できます。
 * @param error
 * @private
 */
function _onError(error) {
	if( error.status === 404 ) {
		error["New feature"] = "This feature is only supported by ActiveServer v1.1.2+";
	}
	console.log('_onError='+error);
}

/**
 * Set 3DS Container
 */
function setThreedsContainer() {
	threedsContainer = document.querySelector("div[id='3dscontainer']");
	if( threedsContainer == null ) {
		var containerDiv = document.createElement("div");
		containerDiv.id = '3dscontainer';
		document.body.appendChild(containerDiv);
		threedsContainer = document.querySelector("div[id='3dscontainer']");
	}
}

/**
 * チャレンジ認証画面ロード完了時、加盟店側のメッセージを非表示とする関数
 */
function loadedChallenge() {
	var div_waiter;
	if ( div_waiter = document.querySelector("div[id='challenge_wait']") ) {
		div_waiter.style.display = 'none';
	}
}
