var zeusTokenClass = function() {

	var today = new Date();
	var year = today.getFullYear();

	this.cgiUrl = "https://linkpt.cardservice.co.jp/cgi-bin/token/token.cgi";

	this.zeusTokenItem = {
		"zeus_token_action_type_quick_label":"登録済みのカードを使う",
		"zeus_token_card_cvv_for_registerd_card_label":"セキュリティコード",
		"zeus_token_action_type_new_label":"新しいカードを使う",
		"zeus_token_card_number_label":"カード番号",
		"zeus_token_card_expires_label":"カード有効期限",
		"zeus_token_card_expires_month_suffix":"月",
		"zeus_token_card_expires_year_suffix":"年",
		"zeus_token_card_expires_note":"例）12月　"+year+"年",
		"zeus_token_card_cvv_for_new_card_label":"セキュリティコード",
		"zeus_token_card_name_label":"カード名義",
		"zeus_token_card_number_change_label":"カード情報の変更はこちら",
		"zeus_token_card_number_last4digits_label":"（登録済みのカード番号下4桁）",
		"zeus_token_error_messages":{
			"88888888":"メンテナンス中です。",
			"90100100":"通信に失敗しました。",
			"99999999":"その他のシステムエラーが発生しました。",
			"02030105":"METHOD が 'POST' 以外",
			"02030106":"CONTENT-TYPE が 'text/xml' もしくは 'application/xml' 以外",
			"02030107":"CONTENT-LENGTH が存在しないか、0 が指定されている",
			"02030108":"CONTENT-LENGTH が 8192 byte より大きい",
			"02030207":"XML データが未送信",
			"02030208":"XML データが 8192 byte より大きい",
			"02030209":"XML データに構文エラーがある",
			"02080114":"XML の action が空",
			"02080115":"無効な action が指定されている",
			"02130114":"XML に authentication clientip が存在しない",
			"02130117":"clientip のフォーマットが不正",
			"02130110":"不正な clientip が指定された",
			"02130118":"不正な clientip が指定された",
			"02130514":"「カード番号」を入力してください。",
			"02130517":"「カード番号」を正しく入力してください。",
			"02130619":"「カード番号」を正しく入力してください。",
			"02130620":"「カード番号」を正しく入力してください。",
			"02130621":"「カード番号」を正しく入力してください。",
			"02130640":"「カード番号」を正しく入力してください。",
			"02130714":"「有効期限(年)」を入力してください。",
			"02130717":"「有効期限(年)」を正しく入力してください。",
			"02130725":"「有効期限(年)」を正しく入力してください。",
			"02130814":"「有効期限(月)」を入力してください。",
			"02130817":"「有効期限(月)」を正しく入力してください。",
			"02130825":"「有効期限(月)」を正しく入力してください。",
			"02130922":"「有効期限」を正しく入力してください。",
			"02131014":"CVVが不正です。",
			"02131017":"「セキュリティコード」を正しく入力してください。",
			"02131117":"「カード名義」を正しく入力してください。",
			"02131123":"「カード名義」を正しく入力してください。",
			"02131124":"「カード名義」を正しく入力してください。",
		},
	};

	if( typeof zeusTokenCustomItem == "object" && zeusTokenCustomItem ) {
		for( var zeus_token_item_tmp_name in this.zeusTokenItem ) {
			if( typeof zeusTokenCustomItem[zeus_token_item_tmp_name] == "string" ) {
				this.zeusTokenItem[zeus_token_item_tmp_name] = zeusTokenCustomItem[zeus_token_item_tmp_name];
			}
		}
	}
};

