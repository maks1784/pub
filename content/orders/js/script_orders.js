function modalRecDelOrd(oi){
    $.ajax({
		url: 'content/orders/php/submit_del_ord.php',
		type: 'POST',
		cache: false,
		data: {'ord_id':oi},
		dataType: 'html',
		success: function(data) {
		    if(data){
		        alert(data);
		    }else{
		        $("#submit_del_ord").html("Успешно");
		        setTimeout(function() {
                    $('#delOrdModal').modal('toggle');
                    uploadOrders();
                }, 1000);
                setTimeout(function() {
                    $("#submit_del_ord").html("Подтвердить");
                }, 1300);
		    }
		}
	});
}

function uploadModalOrd(sm,oi){

   // var td_date = moment().format("YYYY-MM-DD");
    $.ajax({
		url: 'content/orders/modal/modal_body_order.php',
		type: 'POST',
		cache: false,
		data: {'sm':sm,'oi':oi},
		dataType: 'html',
		success: function(data) {
		    //console.log("// "+data+" //");
		    //$("body").append(data);
		    $("#modalAddOrder .modal-content").html(data);
		    setTimeout(function() {
		        $("#sts_form_submit").val(sm);
            }, 10);
		    
		   /* if(data == "null"){
		        $("#more_btn_raboda-"+id).html("Это всё!");
		        
		    }else{
		        $("#delaus_userus-"+id).append(data);
		    }*/
		    
		}
	});
}
function uploadPtos(){
    var ctgpto = $("#select_ctg_modal_ord").val();
    var orid = $("#oid_modal_ord").val();
    var usid = $("#uid_modal_ord").val();
    var sm = $("#sm_modal_ord").val();
     $.ajax({
		url: 'content/orders/ajax/ajax_pto_select.php',
		type: 'POST',
		cache: false,
		data: {'ctgpto':ctgpto,'orid':orid,'usid':usid,'sm':sm},
		dataType: 'html',
		success: function(data){
		    $("#select_pto_ord").html(data);
		    //console.log(data);
		}
	});
}

