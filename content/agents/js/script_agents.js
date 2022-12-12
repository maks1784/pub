function modalRecDelAgn(sm,ai){
    var dca =$("#del_comm_agent").val();
    $.ajax({
		url: 'content/agents/php/submit_del_agents.php',
		type: 'POST',
		cache: false,
		data: {'agnid':ai,'sm':sm,'dca':dca},
		dataType: 'html',
		success: function(data) {
		    //console.log(data);
		    $("#info_form_del_a span").addClass('d-none');
		    if(data != '200'){
		        $("."+data).removeClass('d-none');
		    }else{
		        $("#submit_del_agn").html("Успешно");
		        setTimeout(function() {
                    $('#modalDelAgent').modal('toggle');
                    uploadAgents();
                }, 1000);
                setTimeout(function() {
                    $("#submit_del_agn").html("Подтвердить");
                }, 1300);
		    }
		}
	});
}
function uploadModalAg(sm,ai){
   // var td_date = moment().format("YYYY-MM-DD");
   // var smod = moment().format("YYYY-MM-DD");
    
    $.ajax({
		url: 'content/agents/modal/modal_body_agents.php',
		type: 'POST',
		cache: false,
		data: {'sm':sm,'ai':ai},
		dataType: 'html',
		success: function(data) {
		    //console.log("// "+data+" //");
		    //$("body").append(data);
		    $("#modalAddAgent").find(".modal-content").html(data);
		    setTimeout(function() {
		        $("#submit_add_user").val(sm);
            }, 10);
		   /* if(data == "null"){
		        $("#more_btn_raboda-"+id).html("Это всё!");
		        
		    }else{
		        $("#delaus_userus-"+id).append(data);
		    }*/
		}
	});
}
function uploadAgents(){
   /* var st = $('#reportrange').data('daterangepicker').startDate.format('YYYY-MM-DD');
    var en = $('#reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD');
    var us = $("#select_filt_users").val();*/
    var sa = $("#search_agent").val();
    
   // var td_date = moment().format("YYYY-MM-DD");
    $.ajax({
		url: 'content/agents/ajax/ajax_agents_content.php',
		type: 'POST',
		cache: false,
		data: {'sa':sa},
		dataType: 'html',
		success: function(data) {
		    //console.log("// "+data+" //");
		    //$("body").append(data);
		    $("#agents_content").html(data);
		    
		   /* if(data == "null"){
		        $("#more_btn_raboda-"+id).html("Это всё!");
		        
		    }else{
		        $("#delaus_userus-"+id).append(data);
		    }*/
		    
		}
	});
}
function updateTableAgentsS(){/*
        var arra = [];
        var dn_rows = [];
        setTimeout(function() {
            $('.tr_row_agent').each(function(i, input) {
                var usu_id = $(input).data('usu');
                var row_id = $(input).data('id-row');
                if(usu_id){
                    if( /\,/.test(usu_id)){
                        arra = usu_id.split(/[\s,]+/);
                        //console.log(arra);
                        $.each(arra,function(index,value){
                            //console.log(row_id);
                            dn_rows.push(value);
                            $("#tr_agent-"+value).insertAfter("#tr_agent-"+row_id);
                        });
                        
                    }else{
                        $("#tr_agent-"+usu_id).insertAfter("#tr_agent-"+row_id);
                        dn_rows.push(usu_id);
                    }
                }
            });
            $.each(dn_rows,function(index,value){
                $("#tr_agent-"+value).addClass('d-none');
            });
            $("#agents_content").removeClass("d-none");
        }, 200);
    */
};
window.onload = function updateTableAgents(){/*
        var arra = [];
        var dn_rows = [];
        setTimeout(function() {
            $('.tr_row_agent').each(function(i, input) {
                var usu_id = $(input).data('usu');
                var row_id = $(input).data('id-row');
                if(usu_id){
                    if( /\,/.test(usu_id)){
                        arra = usu_id.split(/[\s,]+/);
                        //console.log(arra);
                        $.each(arra,function(index,value){
                            //console.log(row_id);
                            dn_rows.push(value);
                            $("#tr_agent-"+value).insertAfter("#tr_agent-"+row_id);
                        });
                        
                    }else{
                        $("#tr_agent-"+usu_id).insertAfter("#tr_agent-"+row_id);
                        dn_rows.push(usu_id);
                    }
                }
            });
            $.each(dn_rows,function(index,value){
                $("#tr_agent-"+value).addClass('d-none');
            });
            $("#agents_content").removeClass("d-none");
        }, 200);
*/
};

