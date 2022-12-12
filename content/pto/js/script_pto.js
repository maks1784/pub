function modalRecDelPto(sm,pi){
    $.ajax({
		url: 'content/pto/php/submit_del_pto.php',
		type: 'POST',
		cache: false,
		data: {'ptoid':pi,'sm':sm},
		dataType: 'html',
		success: function(data) {
		    
		    if(data){
		        alert(data);
		    }else{
		        $("#submit_del_pto").html("Успешно");
		        setTimeout(function() {
                    $('#delPtoModal').modal('toggle');
                    uploadPto();
                }, 1000);
                setTimeout(function() {
                    $("#submit_del_pto").html("Подтвердить");
                }, 1300);
		    }
		}
	});
}

function uploadModalPto(sm,pi){
   // var td_date = moment().format("YYYY-MM-DD");
   // var smod = moment().format("YYYY-MM-DD");
    
    $.ajax({
		url: 'content/pto/modal/modal_body_pto.php',
		type: 'POST',
		cache: false,
		data: {'sm':sm,'pi':pi},
		dataType: 'html',
		success: function(data) {
		    //console.log("// "+data+" //");
		    //$("body").append(data);
		    $("#modalAddPto").find(".modal-content").html(data);
		   /* if(data == "null"){
		        $("#more_btn_raboda-"+id).html("Это всё!");
		        
		    }else{
		        $("#delaus_userus-"+id).append(data);
		    }*/
		}
	});
}
function uploadPto(){
   // var td_date = moment().format("YYYY-MM-DD");
    $.ajax({
		url: 'content/pto/ajax/ajax_pto_content.php',
		type: 'POST',
		cache: false,
		/*data: {'st':st,'en':en,'us':us,'to':to},*/
		dataType: 'html',
		success: function(data) {
		    //console.log("// "+data+" //");
		    //$("body").append(data);
		    $("#pto_content").html(data);
		   /* if(data == "null"){
		        $("#more_btn_raboda-"+id).html("Это всё!");
		        
		    }else{
		        $("#delaus_userus-"+id).append(data);
		    }*/
		}
	});
}
$(document).ready(function(){
    uploadPto();
    var nto, toa, tob, toc, toe, smod, ptoid;
    
    $(document).on('click','#open_modal_pto', function(){
        smod = 'add';
        ptoid = '';
        uploadModalPto(smod,ptoid);
    });
    
    $(document).on('click','.edit_modal_pto', function(){
        smod = 'edit';
        ptoid = $(this).data("id-row");
        uploadModalPto(smod,ptoid);
    });
    $(document).on('click','.del_modal_pto', function(){
        $("#submit_del_pto").val($(this).data("id-row"));
        $("#sts_modal_rec_or_del").val("del");
        $("#delPtoModalLabel").html("Блокировать ПТО?");
    });
     $(document).on('click','.rec_modal_pto', function(){
        $("#submit_del_pto").val($(this).data("id-row"));
        $("#sts_modal_rec_or_del").val("rec");
        $("#delPtoModalLabel").html("Разблокировать ПТО?");
    });
    $(document).on('click','#submit_del_pto', function(){
        ptoid = this.value;
        smod = $("#sts_modal_rec_or_del").val();
        modalRecDelPto(smod,ptoid);
    });
    $(document).on('click','#submit_add_pto', function(){
        nto = $("#add_pto_name").val();
        toa = $("#add_pto_a").val();
        tob = $("#add_pto_b").val();
        toc = $("#add_pto_c").val();
        toe = $("#add_pto_e").val();
        toi = $(this).val();
        //console.log(sts+" "+lgu+" "+psu);
        $.ajax({
    		url: 'content/pto/php/submit_add_pto.php',
    		type: 'POST',
    		cache: false,
    		data: {'nto':nto,'toa':toa,'tob':tob,'toc':toc,'toe':toe,'toi':toi},
    		dataType: 'html',
    		success: function(data) {
    		    
    		    $("#info_form_pto span").addClass('d-none');
    		    if(data != "200"){
    		        $("."+data).removeClass('d-none');
    		    }else{
    		        if(toi === ''){
    		            $("#modalAddPto").find(".form-control").val("");
    		        }
    		        $("#submit_add_pto").html("Успешно");
    		        setTimeout(function() {
                        $('#modalAddPto').modal('toggle');
                        uploadPto();
                    }, 1000);
                    setTimeout(function() {
                        $("#submit_add_pto").html("Сохранить");
                    }, 1300);
                    
    		    }
    		}
    	});
    });
});