zeusTokenClass.prototype.init = function() {
	// IPCODEの設定
	if( typeof zeusTokenIpcode != "string" ) {
		alert('E00002');
		return;
	}

	this.ipcode = zeusTokenIpcode;

	// 設置ミス防止
	if( !document.getElementById('zeus_token_card_info_area') ) {
		return;
	}

	// カード入力欄初期化
	this.initCardFormItems();

	if( uscesL10n.zeus_form == 'cart' && uscesL10n.zeus_quickcharge == 'on' && uscesL10n.zeus_cardupdate_url != undefined ) {
		// 新しいカードを使うをデフォルト
		//document.getElementsByName('zeus_card_option')[1].checked = true;
		document.getElementById('zeus_token_action_type_new').checked = true;
		//this.disableRegisterdCardArea();
		this.enableNewCardArea();
		//this.validateCardForm();
	}
}

zeusTokenClass.prototype.getErrorMessage = function(error_code) {
	if( typeof this.zeusTokenItem["zeus_token_error_messages"] != "object" ) {
		return error_code+"An error has occurred.";
	}
	if( typeof this.zeusTokenItem["zeus_token_error_messages"][error_code] == null ) {
		return error_code+"An error has occurred.";
	}
	if( typeof this.zeusTokenItem["zeus_token_error_messages"][error_code] != "string" ) {
		return error_code+":An error has occurred.";
	}
	return error_code+':'+this.zeusTokenItem["zeus_token_error_messages"][error_code];
}

