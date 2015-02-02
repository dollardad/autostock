jQuery(document).ready( function($) {

    var file_name;
    console.log( 'here');
    $('#upload_image_button').on( 'click', function( event ) {
        event.preventDefault();

        if( file_name ) {
            file_name.open();
            return;
        }

        file_name = wp.media.frames.file_frame = wp.media({
            title: $( this).data('uploader_title'),
            button: {
                text: $(this).data( 'uploader_button_text')
            },
            multiple: false
        });
    });
});