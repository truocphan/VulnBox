// JavaScript
(function($) {

	itemCSV = {
		settings: {
			url: uscesL10n.requestFile,
			type: 'POST',
			cache: false
		},


		registration : function() {

			//$("#msg").html('<span>データチェック中　</span><img src="' + uscesL10n.USCES_PLUGIN_URL + '/images/loading.gif" />');

			var s = itemCSV.settings;
			s.data = 'action=wel_item_upload_ajax&regfile=' + tempfilename + '&noheader=true';
			console.log(s.data);

			$.ajax( s ).done(function( data ){
				console.log('Reg OK');

				//$("#msg").html(data);
				console.log(data);
				
			}).fail(function( msg ){
				console.log('Reg NG');

				console.log(msg);
				//$("#newitemopt_loading").html('');

			});
			return false;
		},
	};



})(jQuery);