// カード入力欄初期化
zeusTokenClass.prototype.initCardFormItems = function() {
	var label,input,select,option,span,zeus_registerd_card_area,zeus_new_card_area;
	var card_info_area = document.getElementById('zeus_token_card_info_area');
	card_info_area.textContent = null;

	// Hidden fields
	input = document.createElement('input');
	input.setAttribute('type','hidden');
	input.setAttribute('value','');
	input.setAttribute('id','zeus_token_value');
	input.setAttribute('name','zeus_token_value');
	card_info_area.appendChild(input);

	input = document.createElement('input');
	input.setAttribute('type','hidden');
	input.setAttribute('value','');
	input.setAttribute('id','zeus_token_masked_card_no');
	input.setAttribute('name','zeus_token_masked_card_no');
	card_info_area.appendChild(input);

	input = document.createElement('input');
	input.setAttribute('type','hidden');
	input.setAttribute('value','');
	input.setAttribute('id','zeus_token_return_card_expires_month');
	input.setAttribute('name','zeus_token_return_card_expires_month');
	card_info_area.appendChild(input);

	input = document.createElement('input');
	input.setAttribute('type','hidden');
	input.setAttribute('value','');
	input.setAttribute('id','zeus_token_return_card_expires_year');
	input.setAttribute('name','zeus_token_return_card_expires_year');
	card_info_area.appendChild(input);

	input = document.createElement('input');
	input.setAttribute('type','hidden');
	input.setAttribute('value','');
	input.setAttribute('id','zeus_token_masked_cvv');
	input.setAttribute('name','zeus_token_masked_cvv');
	card_info_area.appendChild(input);

	input = document.createElement('input');
	input.setAttribute('type','hidden');
	input.setAttribute('value','');
	input.setAttribute('id','zeus_token_return_card_name');
	input.setAttribute('name','zeus_token_return_card_name');
	card_info_area.appendChild(input);

	input = document.createElement('input');
	input.setAttribute('type','hidden');
	input.setAttribute('value','new');
	input.setAttribute('id','zeus_card_option');
	input.setAttribute('name','zeus_card_option');
	card_info_area.appendChild(input);

	// Input fields
	if( uscesL10n.zeus_form == 'cart' && uscesL10n.zeus_quickcharge == 'on' && uscesL10n.zeus_cardupdate_url != undefined ) {
		input = document.createElement('input');
		input.setAttribute('type','radio');
		input.setAttribute('value','prev');
		input.setAttribute('id','zeus_token_action_type_quick');
		input.setAttribute('name','zeus_card_option_type');
		card_info_area.appendChild(input);

		label = document.createElement('label');
		label.setAttribute('for','zeus_token_action_type_quick');
		label.textContent = this.zeusTokenItem["zeus_token_action_type_quick_label"];
		card_info_area.appendChild(label);
	}

	zeus_registerd_card_area = document.createElement('div');
	zeus_registerd_card_area.setAttribute('id','zeus_registerd_card_area');

	if( ( uscesL10n.zeus_form == 'cart' && uscesL10n.zeus_quickcharge == 'on' && uscesL10n.zeus_cardupdate_url != undefined ) ||
		( uscesL10n.zeus_form == 'member' && uscesL10n.zeus_quickcharge == 'on' ) ) {
		if( uscesL10n.zeus_partofcard != "" ) {
			label = document.createElement('label');
			label.textContent = uscesL10n.zeus_partofcard+" "+this.zeusTokenItem["zeus_token_card_number_last4digits_label"];
			zeus_registerd_card_area.appendChild(label);
		}
	}

	if( uscesL10n.zeus_form == 'cart' && uscesL10n.zeus_quickcharge == 'on' && uscesL10n.zeus_cardupdate_url != undefined ) {
		wc_nonce = document.getElementById("wc_nonce");
		cardupdate_url = document.createElement("a");
		cardupdate_url.href = decodeURIComponent(uscesL10n.zeus_cardupdate_url)+"&wc_nonce="+wc_nonce.value;
		var str = document.createTextNode(this.zeusTokenItem["zeus_token_card_number_change_label"]);
		cardupdate_url.appendChild(str);
		zeus_registerd_card_area.appendChild(cardupdate_url);

		//if( uscesL10n.zeus_security == '1' ) {
		//	label = document.createElement('label');
		//	label.setAttribute('for','zeus_token_card_cvv_for_registerd_card');
		//	label.textContent = this.zeusTokenItem["zeus_token_card_cvv_for_registerd_card_label"];
		//	zeus_registerd_card_area.appendChild(label);

		//	input = document.createElement('input');
		//	input.setAttribute('type','tel');
		//	input.setAttribute('value','');
		//	input.setAttribute('id','zeus_token_card_cvv_for_registerd_card');
		//	input.setAttribute('name','zeus_token_card_cvv_for_registerd_card');
		//	input.setAttribute('size','4');
		//	input.setAttribute('maxlength','5');
		//	zeus_registerd_card_area.appendChild(input);
		//}
	}

	card_info_area.appendChild(zeus_registerd_card_area);

	if( uscesL10n.zeus_form == 'cart' && uscesL10n.zeus_quickcharge == 'on' && uscesL10n.zeus_cardupdate_url != undefined ) {
		input = document.createElement('input');
		input.setAttribute('type','radio');
		input.setAttribute('value','new');
		input.setAttribute('id','zeus_token_action_type_new');
		input.setAttribute('name','zeus_card_option_type');
		card_info_area.appendChild(input);

		label = document.createElement('label');
		label.setAttribute('for','zeus_token_action_type_new');
		label.textContent = this.zeusTokenItem["zeus_token_action_type_new_label"];
		card_info_area.appendChild(label);
	}

	zeus_new_card_area = document.createElement('div');
	zeus_new_card_area.setAttribute('id','zeus_new_card_area');

	label = document.createElement('label');
	label.setAttribute('for','zeus_token_card_number');
	label.textContent = this.zeusTokenItem["zeus_token_card_number_label"];
	zeus_new_card_area.appendChild(label);

	input = document.createElement('input');
	input.setAttribute('type','tel');
	input.setAttribute('value','');
	input.setAttribute('id','zeus_token_card_number');
	input.setAttribute('name','zeus_token_card_number');
	input.setAttribute('autocomplete','off');
	zeus_new_card_area.appendChild(input);

	label = document.createElement('label');
	label.setAttribute('for','zeus_token_card_expires_month');
	label.textContent = this.zeusTokenItem["zeus_token_card_expires_label"];
	zeus_new_card_area.appendChild(label);

	select = document.createElement('select');
	select.setAttribute('id','zeus_token_card_expires_month');
	select.setAttribute('name','zeus_token_card_expires_month');
	option = document.createElement('option');
	option.setAttribute('value','');
	option.textContent = '--';
	select.appendChild(option);
	for( var month = 1; month <= 12; month++ ) {
		var tmp = ("0"+month).slice(-2);
		option = document.createElement('option');
		option.setAttribute('value',tmp);
		option.textContent = tmp;
		select.appendChild(option);
	}
	zeus_new_card_area.appendChild(select);

	span = document.createElement('span');
	span.setAttribute('id','zeus_token_card_expires_month_suffix');
	span.textContent = this.zeusTokenItem["zeus_token_card_expires_month_suffix"]+'　';
	zeus_new_card_area.appendChild(span);

	select = document.createElement('select');
	select.setAttribute('id','zeus_token_card_expires_year');
	select.setAttribute('name','zeus_token_card_expires_year');
	option = document.createElement('option');
	option.setAttribute('value','');
	option.textContent = '----';
	select.appendChild(option);
	//var year_from = parseInt(uscesL10n.zeus_thisyear) - 1;
	var year_from = parseInt(uscesL10n.zeus_thisyear);
	var year_to = year_from+10;
	for( var year = year_from; year <= year_to; year++ ) {
		option = document.createElement('option');
		option.setAttribute('value',year);
		option.textContent = year;
		select.appendChild(option);
	}
	zeus_new_card_area.appendChild(select);

	span = document.createElement('span');
	span.setAttribute('id','zeus_token_card_expires_year_suffix');
	span.textContent = this.zeusTokenItem["zeus_token_card_expires_year_suffix"];
	zeus_new_card_area.appendChild(span);

	zeus_new_card_area.appendChild(document.createElement('br'));

	span = document.createElement('span');
	span.setAttribute('id','zeus_token_card_expires_note');
	span.textContent = this.zeusTokenItem["zeus_token_card_expires_note"];
	zeus_new_card_area.appendChild(span);

	if( uscesL10n.zeus_security == '1' ) {
		label = document.createElement('label');
		label.setAttribute('for','zeus_token_card_cvv');
		label.textContent = this.zeusTokenItem["zeus_token_card_cvv_for_new_card_label"];
		zeus_new_card_area.appendChild(label);

		input = document.createElement('input');
		input.setAttribute('type','tel');
		input.setAttribute('value','');
		input.setAttribute('id','zeus_token_card_cvv');
		input.setAttribute('name','zeus_token_card_cvv');
		input.setAttribute('size','4');
		input.setAttribute('maxlength','5');
		zeus_new_card_area.appendChild(input);
	}

	label = document.createElement('label');
	label.setAttribute('for','zeus_token_card_name');
	label.textContent = this.zeusTokenItem["zeus_token_card_name_label"];
	zeus_new_card_area.appendChild(label);

	input = document.createElement('input');
	input.setAttribute('type','text');
	input.setAttribute('value','');
	input.setAttribute('id','zeus_token_card_name');
	input.setAttribute('name','zeus_token_card_name');
	zeus_new_card_area.appendChild(input);

	card_info_area.appendChild(zeus_new_card_area);

	//if( uscesL10n.zeus_form == 'member' && uscesL10n.zeus_expyy != undefined && uscesL10n.zeus_expmm != undefined ) {
	//	var expyy = parseInt(uscesL10n.zeus_expyy);
	//	var expmm = parseInt(uscesL10n.zeus_expmm);
	//	obj = document.getElementById('zeus_token_card_expires_month');
	//	for( var i = 0; i < obj.options.length; i++ ) {
	//		if( obj.options[i].value == expmm ) {
	//			obj.selectedIndex = i;
	//			break;
	//		}
	//	}
	//	obj = document.getElementById('zeus_token_card_expires_year');
	//	for( var i = 0; i < obj.options.length; i++ ) {
	//		if( obj.options[i].value == expyy ) {
	//			obj.selectedIndex = i;
	//			break;
	//		}
	//	}
	//}
};

