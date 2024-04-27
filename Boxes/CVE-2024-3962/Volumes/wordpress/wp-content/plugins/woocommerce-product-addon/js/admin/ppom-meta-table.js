"use strict";
jQuery(function($){
    
    /*********************************
    *   PPOM Existing Table Meta JS  *
    **********************************/

    /*-------------------------------------------------------
        
        ------ Its Include Following Function -----

        1- Apply DataTable JS Library To PPOM Meta List
        2- Delete Selected Products
        3- Check And Uncheck All Existing Product Meta List
        4- Loading Products In Modal DataTable
        5- Delete Single Product Meta
    --------------------------------------------------------*/


	/**
        1- Apply DataTable JS Library To PPOM Meta List
    **/
	$('#ppom-meta-table').DataTable({
		pageLength: 50,
		dom: 'f<"ppom-toolbar"><"top">rt<"bottom">lpi',
	});
	var append_overly_model =  ("<div class='ppom-modal-overlay ppom-js-modal-close'></div>");

    /**
        2- Delete Selected Products
    **/
    function deleteSelectedProducts(checkedProducts_ids) {
		swal.fire({
			title: "Are you sure?",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55 ",
			cancelButtonColor: "#DD6B55",
			confirmButtonText: "Yes",
			cancelButtonText: "No",
			}).then( (result ) => {
				if (!result.isConfirmed) return;

				$('#ppom_delete_selected_products_btn').html('Deleting...');
				
				var data = {
					action 			: 'ppom_delete_selected_meta',
					productmeta_ids	: checkedProducts_ids,
					ppom_meta_nonce : $("#ppom_meta_nonce").val()
				};

				$.post(ajaxurl, data, function(resp){
					$('#ppom_delete_selected_products_btn').html('Delete');
					if (resp) {
						swal.fire({title: "Done", text: resp, type: "success" ,confirmButtonColor: '#217ac8'}).then(()=>location.reload());
					}else{
						swal.fire(resp, "", "error").then();
					}
				});
		});
	}


    /**
        3- Check And Uncheck All Existing Product Meta List
    **/
	$('.ppom_product_checkbox').on('click', function(event){
		
		var checkboxProducts = $('.ppom_product_checkbox').map(function() {
		    return this.value;
		}).get();

		var checkedProducts = $('.ppom_product_checkbox:checked').map(function() {
		    return this.value;
		}).get();

		if (checkboxProducts.length == checkedProducts.length ) {
			$('#ppom-all-select-products-head-btn, #ppom-all-select-products-foot-btn').prop('checked', true);
		}else{
			$('#ppom-all-select-products-head-btn, #ppom-all-select-products-foot-btn').prop('checked', false);
		};

		$('#selected_products_count').html();
		$('#selected_products_count').html(checkedProducts.length);
	});
	$('#ppom-all-select-products-head-btn, #ppom-all-select-products-foot-btn').on('click', function(event){
		
		$('#ppom-meta-table input:checkbox').not(this).prop('checked', this.checked);
		var checkedProducts = $('.ppom_product_checkbox:checked').map(function() {
		    return this.value;
		}).get();
		$('#selected_products_count').html();
		$('#selected_products_count').html(checkedProducts.length);
	});


	/**
        4- Loading Products In Modal DataTable
    **/
    $('#ppom-meta-table_wrapper').on('click','a.ppom-products-modal', function(e){
        
        e.preventDefault();

        $(".ppom-table").DataTable();
        var ppom_id = $(this).data('ppom_id'); 
        var get_url = ajaxurl+'?action=ppom_get_products&ppom_id='+ppom_id;
	    var model_id = $(this).attr('data-formmodal-id');
	    
	    $.get( get_url, function(html){
	        $('#ppom-product-modal .ppom-modal-body').html(html);
	        $("#ppom_id").val(ppom_id);
        	$("body").append(append_overly_model);
	        $(".ppom-table").DataTable();
	        $('#'+model_id).fadeIn();

	    });
    });


    /**
        5- Delete Single Product Meta
    **/
	$('body').on('click','a.ppom-delete-single-product', function(e){
		e.preventDefault();
		var productmeta_id = $(this).attr('data-product-id');

        swal.fire({
            title: "Are you sure?",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55 ",
            cancelButtonColor: "#DD6B55",
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            }).then( (result ) => {
                if (!result.isConfirmed) return;
				$("#del-file-" + productmeta_id).html('<img src="' + ppom_vars.loader + '">');

				var data = {
					action 			: 'ppom_delete_meta',
					productmeta_id	: productmeta_id,
					ppom_meta_nonce : $("#ppom_meta_nonce").val()
				};

		        $.post(ajaxurl, data, function(resp){
		        	$("#del-file-" + productmeta_id).html('<span class="dashicons dashicons-no"></span>');
		        	if (resp.status === 'success') {
				        swal.fire({title: "Done", text: resp.message, type: "success" ,confirmButtonColor: '#217ac8'}).then(()=>location.reload());
		        	}else{
        	 			swal.fire(resp.message, "", "error");
		        	}
		        });
        });
    });

	$(document).on( 'change', '#ppom-bulk-actions', function(){
		const type = $(this).val();

		const checkedProducts_ids = $('.ppom_product_checkbox:checked').map(function() {
		    return parseInt(this.value);
		}).get();

		if ( ! ( checkedProducts_ids.length > 0 ) ) {
			swal.fire("Please at least check one Meta!", "", "error");
			return;
		}

		if( 'delete' === type ) {
			deleteSelectedProducts(checkedProducts_ids);
		}else if( 'export' === type ) {
			$('#ppom-groups-export-form').submit();
		}

		$(this).val(-1);
	});

	const exportOption = ppom_vars.ppomProActivated === 'yes' ? `<option value="export">${ppom_vars.i18n.exportLabel}</option>` : `<option disabled value="export">${ppom_vars.i18n.exportLockedLabel}</option>`;

	const importBtn = ppom_vars.ppomProActivated === 'yes' ? `<a class="btn btn-secondary btn-sm ml-4 ppom-import-export-btn" href=""><span class="dashicons dashicons-download"></span>${ppom_vars.i18n.importLabel}</a>` : `<a disabled class="btn btn-secondary btn-sm ml-4 disabled" href=""><span class="dashicons dashicons-download"></span>${ppom_vars.i18n.importLockedLabel}</a>`;

	const bulkActions = `<select id="ppom-bulk-actions">
			<option value="-1">${ppom_vars.i18n.bulkActionsLabel}</option>
			<option value="delete">${ppom_vars.i18n.deleteLabel}</option>
			${exportOption}
		</select>`;

	const btn = `<a class="btn btn-success btn-sm float-right mr-4" href="${ppom_vars.i18n.addGroupUrl}"><span class="dashicons dashicons-plus"></span>${ppom_vars.i18n.addGroupLabel}</a>`;

	$('div.ppom-toolbar').html(`<div class="">${bulkActions} ${importBtn} <span id="ppom-toolbar-extra"></span> ${btn}</div>`);
});