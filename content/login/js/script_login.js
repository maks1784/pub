
$(document).ready(function(){
    var regist_login,
        regist_password,
        login_login,
        login_password;
        

    $(document).on('click', '#submit_regist', function(){
        regist_login=$("#login").val();
        regist_password=$("#password").val();
        $.ajax({
    		url: 'content/login/php/regist.php',
    		type: 'POST',
    		cache: false,
    		data: {'login':regist_login,'password':regist_password},
    		dataType: 'html',
    		success: function(data) {
    		    alert("Regist");
    		    console.log(data)
    		}
    	});
    });
    
    $(document).on('click', '#submit_login', function(){
        login_login=$("#login").val();
        login_password=$("#password").val();
        $.ajax({
    		url: 'content/login/php/login.php',
    		type: 'POST',
    		cache: false,
    		data: {'login':login_login,'password':login_password},
    		dataType: 'html',
    		success: function(data) {
    		    $("#info_form span").addClass('d-none');
    		    if(data != "200"){
    		        $("."+data).removeClass('d-none');
    		    }else{
    		        location.reload();
    		    }
    		}
    	});
    });
        $('#login_body').keypress(function(event) {
            if(event.keyCode == 13) {
                $("#submit_login").click();
                return true;
            }
        });   
    
    $(document).on('click', '#submit_logout', function(){
        $.ajax({
    		url: 'content/common/php/logout.php',
    		cache: false,
    		success: function(data) {
    		    location.reload();
    		}
    	});
    });
    
    /*$(document).on('click', '.visor_password', function(){
        if($("input[id='regist_password']").attr("type") == "password" ){
            
            $(this).html(feather.icons['eye-off'].toSvg());
            $(".input_password_visor").attr("type","text");
        }else if ($("input[id='regist_password']").attr("type") == "text" ){
            
            $(this).html(feather.icons['eye'].toSvg());
            $(".input_password_visor").attr("type","password");
        }
    });*/
    
});