jQuery(document).ready(function($){
  var media_uploader;

  $('#upload_image_button').click(function(e) {
    e.preventDefault();
    
    if (media_uploader) {
        media_uploader.open();
        return;
    }
    
    media_uploader = wp.media.frames.file_frame = wp.media({
        title: 'Select or Upload Image',
        button: {
            text: 'Use this image'
        },
        multiple: false
    });
    
    media_uploader.on('select', function() {
        var attachment = media_uploader.state().get('selection').first().toJSON();
        
        $('#product_image_url').val(attachment.url);
    });
    
    media_uploader.open();
  });
});

