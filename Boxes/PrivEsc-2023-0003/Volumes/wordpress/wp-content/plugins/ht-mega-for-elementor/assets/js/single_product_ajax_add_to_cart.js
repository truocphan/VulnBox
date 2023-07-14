;(function($){

    $(document).on( 'click', '.elementor-widget-htmega-wcaddtocart-addons .single_add_to_cart_button', function (e) {
        e.preventDefault();

        var $this = $(this),
            $form           = $this.closest('form.cart'),
            all_data        = $form.serialize(),
            product_qty     = $form.find('input[name=quantity]').val() || 1,
            product_id      = $form.find('input[name=product_id]').val() || $this.val(),
            variation_id    = $form.find('input[name=variation_id]').val() || 0,
            isGrouped       = $form.find('input[name=add-to-cart]').val();

        /* For Variation product */    
        var item = {},
        variations = $form.find( 'select[name^=attribute]' );
        if ( !variations.length) {
            variations = $form.find( '[name^=attribute]:checked' );
        }
        if ( !variations.length) {
            variations = $form.find( 'input[name^=attribute]' );
        }

        variations.each( function() {
            var $thisitem = $( this ),
                attributeName = $thisitem.attr( 'name' ),
                attributevalue = $thisitem.val(),
                index,
                attributeTaxName;
                $thisitem.removeClass( 'error' );
            if ( attributevalue.length === 0 ) {
                index = attributeName.lastIndexOf( '_' );
                attributeTaxName = attributeName.substring( index + 1 );
                $thisitem.addClass( 'required error' );
            } else {
                item[attributeName] = attributevalue;
            }
        });

        var data = {
            // action: 'woolentor_insert_to_cart',
            product_id: product_id,
            product_sku: '',
            quantity: product_qty,
            variation_id: variation_id,
            variations: item,
            isgrouped: 'no',
            all_data: all_data,
        };

        //For grouped product
        if(isGrouped){
            var groupedProductQuantites = [];
            var groupProductIds = [];
            var groupedProducts =  $form.find('.woocommerce-grouped-product-list .quantity input');
            groupedProducts.each(function(){ 
                if('' !== $(this).val() && '0' !== $(this).val()){
                    groupedProductQuantites.push($(this).val());
                    groupProductIds.push(getGroupedProductId($(this)));
                } 
            });
            data.quantity   = groupedProductQuantites;
            data.product_id = isGrouped;
            data.isgrouped  = 'yes';
            data.groupedProductIds  = groupProductIds;
        }

        var alldata = data.all_data + '&isgrouped='+ data.isgrouped + '&grouped_product_id='+ data.groupedProductIds +  '&product_id='+ data.product_id + '&product_sku='+ data.product_sku + '&quantity='+ data.quantity + '&variation_id='+ data.variation_id + '&variations='+ JSON.stringify( data.variations ) +'&action=woocommerce_grouped_product_ajax_add_to_cart';

        $( document.body ).trigger('adding_to_cart', [$this, data]);

        $.ajax({
            type: 'post',
            url: wc_add_to_cart_params.ajax_url,
            data: alldata,

            beforeSend: function (response) {
                $this.removeClass('added').addClass('loading');
            },

            complete: function (response) {
                $this.addClass('added').removeClass('loading');
            },

            success: function (response) {
                if ( response.error && response.product_url ) {
                    window.location = response.product_url;
                    return;
                } else {
                    $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $this]);
                }
            },

        });

        function getGroupedProductId($this){
            var productId = $this.parent().parent().parent().attr('id');
            return productId.split('-')[1];
        }

        return false;
    });

})(jQuery);