// 「登録済みのカードを使う」の項目を活性化
zeusTokenClass.prototype.enableRegisterdCardArea = function() {
	var obj;

	if( uscesL10n.zeus_security == '1' ) {
		obj = document.getElementById('zeus_token_card_cvv_for_registerd_card');
		obj.disabled = false;
		obj.classList.remove('zeus_token_input_disable');
		obj.classList.remove('zeus_token_input_error');
		obj.classList.add('zeus_token_input_normal');
	}
};

// 「登録済みのカードを使う」の項目を非活性化
zeusTokenClass.prototype.disableRegisterdCardArea = function() {
	var obj;

	if( uscesL10n.zeus_security == '1' ) {
		obj = document.getElementById('zeus_token_card_cvv_for_registerd_card');
		obj.value = "";
		obj.disabled = "disabled";
		obj.classList.remove('zeus_token_input_error');
		obj.classList.remove('zeus_token_input_normal');
		obj.classList.add('zeus_token_input_disable');
	}
};

// 「新しいカードを使う」の項目を活性化
zeusTokenClass.prototype.enableNewCardArea = function() {
	var obj;

	obj = document.getElementById('zeus_token_card_number');
	obj.disabled = false;
	obj.classList.remove('zeus_token_input_disable');
	obj.classList.remove('zeus_token_input_error');
	obj.classList.add('zeus_token_input_normal');

	obj = document.getElementById('zeus_token_card_expires_month');
	obj.disabled = false;
	obj.classList.remove('zeus_token_input_disable');
	obj.classList.remove('zeus_token_input_error');
	obj.classList.add('zeus_token_input_normal');

	obj = document.getElementById('zeus_token_card_expires_year');
	obj.disabled = false;
	obj.classList.remove('zeus_token_input_disable');
	obj.classList.remove('zeus_token_input_error');
	obj.classList.add('zeus_token_input_normal');

	if( uscesL10n.zeus_security == '1' ) {
		obj = document.getElementById('zeus_token_card_cvv');
		obj.disabled = false;
		obj.classList.remove('zeus_token_input_disable');
		obj.classList.remove('zeus_token_input_error');
		obj.classList.add('zeus_token_input_normal');
	}

	obj = document.getElementById('zeus_token_card_name');
	obj.disabled = false;
	obj.classList.remove('zeus_token_input_disable');
	obj.classList.remove('zeus_token_input_error');
	obj.classList.add('zeus_token_input_normal');
};

