function uploadFinance(){
    var st = $('#reportrange').data('daterangepicker').startDate.format('YYYY-MM-DD');
    var en = $('#reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD');
    var us = $("#select_filt_users").val();
    var to = $("#select_filt_pto").val();
    //console.log(st+" "+en);
   // var td_date = moment().format("YYYY-MM-DD");
    $.ajax({
		url: 'content/finance/ajax/ajax_finance_content.php',
		type: 'POST',
		cache: false,
		data: {'st':st,'en':en,'us':us,'to':to},
		dataType: 'html',
		success: function(data) {
		    //console.log("// "+data+" //");
		    //$("body").append(data);
		    $("#finance_content").html(data);
		    calc_total();
		    //updateTableAgentsS();
		   /* if(data == "null"){
		        $("#more_btn_raboda-"+id).html("Это всё!");
		        
		    }else{
		        $("#delaus_userus-"+id).append(data);
		    }*/
		    
		}
	});
}
function calc_total(){
  /*$(".tr_row_agent").each(function(i,input){
    $(input).find('.sum_profiti').text($(input).find(".sum_income").text() - $(input).find(".sum_expense").text());
  });*/
  var sum = 0;
  
  $(".sum_ctg_a").each(function(){
    sum += parseFloat($(this).text());
  });
  $('#sum_ctg_a').text(sum);
  sum = 0;
  $(".sum_ctg_b").each(function(){
    sum += parseFloat($(this).text());
  });
  $('#sum_ctg_b').text(sum);
  sum = 0;
  $(".sum_ctg_c").each(function(){
    sum += parseFloat($(this).text());
  });
  $('#sum_ctg_c').text(sum);
  sum = 0;
  $(".sum_ctg_d").each(function(){
    sum += parseFloat($(this).text());
  });
  $('#sum_ctg_d').text(sum);
  sum = 0;
  $(".sum_order").each(function(){
    sum += parseFloat($(this).text());
  });
  $('#sum_order').text(sum);
  sum = 0;
  $(".sum_income").each(function(){
    sum += parseFloat($(this).text());
  });
  $('#sum_income').text(sum);
  sum = 0;
  $(".sum_expense").each(function(){
    sum += parseFloat($(this).text());
  });
  $('#sum_expense').text(sum);
  
  sum = 0;
  $(".sum_profiti").each(function(){
      
      sum += parseFloat($(this).text());
      //console.log(sum);
  });
  $('#sum_profiti').text(sum);
  
    sum = 0;
    var i=0;
    $(".sum_balance").each(function(){
        if(i!=0){
            sum += parseFloat($(this).data('balance'));
        }
        i++;
    });
    $('#sum_balance').text(sum+' руб.');
  
  
}

function updateTableAgentsS(){
   /* var arra = [];
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
        $("#finance_content").removeClass("d-none");
    }, 200);*/
};

window.onload = function updateTableAgents(){
    /*var arra = [];
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
        $("#finance_content").removeClass("d-none");
    }, 200);*/
};



$(document).ready(function(){
    var uid,vfr,psu,td_date,dis=false,ev,st,en,us,to;
    
    uploadFinance();
    setTimeout(function() {
        $("#count_users").html("Всего агентов: " + $('#finance_content .tr_row_agent').length);
    }, 500);
    $(document).on('click','.add_finance_btn', function(){
        $("#login_lbl_modal_finance").html($(this).data('login-user'));
        $("#user_id_add_finance").val($(this).data('id-user'));
    });
    
    /* СТАРЫЙ СКРИПТ, для открывания подагентов по всей цепочки
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
    }); */
    
    //Новый скрипт

    $(document).on('click','.excel_fin', function() {
        ev = this.value;
        su = $(this).data('sts');
        st = $('#reportrange').data('daterangepicker').startDate.format('YYYY-MM-DD');
        en = $('#reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD');
        us = $("#select_filt_users").val();
        to = $("#select_filt_pto").val();
        $.ajax ({
            url: 'content/finance/ajax/ajax_excel_fin.php',
            type: 'POST',
            cache: false,
            data: {'ev':ev,'su':su,'st':st,'en':en,'us':us,'to':to},
            dataType: 'html',
            success: function (data){
               // console.log(data);
                if(data != "ri34fo"){
                    window.open(data, '_blank');
                }else{
                    alert("Нет данных");
                }
            } 
        });  
    });
    
    $(document).on('click','#submit_add_finance', function(){
        uid = $("#user_id_add_finance").val();
        vfr = $("#value_add_finance").val();
        td_date = moment().format("YYYY-MM-DD HH:mm:ss");
        $.ajax({
    		url: 'content/finance/php/submit_add_finance.php',
    		type: 'POST',
    		cache: false,
    		data: {'uid':uid,'vfr':vfr,'td_date':td_date},
    		dataType: 'html',
    		success: function(data) {
    		    //console.log("// "+data+" //");
    		    $("#info_form_finance span").addClass('d-none');
    		    if(data != "200"){
    		        setTimeout(function() {
    		            $("."+data).removeClass('d-none');
    		        }, 40);
    		    }else{
    		        $("#value_add_finance").val("");
    		        $("#submit_add_finance").html("Успешно");
    		        setTimeout(function() {
                        $('#modalAddFinance').modal('toggle');
                        uploadFinance();
                    }, 1000);
                    setTimeout(function() {
                        $("#submit_add_finance").html("Пополнить");
                    }, 1300);
                    
    		    }
    		    
    		}
    	});
    });
    $("#search_filt_row").on('input', function() {
        let text = $(this).val().toLowerCase();
        $(".tr_row_agent").find(".search_text_input").each(function() {
            const $this = $(this);
            if($this.data("lgn").toLowerCase().indexOf(text) === -1) {
                $(this).parent().hide();
            } else {
                $(this).parent().show();
            }
            if($(".tr_row_agent:visible").length === 0){
                $(".mail_modal_nullsearch").removeClass('d-none');
            }else{
                $(".mail_modal_nullsearch").addClass('d-none');
            }
        });
    });
    $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
       // startD=picker.startDate.format('YYYY-MM-DD');
        //endD=picker.endDate.format('YYYY-MM-DD'); 
        uploadFinance();
    });
    $(document).on('change','#select_filt_users', function(){
        uploadFinance();
    });
    $(document).on('change','#select_filt_pto', function(){
        uploadFinance();
    });
});