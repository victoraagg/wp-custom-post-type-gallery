<script type="text/javascript">
function remove_img(value) {
    var parent = jQuery('.gallery_single_row')[value];
    parent.remove();
}

var media_uploader = null;

function open_media_uploader_image(){
    media_uploader = wp.media({
        frame: "post", 
        state: "insert", 
        multiple: true 
    });
    media_uploader.open();
    media_uploader.on("insert", function(){
        var length = media_uploader.state().get("selection").length;
        var images = media_uploader.state().get("selection").models
        for(var i = 0; i < length; i++){
            var image_url = images[i].changed.url;
            var box = jQuery('#master_box').html();
            jQuery(box).appendTo('#img_box_container');
            var element = jQuery('#img_box_container .gallery_single_row:last-child').find('.image_container');
            var html = '<img src="'+image_url+'" width="100"/>';
            element.append(html);
            element.find('.meta_image_url').val(image_url);
        }
    });
}

jQuery(function() {
    jQuery("#img_box_container").sortable();
});
</script>