// 「新しいカードを使う」の項目を非性化
zeusTokenClass.prototype.disableNewCardArea = function() {
	var obj;

	obj = document.getElementById('zeus_token_card_number');
	obj.value = "";
	obj.disabled = "disabled";
	obj.classList.remove('zeus_token_input_error');
	obj.classList.remove('zeus_token_input_normal');
	obj.classList.add('zeus_token_input_disable');

	obj = document.getElementById('zeus_token_card_expires_month');
	obj.selectedIndex = 0;
	obj.disabled = "disabled";
	obj.classList.remove('zeus_token_input_error');
	obj.classList.remove('zeus_token_input_normal');
	obj.classList.add('zeus_token_input_disable');

	obj = document.getElementById('zeus_token_card_expires_year');
	obj.selectedIndex = 0;
	obj.disabled = "disabled";
	obj.classList.remove('zeus_token_input_error');
	obj.classList.remove('zeus_token_input_normal');
	obj.classList.add('zeus_token_input_disable');

	if( uscesL10n.zeus_security == '1' ) {
		obj = document.getElementById('zeus_token_card_cvv');
		obj.disabled = false;
		obj.classList.remove('zeus_token_input_error');
		obj.classList.remove('zeus_token_input_normal');
		obj.classList.add('zeus_token_input_disable');
	}

	obj = document.getElementById('zeus_token_card_name');
	obj.value = "";
	obj.disabled = "disabled";
	obj.classList.remove('zeus_token_input_error');
	obj.classList.remove('zeus_token_input_normal');
	obj.classList.add('zeus_token_input_disable');
};

