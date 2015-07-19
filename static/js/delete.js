jQuery(document).ready(function() {
	displayRemoveButton();
	    
    jQuery('.delete_div_button').click(function(){
    	jQuery(this).parent().remove();
    	
    	displayRemoveButton();
    });
    
    
    function displayRemoveButton()
    {
    	if (jQuery('.container').length <= 1) {
    		jQuery('.delete_div_button').hide();
    	} else {
    		jQuery('.delete_div_button').show();
    	}
    }
});