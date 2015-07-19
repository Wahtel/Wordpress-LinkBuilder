jQuery(document).ready(function() {
    var add_button = jQuery(".add_field_button"); 
	
    jQuery(add_button).click(function(){ 
            jQuery(".input_fields_wrap div").first().clone(true).appendTo(".input_fields_wrap").find("input[type='text']").val("");
            jQuery(".delete_div_button").show();
            changeInputsName();
    });
 	inputsValidation();
});

 
function changeInputsName()
{
	jQuery("input.LinkbuilderForWords").each(function(i) {
	  jQuery(this).attr("name", "linkbuilder["+i+"][word]");
	});
	jQuery("input.LinkbuilderForLinks").each(function(i) {
	  jQuery(this).attr("name", "linkbuilder["+i+"][link]");
	});
} // end changeInputsName


function inputsValidation()
{
	jQuery("form").submit(function(){
		var errors = false;
		jQuery(".container").each(function(){
			if(jQuery(this).find(".LinkbuilderForWords").val() == "" && jQuery(this).find(".LinkbuilderForLinks").val() == "" ){
		        jQuery(this).remove();
		        return;
			}
			
			if(jQuery(this).find(".LinkbuilderForWords").val() == "") {
				jQuery(this).find(".LinkbuilderForWords").addClass("error"); 
				errors = true;
				return;
			}
			if(jQuery(this).find(".LinkbuilderForLinks").val() == "") {
				jQuery(this).find(".LinkbuilderForLinks").addClass("error"); 
				errors = true;
				return;
			}
		})
			if (errors == true) {
				return false;
			}
	});
} // end inputsValidation