// カード情報入力フォームのバリデーション
zeusTokenClass.prototype.validateCardForm = function(action_type) {
	var validateResult = true;
	var error_code = "";
	var obj;

	obj = document.getElementById('zeus_token_card_number');
	if( obj.value == "" ) {
		validateResult = false;
		if( error_code == "" ) error_code = "02130514";
		obj.classList.remove('zeus_token_input_disable');
		obj.classList.remove('zeus_token_input_normal');
		obj.classList.add('zeus_token_input_error');
	} else {
		obj.classList.remove('zeus_token_input_disable');
		obj.classList.remove('zeus_token_input_error');
		obj.classList.add('zeus_token_input_normal');
	}

	obj = document.getElementById('zeus_token_card_expires_month');
	if( obj.selectedIndex == 0 ) {
		validateResult = false;
		if( error_code == "" ) error_code = "02130814";
		obj.classList.remove('zeus_token_input_disable');
		obj.classList.remove('zeus_token_input_normal');
		obj.classList.add('zeus_token_input_error');
	} else {
		obj.classList.remove('zeus_token_input_disable');
		obj.classList.remove('zeus_token_input_error');
		obj.classList.add('zeus_token_input_normal');
	}

	obj = document.getElementById('zeus_token_card_expires_year');
	if( obj.selectedIndex == 0 ) {
		validateResult = false;
		if( error_code == "" ) error_code = "02130714";
		obj.classList.remove('zeus_token_input_disable');
		obj.classList.remove('zeus_token_input_normal');
		obj.classList.add('zeus_token_input_error');
	} else {
		obj.classList.remove('zeus_token_input_disable');
		obj.classList.remove('zeus_token_input_error');
		obj.classList.add('zeus_token_input_normal');
	}

	if( uscesL10n.zeus_security == '1' ) {
		obj = document.getElementById('zeus_token_card_cvv');
		if( obj.value == "" ) {
			validateResult = false;
			if( error_code == "" ) error_code = "02131017";
			obj.classList.remove('zeus_token_input_disable');
			obj.classList.remove('zeus_token_input_normal');
			obj.classList.add('zeus_token_input_error');
		} else {
			obj.classList.remove('zeus_token_input_disable');
			obj.classList.remove('zeus_token_input_error');
			obj.classList.add('zeus_token_input_normal');
		}
	}

	obj = document.getElementById('zeus_token_card_name');
	if( obj.value == "" ) {
		validateResult = false;
		if( error_code == "" ) error_code = "02131117";
		obj.classList.remove('zeus_token_input_disable');
		obj.classList.remove('zeus_token_input_normal');
		obj.classList.add('zeus_token_input_error');
	} else {
		obj.classList.remove('zeus_token_input_disable');
		obj.classList.remove('zeus_token_input_error');
		obj.classList.add('zeus_token_input_normal');
	}

	//return validateResult;
	return error_code;
};

