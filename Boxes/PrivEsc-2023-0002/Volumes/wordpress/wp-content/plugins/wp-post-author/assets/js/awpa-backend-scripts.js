var custom_theme_file_frame;

jQuery(function($){

    // Uploads.
    jQuery(document).on('click', 'input.select-img', function( event ){

        event.preventDefault();

        var file_frame;
        var _that = jQuery(this);

        // Create the media frame.
        file_frame = wp.media.frames.downloadable_file = wp.media({
            title: _that.attr('data-uploader_title'),
            button: {
                text: _that.attr('data-uploader_button_text')
            },
            multiple: false
        });

        // When an image is selected, run a callback.
        file_frame.on( 'select', function() {
            var attachment           = file_frame.state().get( 'selection' ).first().toJSON();
            var attachment_thumbnail = attachment.sizes.thumbnail || attachment.sizes.full;

            _that.prev().trigger('change').val( attachment.id );
            var image_html = '<img src="' + attachment_thumbnail.url + '" />';
            _that.closest('.image-preview-wrap').find('.image-preview').html(image_html);
            _that.next().show();
        });

        // Finally, open the modal.
        file_frame.open();
    });

    // Remove image.
    jQuery(document).on('click', 'input.btn-image-remove', function( e ) {
        var _this = jQuery( this );
        _this.closest('.image-preview-wrap').find('.image-preview').html(' ');
        _this.siblings('.img').trigger('change').val( '' );
        _this.hide();
        return false;

    });

});