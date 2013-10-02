/*!
 * Admin js
 */

jQuery(document).ready(function() {

// WP image uploader for custom use in the admin section
		var uploadparent = 0;
		var old_send_to_editor = window.send_to_editor;
		var old_tb_remove = window.tb_remove;

		jQuery('.upload_image_button').click(function(){
			uploadparent = jQuery(this).closest('div');
			//Change "insert into post" to "Use this Button"
     		tbframe_interval = setInterval( function() {jQuery('#TB_iframeContent').contents().find('.savesend .button').val('Use This Image');}, 2000);
			tb_show('Select File', 'media-upload.php?post_id=0&amp;type=file&amp;TB_iframe=true');
			return false;
		});

		window.tb_remove = function() {
			uploadparent = 0;
			old_tb_remove();
		}

		window.send_to_editor = function(html) {
			if(uploadparent){
				imgurl = jQuery('img',html).attr('src');
				uploadparent.find('.slideimages').attr('value', imgurl);
				uploadparent.find('.imagebox').attr('src', imgurl);
				tb_remove();
			} else {
				old_send_to_editor();
			}
			}			
			
			
// Show/hide slides
		jQuery('.slidetitle').click(function() {
				jQuery(this).next().toggle("fast");
				});


// Jquery confim window on reset to defaults
jQuery('#parabola_defaults').click (function() {
		if (!confirm(reset_confirmation)) { return false;}
	});

// Jquery confim window on loading a color scheme
jQuery('#load-color-scheme').click (function() {
		if (!confirm(scheme_confirmation)) { return false;}
	});


// Hide or show slider settings
jQuery('#parabola_slideType').change(function() {
	jQuery('.slideDivs').hide();
	switch (jQuery('#parabola_slideType option:selected').val()) {

		case "Custom Slides" :
 		jQuery('#sliderCustomSlides').show("normal");
		break;

		case "Latest Posts" :
 		jQuery('#sliderLatestPosts').show("normal");
		break;

		case "Random Posts" :
 		jQuery('#sliderRandomPosts').show("normal");
		break;

		case "Sticky Posts" :
 		jQuery('#sliderStickyPosts').show("normal");
		break;

		case "Latest Posts from Category" :
 		jQuery('#sliderLatestCateg').show("normal");
		break;

		case "Random Posts from Category" :
 		jQuery('#sliderRandomCateg').show("normal");
		break;

		case "Specific Posts" :
 		jQuery('#sliderSpecificPosts').show("normal");
		break;

	}//switch
	
	sliderNr=jQuery('#parabola_slideType').val();
	//Show category if a category type is selected
	if (sliderNr=="Latest Posts from Category" || sliderNr=="Random Posts from Category" )
			jQuery('#slider-category').show();
	else 	jQuery('#slider-category').hide();
	//Show number of slides if that's the case
	if (sliderNr=="Latest Posts" || sliderNr =="Random Posts" || sliderNr =="Sticky Posts" || sliderNr=="Latest Posts from Category" || sliderNr=="Random Posts from Category" )
			jQuery('#slider-post-number').show();
	else 	jQuery('#slider-post-number').hide();

});//function

jQuery('.slideDivs').hide();
jQuery('#parabola_slideType').trigger('change');

//var parabola_customcss = CodeMirror.fromTextArea(document.getElementById("parabola_customcss"), { lineNumbers: true });
//var parabola_customjs = CodeMirror.fromTextArea(document.getElementById("parabola_customjs"), { lineNumbers: true });

// Create accordion from existing settings table
	jQuery('.form-table').wrap('<div>');
	jQuery(function() {
			jQuery( "#accordion" ).accordion({
				header: 'h3',
				autoHeight: false, // for jQueryUI <1.10
				heightStyle: "content", // required in jQueryUI 1.10
				collapsible: true,
				navigation: true,
				active: false
				});
	});

	jQuery("#parabola_nrcolumns").bind('change', function() {
		column_image_width_hint(jQuery("#totalsize").html(),jQuery("#parabola_nrcolumns").val());
	});										
	jQuery("#parabola_nrcolumns").trigger('change');
		
});// ready
  
// Columns image width hint
function column_image_width_hint(total, colcount) {
if (colcount==0) var size = 0;
else 
	var size = parseInt((total-(colcount*7*2)-(total*2*(colcount-1)/100))/colcount-14);

jQuery('#parabola_colimagewidth').html(size);

}

  // Change border for selecte inputs
function changeBorder (idName, className) {
	jQuery('.'+className).removeClass( 'checkedClass' );
	jQuery('.'+className).removeClass( 'borderful' );
	jQuery('#'+idName).addClass( 'borderful' );

return 0;
}