// 入力されたカード情報から、トークンを取得する
zeusTokenClass.prototype.getToken = function(callback_function) {
	var action_type = ''; // 空欄で送信された場合の入力チェックは、サーバサイドで行われ、エラーコード101020で返却される。
	if( uscesL10n.zeus_form == 'cart' && uscesL10n.zeus_quickcharge == 'on' && uscesL10n.zeus_cardupdate_url != undefined ) {
		if( document.getElementById('zeus_token_action_type_new').checked ) {
			action_type = 'newcard';
		} else if( document.getElementById('zeus_token_action_type_quick').checked ) {
			action_type = 'quick';
		}
	} else {
		action_type = 'newcard';
	}

	//if( document.getElementById('zeus_token_action_type_new').checked ) {
	//	var validateResult = this.validateCardForm();
	//}
	if( action_type == 'newcard' ) {
		var error_code = this.validateCardForm(action_type);
		if( error_code != "" ) {
			var data = {
				'result':0,
				'error_code':error_code,
			};
			if( typeof callback_function == 'function' ) {
				callback_function(data);
			}
			return;
		}
	}

	var request = new XMLHttpRequest();
	request.onreadystatechange = function() {
		switch( this.readyState ) {
			case 4:
				if( this.status == 0 ) {
					// 通信失敗
					var data = {
						'result':0,
						'error_code':'90100100',
					};
					if( typeof callback_function == 'function' ) {
						// 指定されたコールバック関数を実行する
						callback_function(data);
					}
					return;
				}
				if( ( 200 <= this.status && this.status < 300 ) || ( this.status == 304 ) ) {

					var xml = this.responseXML.documentElement;
					var status = '';
					if( xml.getElementsByTagName('status').length != 0 ) {
						status = xml.getElementsByTagName('status')[0].textContent;
					}
					var error_code = '';
					if( xml.getElementsByTagName('code').length != 0 ) {
						error_code = xml.getElementsByTagName('code')[0].textContent;
					}

					switch( status ) {
						case 'success':
							var token_key = '';
							if( xml.getElementsByTagName('token_key').length != 0 ) {
								token_key = xml.getElementsByTagName('token_key')[0].textContent;
							}
							var masked_card_number = '';
							if( xml.getElementsByTagName('masked_card_number').length != 0 ) {
								masked_card_number = xml.getElementsByTagName('masked_card_number')[0].textContent;
							}
							var masked_cvv = '';
							if( xml.getElementsByTagName('masked_cvv').length != 0 ) {
								masked_cvv = xml.getElementsByTagName('masked_cvv')[0].textContent;
							}
							var card_expires_month = '';
							if( xml.getElementsByTagName('card_expires_month').length != 0 ) {
								card_expires_month = xml.getElementsByTagName('card_expires_month')[0].textContent;
							}
							var card_expires_year = '';
							if( xml.getElementsByTagName('card_expires_year').length != 0 ) {
								card_expires_year = xml.getElementsByTagName('card_expires_year')[0].textContent;
							}
							var card_name = '';
							if( xml.getElementsByTagName('card_name').length != 0 ) {
								card_name = xml.getElementsByTagName('card_name')[0].textContent;
							}
							var data = {
								'result':1,
								'token_key':token_key,
								'masked_card_number':masked_card_number,
								'masked_cvv':masked_cvv,
								'card_expires_month':card_expires_month,
								'card_expires_year':card_expires_year,
								'card_name':card_name,
							};
							// 返却値をhiddenにセットし、カード情報項目をサーバへ送らないよう消す。
							document.getElementById('zeus_token_value').value = data['token_key'];
							document.getElementById('zeus_token_masked_card_no').value = data['masked_card_number'];
							document.getElementById('zeus_token_return_card_expires_month').value = data['card_expires_month'];
							document.getElementById('zeus_token_return_card_expires_year').value = data['card_expires_year'];
							document.getElementById('zeus_token_masked_cvv').value = data['masked_cvv'];
							document.getElementById('zeus_token_return_card_name').value = data['card_name'];
							document.getElementById('zeus_registerd_card_area').textContent = null;
							document.getElementById('zeus_new_card_area').textContent = null;
							break;
						case 'invalid':
						case 'failure':
							var data = {
								'result':0,
								'error_code':error_code,
							};
							break;
						case 'maintenance':
							var data = {
								'result':0,
								'error_code':'88888888',
							};
							break;
						default:
							var data = {
								'result':0,
								'error_code':'99999999',
							};
							break;
					}

					if( typeof callback_function == 'function' ) {
						// 指定されたコールバック関数を実行する
						callback_function(data);
					}
				}
		}
	}

	var zeus_token_card_cvv = '';
	if( uscesL10n.zeus_security == '1' ) {
		if( action_type == 'newcard' ) {
			zeus_token_card_cvv = document.getElementById('zeus_token_card_cvv').value;
		} else if( action_type == 'quick' ) {
			zeus_token_card_cvv = document.getElementById('zeus_token_card_cvv_for_registerd_card').value;
		}
	}

	var data = '<?xml version="1.0" encoding="utf-8"?>'+
					'<request service="token" action="'+action_type+'">'+
						'<authentication>'+
							'<clientip>'+this.ipcode+'</clientip>'+
						'</authentication>'+
						'<card>';
	if( zeus_token_card_cvv != "" ) {
		data = data+
							'<cvv>'+zeus_token_card_cvv+'</cvv>';
	}
	if( action_type == 'newcard' ) {
		var zeus_token_card_number = document.getElementById('zeus_token_card_number').value;
		var objYear = document.getElementById('zeus_token_card_expires_year');
		var zeus_token_card_expires_year = objYear.options[objYear.selectedIndex].value;
		var objMonth = document.getElementById('zeus_token_card_expires_month');
		var zeus_token_card_expires_month = objMonth.options[objMonth.selectedIndex].value;
		var zeus_token_card_name = document.getElementById('zeus_token_card_name').value;

		data = data+
							'<number>'+zeus_token_card_number+'</number>'+
							'<expires>'+
								'<year>'+zeus_token_card_expires_year+'</year>'+
								'<month>'+zeus_token_card_expires_month+'</month>'+
							'</expires>'+
							'<name>'+zeus_token_card_name+'</name>';
	}
	data = data+
						'</card>'+
					'</request>';

	request.open("POST",this.cgiUrl,false);
	request.setRequestHeader("Content-Type","text/xml");
	try {
		request.send(data);
	} catch(e) {
		// 通信失敗
		var return_data = {
			'result':0,
			'error_code':'90100100',
		};
		if( typeof callback_function == 'function' ) {
			// 指定されたコールバック関数を実行する
			callback_function(return_data);
		}
		return;
	}
};

