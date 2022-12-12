function uploadBalance(){
    var st = $('#reportrange').data('daterangepicker').startDate.format('YYYY-MM-DD');
    var en = $('#reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD');
    var us = $("#select_filt_users").val();
    var di = $("#select_filt_doit").val();
    var pp = '0';
    if($('.pagination_page').length > 0){
        pp = $(".pagination_page").find('.active').data('start-row');
    }
    //console.log("LIMIT "+pp+", 10");
   // var td_date = moment().format("YYYY-MM-DD");
    $.ajax({
		url: 'content/balance/ajax/ajax_balance_content.php',
		type: 'POST',
		cache: false,
		data: {'st':st,'en':en,'us':us,'di':di,'pp':pp},
		dataType: 'html',
		success: function(data) {
		    
		    //$("body").append(data);
		    $("#balance_content").html(data);
    		setTimeout(function() {
    		    //Условие, если количество строк меньше Указателя кол-ва строк в фильтре ИЛИ кол-во строк равно общему числу всех строк
    		    
        		if(Number.parseInt($('.balance_tr_row').length) < Number.parseInt($("#select_cnt_blc").val()) || Number.parseInt($('.balance_tr_row').length) == Number.parseInt($('.mail_modal_nullsearch').data('count-rows'))){
    		        if($(".pagination_page").find('.active').html() != 1 && $(".pagination_page").length !== 0){
    		            lbl_start = $('.pagination_page').find(".active").data('label-from');
    		        }else{
    		            lbl_start = "с 1";
    		        }
    		        lbl_finish = "по "+$('.mail_modal_nullsearch').data('count-rows');
    		    }else{
    		        lbl_start = $('.pagination_page').find(".active").data('label-from');
    		        lbl_finish = $('.pagination_page').find(".active").attr('data-label-to');
    		    }
                $("#count_balance_rows").html("Показано записей "+ lbl_start +" "+ lbl_finish +" из " + $('.mail_modal_nullsearch').data('count-rows'));
    		    
		    },900);
		   /* if(data == "null"){
		        $("#more_btn_raboda-"+id).html("Это всё!");
		        
		    }else{
		        $("#delaus_userus-"+id).append(data);
		    }*/
		    
		}
	});
}
function countRows(){
    if($('.mail_modal_nullsearch').length > 0){
        var cp = $('.mail_modal_nullsearch').data('count-rows');
        
        //console.log($('.mail_modal_nullsearch').length);
        $.ajax({
    		url: 'content/balance/ajax/ajax_pagination.php',
    		type: 'POST',
    		cache: false,
    		data: {'cp':cp},
    		dataType: 'html',
    		success: function(data) {
    		    //console.log("// "+data+" //");
    		    $(".pagination_nav").html(data);
    		    $(".pagination_nav").removeClass("d-none");
    		    $("#count_balance_row").html("Всего записей: " + $('.mail_modal_nullsearch').data('count-rows')+" в истории баланса по ");
    		}
    	});
    }else{
        $("#count_balance_row").html("Всего записей: 0 в истории баланса по ");
        $("#count_balance_rows").html("Показано записей c 1 по 8 из 0");
        
    }
}
$(document).ready(function(){
    var vcnt, pgs, dp=false,dpr=false,dpl=false;
    uploadBalance();
    setTimeout(function() {
        countRows();
    },500);
    $("#search_balance").on('input', function() {
        let text = $(this).val().toLowerCase();
        $(".balance_tr_row").find(".search_text_input").each(function() {
            const $this = $(this);
            if($this.html().toLowerCase().indexOf(text) === -1) {
                $(this).parent().hide();
            } else {
                $(this).parent().show();
            }
            if($(".balance_tr_row:visible").length === 0){
                $(".mail_modal_nullsearch").removeClass('d-none');
            }else{
                $(".mail_modal_nullsearch").addClass('d-none');
            }
        });
    });
    $(document).on('change','#select_cnt_blc', function(){
        vcnt = $(this).val();
        $.ajax({
    		url: 'content/orders/php/submit_change_cnt.php',
    		type: 'POST',
    		cache: false,
    		data: {'vcnt':vcnt},
    		dataType: 'html',
    		success: function(data) {
    		    uploadBalance();
    		    setTimeout(function() {
                    countRows();
                },500);
    		 
    		}
    	});
    });
    $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
       // startD=picker.startDate.format('YYYY-MM-DD');
        //endD=picker.endDate.format('YYYY-MM-DD'); 
        uploadBalance();
        setTimeout(function() {
            countRows();
        },500);
    });
    $(document).on('change','#select_filt_users', function(){
        uploadBalance();
        setTimeout(function() {
            countRows();
        },500);
    });
    $(document).on('change','#select_filt_doit', function(){
        uploadBalance();
        setTimeout(function() {
            countRows();
        },500);
    });
    $(document).on('click','.pagination_page', function(){
        if(!dp){
            dp=true;
            var new_a = $(this).find(".page-link").html();
            var crw = $(".pagination_page").length;
            $(".pagination_page").find(".page-link").removeClass('active');
            $(this).find(".page-link").addClass('active');
            uploadBalance();
            $('.pagination_page_right').find('#data_pag_right').val(Number.parseInt(new_a)+1);
            $('.pagination_page_left').find('#data_pag_left').val(Number.parseInt(new_a)-1);
            if(Number.parseInt(new_a) == 1){
                $('.pagination_page_left').addClass("disabled");
            }
            if(Number.parseInt(new_a) > 1){
                $('.pagination_page_left').removeClass("disabled");
            }
            if(crw == new_a){
                $('.pagination_page_right').addClass("disabled");
            }else{
                $('.pagination_page_right').removeClass("disabled");
            }
            setTimeout(function() {
                dp=false;
            }, 500);
        }
    });
    
    $(document).on('click','.pagination_page_right', function(){
        if(!dpr){
            if(!$(this).hasClass("disabled")){
                dpr=true;
                var new_a = $(this).find('#data_pag_right').val();
                var crw = $(".pagination_page").length;
                var enb = $("#pag_btn-"+new_a).attr('data-end');
                $(".pagination_page").find(".page-link").removeClass('active');
                $("#pag_btn-"+new_a).find(".page-link").addClass('active');
                uploadBalance();
                $('.pagination_page_right').find('#data_pag_right').val(Number.parseInt(new_a)+1);
                $('.pagination_page_left').find('#data_pag_left').val(Number.parseInt(new_a)-1);

                if(enb != ''){
                    var stb = Number.parseInt(enb)-3;
                    $("#pag_btn-"+enb).removeClass('d-none');
                   // $("#pag_btn-"+stb).addClass('d-none');
                }
                
                if(Number.parseInt(new_a) > 1){
                    $('.pagination_page_left').removeClass("disabled");
                }
                if(crw == new_a){
                    $('.pagination_page_right').addClass("disabled");
                }
                setTimeout(function() {
                    dpr=false;
                }, 500);
            }
        }
    });
    $(document).on('click','.pagination_page_left', function(){
        if(!dpl){
            if(!$(this).hasClass("disabled")){
                dpl=true;
                var new_a = $(this).find('#data_pag_left').val();
                var crw = $(".pagination_page").length;
                $(".pagination_page").find(".page-link").removeClass('active');
                $("#pag_btn-"+new_a).find(".page-link").addClass('active');
                uploadBalance()
                $('.pagination_page_right').find('#data_pag_right').val(Number.parseInt(new_a)+1);
                $('.pagination_page_left').find('#data_pag_left').val(Number.parseInt(new_a)-1);
                if(Number.parseInt(new_a) == 1){
                    $('.pagination_page_left').addClass("disabled");
                }
                if($("#pag_btn-"+Number.parseInt(new_a)+1).length === 0){
                    $('.pagination_page_right').removeClass("disabled");
                }
                if(crw != new_a){
                    $('.pagination_page_right').removeClass("disabled");
                }
                setTimeout(function() {
                    dpl=false;
                }, 500);
            }
        }
    });
    
});