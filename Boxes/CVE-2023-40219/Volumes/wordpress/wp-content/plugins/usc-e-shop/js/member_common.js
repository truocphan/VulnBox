// Member page JavaScript

let memberOrderHistory = {
	onChangeFilter : function (inputEle){
		const cfilters = JSON.stringify(
			{
				ord_ex_cancel: jQuery("#ord_exclude_cancel").is(":checked") ? "on" : "off",
				usces_purdate: jQuery("#usces_purdate option:selected").attr("cvalue")
			}
		);

		this.setCookie("usces_front", cfilters, 7);
		inputEle.disabled = true;
		location.href = inputEle.value;
	},
	setCookie: function (cname, cvalue, exdays) {
		const d = new Date();
		d.setTime(d.getTime() + (exdays*86400000));
		let expires = "expires="+ d.toUTCString();
		document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	}
};