var zeusToken = new zeusTokenClass();

var zeusTokenStart = function() {
	zeusToken.init();

	//document.getElementById('zeus_token_card_name').onchange = function() {
	//	zeusToken.validateCardForm();
	//};
	//document.getElementById('zeus_token_card_number').onchange = function() {
	//	zeusToken.validateCardForm();
	//};
	//document.getElementById('zeus_token_card_expires_month').onchange = function() {
	//	zeusToken.validateCardForm();
	//};
	//document.getElementById('zeus_token_card_expires_year').onchange = function() {
	//	zeusToken.validateCardForm();
	//};
	//document.getElementById('zeus_token_card_cvv').onchange = function() {
	//	zeusToken.validateCardForm();
	//};

	if( uscesL10n.zeus_form == 'cart' && uscesL10n.zeus_quickcharge == 'on' && uscesL10n.zeus_cardupdate_url != undefined ) {
		document.getElementById('zeus_token_action_type_new').onclick = function() {
			//zeusToken.disableRegisterdCardArea();
			zeusToken.enableNewCardArea();
			//zeusToken.validateCardForm();
			document.getElementById('zeus_card_option').value = this.value;
		};
		document.getElementById('zeus_token_action_type_quick').onclick = function() {
			//zeusToken.enableRegisterdCardArea();
			zeusToken.disableNewCardArea();
			document.getElementById('zeus_card_option').value = this.value;
		};
	}
}

if( window.addEventListener ) {
	window.addEventListener('load',zeusTokenStart,false);
} else if( window.attachEvent ) {
	window.attachEvent('onload',zeusTokenStart);
} else {
	window.onload = zeusTokenStart;
}