function uploadOrders(){
    var st = $('#reportrange').data('daterangepicker').startDate.format('YYYY-MM-DD');
    var en = $('#reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD');
    var cg = $("#select_ctg_ord").val();
    var ag = $("#select_agt_ord").val();
    var ss = $("#select_sts_ord").val();
    var pp = '0';
    if($('.pagination_page').length > 0){
        pp = $(".pagination_page").find('.active').data('start-row');
    }
   // var td_date = moment().format("YYYY-MM-DD");
    $.ajax({
		url: 'content/orders/ajax/ajax_orders_content.php',
		type: 'POST',
		cache: false,
		data: {'st':st,'en':en,'cg':cg,'ag':ag,'ss':ss,'pp':pp},
		dataType: 'html',
		success: function(data) {
		    //console.log("// "+data+" //");
		    //$("body").append(data);
		    $("#orders_content").html(data);
		    setTimeout(function() {
		        //console.log(Number.parseInt($('.orders_tr_row').length) +"<="+ Number.parseInt($("#select_cnt_ord").val()));
    		    if(Number.parseInt($('.orders_tr_row').length) < Number.parseInt($("#select_cnt_ord").val()) || Number.parseInt($('.orders_tr_row').length) == Number.parseInt($('.mail_modal_nullsearch').data('count-rows'))){
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
                $("#count_ord_rows").html("Показано записей "+ lbl_start +" "+ lbl_finish +" из " + $('.mail_modal_nullsearch').data('count-rows'));
		    },900);
		    setTimeout(function() {
		        if(Number.parseInt($('.orders_tr_row').length) !== 0){
		            $("#count_ord_rows").removeClass('d-none');
		            $(".pagination_nav").removeClass('d-none');
		        }
		    },1000);
	   /* if(data == "null"){
		        $("#more_btn_raboda-"+id).html("Это всё!");
		        
		    }else{
		        $("#delaus_userus-"+id).append(data);
		    }*/
		    
		}
	});
}
function changeCategory(){
    
    var ctg = $('#select_ctg_modal_ord option:selected').data("ctg");
    var options = $("#select_pto_ord option");                    // Collect options   
    
   /* options.detach().sort(function(a,b) {               // Detach from select, then Sort
        var at = $(a).data('price');
        var bt = $(b).data('price');         
        return (at > bt)?1:((at < bt)?-1:0);            // Tell the sort function how to order
    });
    options.appendTo("#select_pto_ord");
    $("#select_pto_ord option").addClass("d-none");
    $(".priceCtg"+ctg).removeClass("d-none");*/
    
    /*setTimeout(function() {
        $('#select_pto_ord option:not(.d-none):first').prop('selected', true);
    }, 100);*/
    if(ctg == "C"){
        setTimeout(function() {
            $('#ext_brakes_ord option[value="2"]').prop('selected', true);
            $('#ext_fuel_ord option[value="2"]').prop('selected', true);
            $('.tahograf').show(150);
            
        }, 100);
    }else{
        setTimeout(function() {
            $('#ext_brakes_ord option[value="1"]').prop('selected', true);
            $('#ext_fuel_ord option[value="1"]').prop('selected', true);
            $('.tahograf').hide(150);
        }, 100);
    }
    if(ctg == "E"){
        $("#ext_fuel_ord").append('<option class="not_fuel" value="0">Без топлива</option>');
        $("#ext_fuel_ord").prop('disabled', true);
        $("#ext_fuel_ord").addClass('text-black-50');
        $("#gbo_box_ord").hide();
        $("#ext_mileage_ord").val("0");
        $("#ext_mileage_ord").prop('disabled', true);
        $("#ext_mileage_ord").addClass('text-black-50');
        setTimeout(function() {
            $('#ext_fuel_ord option[value="0"]').prop('selected', true);
        }, 100);
    }else{
        setTimeout(function() {
            $("#gbo_box_ord").show();
            $("#ext_fuel_ord option[value='0']").remove();
            $("#ext_fuel_ord").prop('disabled', false);
            $("#ext_fuel_ord").removeClass('text-black-50');
            if($("#ext_mileage_ord").val() == '' && $("#ext_mileage_ord").val() != '0'){
                $("#ext_mileage_ord").val("");
            }
            $("#ext_mileage_ord").prop('disabled', false);
            $("#ext_mileage_ord").removeClass('text-black-50');
        }, 100);
    }
    setTimeout(function() {
        $('#select_pto_ord').css('opacity','1');
    }, 400);
     if($('#select_pto_ord option[class="priceCtg'+ctg+'"]').length === 0){
        //console.log('234');
        $('#select_pto_ord').append('<option class="none_category" selected value="">ПТО по категории '+ctg+' не найдено</option>');
        return;
    }else{
        $('.none_category').remove();
    }
    
}
function changeCategoryEdit(){
    var ctg = $('#select_ctg_modal_ord option:selected').data("ctg");
    $("#select_pto_ord option").addClass("d-none");
    $(".priceCtg"+ctg).removeClass("d-none");
    
    $('#select_ctg_modal_ord option').each(function() {
      if($(this).prop('selected') === false) {
        changeCategory();
        setTimeout(function() {
            $('#select_pto_ord').css('opacity','1');
        }, 500);
      }
    });
    setTimeout(function() {
        $('#select_pto_ord').css('opacity','1');
    }, 400);
  /*  if($('#select_pto_ord option[class="priceCtg'+ctg+'"]').length === 0){
        //console.log('234');
        $('#select_pto_ord').append('<option class="none_category" selected value="">ПТО по категории '+ctg+' не найдено</option>');
        return;
    }else{
        $('.none_category').remove();
    }*/
    
}
function changeCategoryLook(){
     var ctg = $('#select_ctg_modal_ord option:selected').data("ctg");
    $("#select_pto_ord option").addClass("d-none");
    $(".priceCtg"+ctg).removeClass("d-none");
    setTimeout(function() {
        $('#select_pto_ord').css('opacity','1');
    }, 400);
}
/*function countRows(){
    setTimeout(function() {
        $("#count_ord_row").html("Всего заявок: " + $('#orders_content .orders_tr_row').length +" по ");
        $("#count_ord_rows").html("Показано записей c 1 по 8 из " + $('#orders_content .orders_tr_row').length);
    }, 500);
}*/
function countRows(){
    if($('.mail_modal_nullsearch').length > 0){
        var cp = $('.mail_modal_nullsearch').data('count-rows');
        
        //console.log(cp);
        $.ajax({
    		url: 'content/orders/ajax/ajax_pagination_ord.php',
    		type: 'POST',
    		cache: false,
    		data: {'cp':cp},
    		dataType: 'html',
    		success: function(data) {
    		    //console.log("// "+data+" //");
    		    $(".pagination_nav").html(data);
    		    $(".pagination_nav").removeClass("d-none");
    		    $("#count_ord_row").html("Всего заявок: " + $('.mail_modal_nullsearch').data('count-rows')+" по ");
    		}
    	});
    }else{
        $("#count_ord_row").html("Всего записей: 0 в истории баланса по ");
        $("#count_ord_rows").html("Показано записей c 1 по 8 из 0");
        
    }
}

$(document).ready(function(){
    var oid,mmn, ost, fd, vs, da, td_date, desabled=false, startD, endD, vcnt, dp=false,dpr=false,dpl=false;
    uploadOrders();
    
    setTimeout(function() {
        countRows();
    },500);
    $(document).on('change','#select_ctg_modal_ord', function(){
        setTimeout(function() {
            uploadPtos();
        }, 500);
        setTimeout(function() {
            changeCategory();
        }, 500);
    });
    $(document).on('click','.edit_st_order', function(){
        oid = $(this).data("id-order");
        mmn = $(this).data("mmn");
        $("#submit_edit_st_order").val(oid);
        $("#modalEditStatusOrderLabel b").html(mmn);
    });
    $(document).on('click','#open_modal_ord', function(){
        
        smod = 'add';
        oid = '';
        uploadModalOrd(smod,oid);
        setTimeout(function() {
            uploadPtos();
            //changeCategory();
        }, 500);
        setTimeout(function() {
            changeCategory();
            $('#select_pto_ord').css('opacity','1');
        }, 500);
    });
    $(document).on('click','.edit_modal_ord', function(){
        
        smod = 'edit';
        oid = $(this).data("id-row");
        uploadModalOrd(smod,oid);
        setTimeout(function() {
            //changeCategoryEdit();
        }, 500);
        setTimeout(function() {
            uploadPtos();
            $('#select_pto_ord').css('opacity','1');
        }, 500);
    });
    $(document).on('click','.look_modal_ord', function(){
        smod = 'look';
        oid = $(this).data("id-row");
        uploadModalOrd(smod,oid);
        setTimeout(function() {
           // changeCategoryLook();
        }, 500);
        setTimeout(function() {
            uploadPtos();
            $('#select_pto_ord').css('opacity','1');
        }, 500);
    });
    $(document).on('click','.del_modal_ord', function(){
        oid = $(this).data("id-row");
        $("#submit_del_ord").val(oid);
    });
    
    $(document).on('click','#submit_del_ord', function(){
        oid = this.value;
        modalRecDelOrd(oid);
    });
    
    $(document).on('click','.vin_btn_inp_ord', function(){
        
        $(this).parent().find("input").val($("#car_vin_ord").val());
        
    });
    
    $(document).on('click','#submit_edit_st_order', function(){
        
        if(window.FormData === undefined){
            alert("Ошибка! Ваш браузер не поддерживает данную функцию, используйте Google Chrome");
        }else{
            $("#submit_edit_st_order").html('<i class="bi-clock-history"></i>');
            fd = new FormData();
            fd.append('oid', $(this).val());
            fd.append('ost', $("#status_order_select").val());
            fd.append('td_date', moment().format("YYYY-MM-DD HH:mm:ss"));
            fd.append('pdf_file', $("#new_pdf_ord")[0].files[0]); 
            fd.append('new_pic_front', $("#new_pic_front_ord")[0].files[0]); 
            fd.append('new_pic_back', $("#new_pic_back_ord")[0].files[0]);
            
            $.ajax({
        		url: 'content/orders/php/submit_edit_status_order.php',
        		type: 'POST',
        		cache: false,
        		data: fd,
                dataType: 'html',
        		success: function(data) {
        		    //console.log(data);
        		    $("#info_form_orst span").addClass('d-none');
                    if(data != "200"){
        		        $("."+data).removeClass('d-none');
        		    }else{
        		        $("#submit_edit_st_order").html("Успешно");
        		        setTimeout(function() {
                            $('#modalEditStatusOrder').modal('toggle');
                        }, 1000);
                        setTimeout(function() {
                            $("#submit_edit_st_order").html("Сохранить");
                        }, 1300);
                        uploadOrders();
        		    } 
        		},
                cache: false,
                contentType: false,
                processData: false
        	});
        }
    });
    $(document).on('change','#doc_ord', function(){
       if(this.value=="ЭПТС"){
           $(".ser_doc_toggle").addClass("d-none");
           $(".num_doc_toggle").removeClass("col-md-6").addClass("col-md-9");
       }else{
           $(".ser_doc_toggle").removeClass("d-none");
           $(".num_doc_toggle").removeClass("col-md-9").addClass("col-md-6");
       }
    });
   
    $(document).on('click','#submit_add_order,#draft_add_order', function(){
        vs = $(this).val();
        da = moment().format("YYYY-MM-DD H:m:s");
        if(window.FormData === undefined){
            alert("Ошибка! Ваш браузер не поддерживает данную функцию, используйте Google Chrome");
        }else{
            fd = new FormData();
            fd.append('fstatus', $("#sts_form_submit").val());
            fd.append('fid', $("#id_form_submit").val());
            fd.append('sub', vs);
            fd.append('date_added', da);
            fd.append('ctg_id', $("#select_ctg_modal_ord").val());
            fd.append('doc', $("#doc_ord").val());
            fd.append('doc_s', $("#doc_s_ord").val());
            fd.append('doc_n', $("#doc_n_ord").val());
            fd.append('doc_from', $("#doc_from_ord").val());
            fd.append('doc_date', $("#doc_date_ord").val());
            if($('#doc_owner_ord').is(':checked')) fd.append('doc_owner', '1'); else fd.append('doc_owner', '0');
            fd.append('car_gosn', $("#car_gosn_ord").val());
            fd.append('car_brand', $("#car_brand_ord").val());
            fd.append('car_model', $("#car_model_ord").val());
            fd.append('car_vin', $("#car_vin_ord").val());
            fd.append('car_vin_body', $("#car_vin_body_ord").val());
            fd.append('car_vin_frame', $("#car_vin_frame_ord").val());
            fd.append('car_year', $("#car_year_ord").val());
            fd.append('car_weight_max', $("#car_weight_max_ord").val());
            fd.append('car_weight_min', $("#car_weight_min_ord").val());
            fd.append('ext_brakes', $("#ext_brakes_ord").val());
            fd.append('ext_fuel', $("#ext_fuel_ord").val());
            fd.append('ext_mileage', $("#ext_mileage_ord").val());
            fd.append('ext_tire_brand', $("#ext_tire_brand_ord").val());
            fd.append('ext_dk', $("#ext_dk_ord").val());
            fd.append('ext_spec', $("#ext_spec_ord").val());
            if($('#ext_gas_ord').is(':checked')) fd.append('ext_gas', '1'); else fd.append('ext_gas', '0');
            fd.append('ext_gas_doc', $("#ext_gas_doc_ord").val());
            fd.append('ext_gas_date', $("#ext_gas_date_ord").val());
            fd.append('ext_gb_mnf', $("#ext_gb_mnf_ord").val());
            fd.append('ext_gb_doc_s', $("#ext_gb_doc_s_doc").val());
            fd.append('ext_gb_date_last', $("#ext_gb_date_last_ord").val());
            fd.append('ext_gb_date', $("#ext_gb_date_ord").val());
            
            fd.append('tah_brand', $("#tah_ord_brand").val());
            fd.append('tah_model', $("#tah_ord_model").val());
            fd.append('tah_n', $("#tah_ord_number").val());
            
            fd.append('pic_front', $("#pic_front_ord")[0].files[0]); 
            fd.append('pic_back', $("#pic_back_ord")[0].files[0]); 
            fd.append('select_pto', $("#select_pto_ord").val());
            fd.append('id_pto', $('#select_pto_ord option:selected').data("id-pto"));
            fd.append('select_pto_value',  $('#select_pto_ord option:selected').data("price"));
            fd.append('note', $("#note_ord").val());
            fd.append('fstphoto', '0');
            fd.append('bstphoto', '0');
            if(!$(".toggle_mini_photo:first-child").hasClass("d-none")){
                if(vs == "submit" && $("#sts_form_submit").val() == "edit"){
                    fd.append('fstphoto', '1');
                }
            }
            if(!$(".toggle_mini_photo:last-child").hasClass("d-none")){
                if(vs == "submit" && $("#sts_form_submit").val() == "edit"){
                    fd.append('bstphoto', '1');
                }
            }
            if(!desabled){
                desabled = true;
                $.ajax ({
                    url: 'content/orders/php/submit_add_order.php',
                    type: 'POST',
                    data: fd,
                    dataType: 'html',
                    success: function (data){
                        //console.log(data);
                        $("#info_form_ord span").addClass('d-none');
                        $(".inp_ord_valid").removeClass("border border-danger");
            		    if(data != "200"){
            		        $("."+data).removeClass('d-none');
            		        $(".inp_"+data).addClass("border border-danger");
            		    }else{
            		        $('#select_ctg_modal_ord option[value="1"]').prop('selected', true);
            		        $('#doc_ord option[value="СРТС"]').prop('selected', true);
            		        $('#ext_brakes_ord option[value="1"]').prop('selected', true);
            		        $('#ext_fuel_ord option[value="1"]').prop('selected', true);
            		        $('#ext_spec_ord option[value="0"]').prop('selected', true);
            		        $(".np_ord_valid").val("");
            		        
            		        if(vs == "draft") $("#draft_add_order").html("Успешно");
            		        if(vs == "submit") $("#submit_add_order").html("Успешно");
            		        setTimeout(function() {
                                $('#modalAddOrder').modal('toggle');
                            }, 1000);
                            setTimeout(function() {
                                if(vs == "draft") $("#draft_add_order").html("Сохранить черновик");
            		            if(vs == "submit") $("#submit_add_order").html("Отправить в работу <span style='padding-left: 4px;'>&rarr;</span>");
                            }, 1300);
                            
            		    }
            		    uploadOrders();
                        desabled = false;
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            }
        }
       
   });
   $(document).on('change','#status_order_select', function(){
        if(this.value == "Готово") $(".load_files_box_ord").removeClass('d-none');
        else $(".load_files_box_ord").addClass('d-none');
    });
    $(document).on('change','#select_cnt_ord', function(){
        vcnt = $(this).val();
        $.ajax({
    		url: 'content/orders/php/submit_change_cnt.php',
    		type: 'POST',
    		cache: false,
    		data: {'vcnt':vcnt},
    		dataType: 'html',
    		success: function (data){
    		    uploadOrders();
                setTimeout(function() {
                    countRows();
                },500);
    		 }
    	});
    	
    });
   $("#ord_search").on('input', function() {
        let text = $(this).val().toLowerCase();
        $(".orders_tr_row").find(".search_text_input").each(function() {
            const $this = $(this);
            if($this.data('seacrh-text').toLowerCase().indexOf(text) === -1) {
                $(this).parent().hide();
            } else {
                $(this).parent().show();
            }
            if($(".orders_tr_row:visible").length === 0){
                $(".mail_modal_nullsearch").removeClass('d-none');
            }else{
                $(".mail_modal_nullsearch").addClass('d-none');
            }
        });
    });
   
   $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
       // startD=picker.startDate.format('YYYY-MM-DD');
        //endD=picker.endDate.format('YYYY-MM-DD'); 
        uploadOrders();
        setTimeout(function() {
            countRows();
        },500);
    });
    $(document).on('change','#select_ctg_ord', function(){
        uploadOrders();
        setTimeout(function() {
            countRows();
        },500);
    });
    $(document).on('change','#select_agt_ord', function(){
        uploadOrders();
        setTimeout(function() {
            countRows();
        },500);
    });
    $(document).on('change','#select_sts_ord', function(){
        uploadOrders();
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
            uploadOrders();
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
                uploadOrders();
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
                uploadOrders();
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