var j$ = jQuery;
function getUploadImageInfo(formname) {
	window.original_send_to_editor = window.send_to_editor;
	// variable html from uploader to custom field.
	window.send_to_editor = function(html){
		// change double quote to single.
		var html = new String(html).replace(/\"/g, "'");
		if (formname) {
			var img_tag = html.match(/<img.*?\/>/g);
			if(img_tag) {
				img_tag = new String(img_tag).replace(/\'/g,"");
				img_tag = img_tag.match(/(http:\/\/[\x21-\x7e]+)/gi);
				if(img_tag) {
					j$('#'+formname+'.img_url').val(img_tag);
				}
				tb_remove();
				// Preview uploaded image
				if (formname === 'slideshow_image_url') {
					insert_img = '<img src="'+img_tag+'" />';
					j$('#uploadedImageView').html(insert_img);
					if (j$("#uploadedImageView").is(":hidden")) {
						j$("#uploadedImageView").slideDown();
					}
				}
			}
		} else {
		    window.original_send_to_editor(html);
		}
	};
}
j$(document).ready(function() {
	if (j$("#uploadedImageView").find("img#exist_slide_image")[0]) {
		j$("#uploadedImageView").show();
	}
	var formname, urlfield;
	// Call media_upload.php
	j$('.dp_upload_image_button').click(function() {
		// j$('#slideshow_image_url').addClass('image');
		// formname = j$('.image').attr('name');
		urlfield = j$(this).prev('.img_url');
		formname = urlfield.attr('name');
		tb_show('', 'media-upload.php?type=image&TB_iframe=true');
		getUploadImageInfo(formname);
		return false;
	});
});