$(document).ready(function(){
    var sts, lgu, psu, inc, pra, prb, prc, pre, usi, gusi, aru, usu_id, rin, dis=false, smod, agid, rowc, pay='';
    uploadAgents();
    setTimeout(function() {
        $("#count_users_ag").html("Всего агентов: " + $('#agents_content .tr_row_agent').length);
    }, 700);
    $(document).on('click','#submit_add_user', function(){
        sts = $("#status_user_select").val();
        lgu = $("#login_new_user").val();
        psu = $("#pass_new_user").val();
        inc = "";
        igi = $("#id_form_submit").val();
        if(sts == "moderator"){
            pay = $("#pay_new_user").val();
        }
        if(sts == "agent"){
            inc = [];
            $('.inp_pto_ctg').each(function(i, input) {
                inc.push();
                inc.push({
                    'id': $(input).data('pto-id'), 
                    'price_a': $(input).find(".inp_price_a").val(), 
                    'price_b': $(input).find(".inp_price_b").val(), 
                    'price_c': $(input).find(".inp_price_c").val(), 
                    'price_e': $(input).find(".inp_price_e").val(),
                    
                    'price_a_old': $(input).find(".inp_price_a").data('price'), 
                    'price_b_old': $(input).find(".inp_price_b").data('price'), 
                    'price_c_old': $(input).find(".inp_price_c").data('price'), 
                    'price_e_old': $(input).find(".inp_price_e").data('price')
                    
                });
            });
        }
        
        
        //console.log(inc);
        
        $.ajax({
    		url: 'content/agents/php/submit_add_user.php',
    		type: 'POST',
    		cache: false,
    		data: {'sts':sts,'lgu':lgu,'psu':psu,'inc':inc,'igi':igi,'pay':pay},
    		dataType: 'html',
    		success: function(data) {
    		    //console.log(data);
    		    $("#info_form_ag span").addClass('d-none');
    		    if(data != "200"){
    		        $("."+data).removeClass('d-none');
    		    }else{
    		        if(igi === ''){
        		        $("#login_new_user").val("");
        		        $("#pass_new_user").val("");
    		        }
    		        $("#submit_add_user").html("Успешно");
    		        setTimeout(function() {
                        $('#modalAddAgent').modal('toggle');
                        uploadAgents();
                    }, 1000);
                    setTimeout(function() {
                        $("#submit_add_user").html("Сохранить");
                    }, 1300);
                    
    		    }
    		}
    	});
    });
     $(document).on('click','#open_modal_agents', function(){
        smod = 'add';
        agid = '';
        uploadModalAg(smod,agid);
     });
     $(document).on('click','.edit_modal_agents', function(){
        smod = 'edit';
        agid = $(this).data("id-row");
        
        uploadModalAg(smod,agid);
     });
     
    $(document).on('click','.del_modal_agn', function(){
        $("#del_comm_agent").prop('disabled',false);
        $("#submit_del_agn").val($(this).data("id-row"));
        $("#sts_modal_rec_or_del").val("del");
        $("#modalDelAgentLabel").html("Блокировать пользователя: <b>"+$(this).data("lg-row")+"</b>?");
    });
    $(document).on('click','.rec_modal_agn', function(){
        $("#del_comm_agent").prop('disabled',true);
        $("#submit_del_agn").val($(this).data("id-row"));
        $("#del_comm_agent").val($(this).data("del-comm"));
        $("#sts_modal_rec_or_del").val("rec");
        $("#modalDelAgentLabel").html("Разблокировать пользователя <b>"+$(this).data("lg-row")+"</b>?");
    });
    $(document).on('click','#submit_del_agn', function(){
        agid = this.value;
        smod = $("#sts_modal_rec_or_del").val();
        modalRecDelAgn(smod,agid);
    });
    
    /* СТАРАЯ ВЕРСИЯ, Сейчас без подподподагентов
    $(document).on('click','.open_pdagent_btn', function(){
        if(!dis){
            dis=true;
            aru=[];
            usi = $(this).data('usu');
            gusi = $(this).data('gen-usu');
            
    //////////
            if($(this).html() == '<i class="bi bi-plus"></i>'){
            //console.log("Расскрыл");
                if( /\,/.test(usi)){
                    aru = usi.split(/[\s,]+/);
                    $.each(aru,function(index,value){
                        $("#tr_agent-"+value).removeClass("d-none");
                    });
                }else{
                    $("#tr_agent-"+usi).removeClass("d-none");
                }
            }
    /////////
            else if($(this).html() == '<i class="bi bi-dash"></i>'){
            //console.log("Скрыл");
                if( /\,/.test(gusi)){
                    aru = gusi.split(/[\s,]+/);
                    $.each(aru,function(index,value){
                        $("#tr_agent-"+value).addClass("d-none");
                        $("#tr_agent-"+value).find(".open_pdagent_btn").html('<i class="bi bi-plus"></i>');
                    });
                }else{
                    $("#tr_agent-"+gusi).addClass("d-none");
                    $("#tr_agent-"+gusi).find(".open_pdagent_btn").html('<i class="bi bi-plus"></i>');
                    
                }
            
            }
    /////////        
            
            $(this).html($(this).html() == '<i class="bi bi-plus"></i>' ? '<i class="bi bi-dash"></i>' : '<i class="bi bi-plus"></i>');
            dis=false;
        }
    });*/

    
    $(document).on('change','#status_user_select', function(){
        if(this.value == "agent"){
            $("#moder_pay_content").addClass('d-none');
            $("#table_pto_content").removeClass('d-none');
        }else if(this.value == "moderator"){
            $("#moder_pay_content").removeClass('d-none');
            $("#table_pto_content").addClass('d-none');
        }else{
            $("#moder_pay_content").addClass('d-none');
            $("#table_pto_content").addClass('d-none');
        }
    });
    
    $(document).on('keyup','.inp_price_ext', function(){
        var d_inp = $(this).data("input");
        var v_inp = $(this).val();
        if(v_inp.length > 0){
            $("."+d_inp).val(Number.parseInt($("."+d_inp).val())+Number.parseInt(v_inp));
        }else{
            
        }

    });
    $("#search_agent").on('input', function() {
        $(".open_pdagent_btn").hide();
        $(".tr_row_agent").removeClass("d-none");
        let text = $(this).val().toLowerCase();
        if($(this).val() == ""){
            $(".open_pdagent_btn").show();
        }
        $(".tr_row_agent").find(".search_text_input").each(function() {
            const $this = $(this);
            
            if($this.data("lgn").toLowerCase().indexOf(text) === -1) {
                //Если данные из инпута поиска не совпадают, то скрываем строку
              /*  $(".open_pdagent_btn").hide();
                $(".tr_row_agent").show();*/
                $(this).parent().hide();
            } else {
                //Если данные из инпута поиска совпадают, то показываем строку
                $(this).parent().show();
               /* $(".open_pdagent_btn").show();
                updateTableAgents();*/
            }
            if($(".tr_row_agent:visible").length === 0){
                $(".mail_modal_nullsearch").removeClass('d-none');
            }else{
                $(".mail_modal_nullsearch").addClass('d-none');
            }
        });
    });
    
});