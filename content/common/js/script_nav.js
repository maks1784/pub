$(document).ready(function(){
    $(document).on('click', '#submit_logout', function(){
        $.ajax({
    		url: 'content/common/php/logout.php',
    		cache: false,
    		success: function(data) {
    		    location.reload();
    		}
    	});
    });
});