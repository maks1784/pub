<?php
require "php/db.php";
$sm = htmlspecialchars($_POST['sm']);
$ldl = "Создание новой заявки";
$ord_id='';
$ctg_id='';
$doc='';
$sel_srts='';
$sel_pts='';
$sel_epts='';
$doc_s='';
$doc_n='';
$doc_from='';
$doc_date='';
$doc_owner='';
$doc_owner_chb='';
$car_gosn='';
$car_brand='';
$car_model='';
$car_vin='';
$car_vin_body='';
$car_vin_frame='';
$car_year='';
$car_weight_max='';
$car_weight_min='';
$ext_brakes='';
$sel_br_gid='';
$sel_br_pnev='';
$sel_br_comb='';
$sel_br_mech='';
$sel_br_none='';
$ext_fuel='';
$sel_fuel_pet='';
$sel_fuel_diz='';
$sel_fuel_lgas='';
$sel_fuel_gas='';
$ext_mileage='';
$ext_tire_brand='';
$ext_dk='';
$ext_spec='';
$sel_spec_none='';
$sel_spec_taxi='';
$sel_spec_stu='';
$sel_spec_dan='';
$sel_spec_sig='';
$ext_gas='';
$chb_ext_gas='';
$ext_gas_doc='';
$ext_gas_date='';
$ext_gb_mnf='';
$ext_gb_doc_s='';
$ext_gb_date_last='';
$ext_gb_date='';
$tah_brand='';
$tah_model='';
$tah_n='';
$name_ph_front='';
$name_ph_back='';
$name_nph_front='';
$name_nph_back='';
$d_none_pf='d-none';
$d_none_pb='d-none';
$select_pto='';
$note='';
$sel_ph_bg='';
$sel_ph_gn='';
$sel_ph_none='';
$look_dis='';
if($sm == 'edit') $ldl = "Изменение заявки";
if($sm == 'look'){ $ldl = "Просмотр заявки"; $look_dis="disabled";}
if($sm == 'edit' or $sm == 'look'){
    
    $ord_id = htmlspecialchars($_POST['oi']);
    $order = R::load('orders',$ord_id);
    
    $or_us_id=$order->users_id;
    /*
    $order->draft=$sub;
    $order->date_added=date("Y-m-d H:i:s",strtotime("now"));
    //$order->date_exe Только после проведения от модератора
    $order->status=$status_ord;*/
    
    $ctg_id=$order->ctg_id;
    $doc=$order->doc;
    if($doc == "СРТС"){ $sel_srts='selected';}
    if($doc == "ПТС"){ $sel_pts='selected';}
    if($doc == "ЭПТС"){ $sel_epts='selected';}
    $doc_s=$order->doc_s;
    $doc_n=$order->doc_n;
    $doc_from=$order->doc_from;
    $doc_date=date("d.m.Y",strtotime($order->doc_date));
    $doc_owner=$order->doc_owner;
    if($doc_owner == "1") $doc_owner_chb = "checked";
    $car_gosn=$order->car_gosn;
    $car_brand=$order->car_brand;
    $car_model=$order->car_model;
    $car_vin=$order->car_vin;
    $car_vin_body=$order->car_vin_body;
    $car_vin_frame=$order->car_vin_frame;
    $car_year=date("Y",strtotime($order->car_year));
    $car_weight_max=$order->car_weight_max;
    $car_weight_min=$order->car_weight_min;
    
    $ext_brakes=$order->ext_brakes;
    if($ext_brakes == "1"){ $sel_br_gid='selected';}
    if($ext_brakes == "2"){ $sel_br_pnev='selected';}
    if($ext_brakes == "3"){ $sel_br_comb='selected';}
    if($ext_brakes == "4"){ $sel_br_mech='selected';}
    if($ext_brakes == "5"){ $sel_br_none='selected';}

    $ext_fuel=$order->ext_fuel;
    if($ext_fuel == "1"){ $sel_fuel_pet='selected';}
    if($ext_fuel == "2"){ $sel_fuel_diz='selected';}
    if($ext_fuel == "3"){ $sel_fuel_lgas='selected';}
    if($ext_fuel == "4"){ $sel_fuel_gas='selected';}
    
    $ext_mileage=$order->ext_mileage;
    $ext_tire_brand=$order->ext_tire_brand;
    $ext_dk=$order->ext_dk;
    $ext_spec=$order->ext_spec;
    if($ext_spec == "0"){ $sel_spec_none='selected';}
    if($ext_spec == "1"){ $sel_spec_taxi='selected';}
    if($ext_spec == "2"){ $sel_spec_stu='selected';}
    if($ext_spec == "3"){ $sel_spec_dan='selected';}
    if($ext_spec == "4"){ $sel_spec_sig='selected';}
    
    $ext_gas=$order->ext_gas;
    if($ext_gas == "1") $chb_ext_gas = "checked";
    if($ext_gas == "1"){
        $ext_gas_doc=$order->ext_gas_doc;
        $ext_gas_date=date("d.m.Y",strtotime($order->ext_gas_date));
        $ext_gb_mnf=$order->ext_gb_mnf;
        $ext_gb_doc_s=$order->ext_gb_doc_s;
        $ext_gb_date_last=date("d.m.Y",strtotime($order->ext_gb_date_last));
        $ext_gb_date=date("d.m.Y",strtotime($order->ext_gb_date));
    }
    $tah_brand=$order->tah_brand;
    $tah_model=$order->tah_model;
    $tah_n=$order->tah_n;
    //$name=$order->$input_name;
    
    $select_pto=$order->pto_id;
    $val_rub=$order->value;
    $note=$order->note;
    if($order->pic_front!=''){
            $d_none_pf='';
            $name_ph_front="style='background-image: url(\"content/orders/php/files/".$order->pic_front."\")'";
        
    }
    if($order->pic_back!=''){
            $d_none_pb='';
            $name_ph_back="style='background-image: url(\"content/orders/php/files/".$order->pic_back."\")'";
        
    }
    if($order->status=='Готово'){
        $name_nph_front="style='background-image: url(\"content/orders/php/files/".$order->new_pic_front."\")'";
        $name_nph_back="style='background-image: url(\"content/orders/php/files/".$order->new_pic_back."\")'";
    }
   // $name_ph_front="style='background-image: url(\"content/orders/php/files/".$order->pic_front."\")'";
    //$name_ph_back="style='background-image: url(\"content/orders/php/files/".$order->pic_back."\")'";
    if($note == "1"){$sel_ph_bg='selected';}
    if($note == "2"){$sel_ph_gn='selected';}
    if($note == "3"){$sel_ph_none='selected';}

}
$total_ctg = ctg();
$total_pto = pto_not_del();
$total_users_ptous = ptous($id);
?>    
      <div class="modal-header mb-2 bg-dark text-light">
          <input type="hidden" id="uid_modal_ord" value="<?php echo $or_us_id?>">
        <input type="hidden" id="oid_modal_ord" value="<?php echo $ord_id?>">
        <input type="hidden" id="sm_modal_ord" value="<?php echo $sm?>">
        <h5 class="modal-title" id="modalAddOrderLabel"><?php echo $ldl;?></h5>
        <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Закрыть"></button>
      </div>
      <div class="modal-body">
          
    
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-12">
                    <div class="form-floating">
                        <select class="form-select bg-light" id="select_ctg_modal_ord" <?php echo $look_dis;?>>
                            <?php
                            foreach($total_ctg as $ctg){
                                $selected_ctg="";
                                if($sm == 'add'){
                                    if($ctg["ctm"] == "M1") $selected_ctg = "selected";
                                }
                                if($ctg_id == $ctg['id']){
                                    $selected_ctg = "selected";
                                }
                            ?>
                            <option <?php echo $selected_ctg;?> value="<?php echo $ctg["id"]?>" data-ctg="<?php echo $ctg["ctg"]?>" data-ctm="<?php echo $ctg["ctm"]?>"><?php echo $ctg["name"]?></option>
                            <?php
                            }
                            ?> 
                            
                        </select>
                        <label for="select_ctg_modal_ord">Категория ТС</label>
                    </div>
                </div>
            </div>
            
            <h4 class="mb-4">Предоставляемый документ</h4>
        
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <div class="form-floating">
                        <select class="form-select bg-light" id="doc_ord" <?php echo $look_dis;?>>
                            <option <?php echo $sel_srts?> value="СРТС">СРТС</option>
                            <option <?php echo $sel_pts?> value="ПТС">ПТС</option>
                            <option <?php echo $sel_epts?> value="ЭПТС">ЭПТС</option>
                        </select>
                        <label for="doc_ord">Документ</label>
                    </div>
                </div>
                <div class="ser_doc_toggle col-md-3">
                    <div class="form-floating">
                        <input type="text" <?php echo $look_dis;?> class="inp_ord_valid form-control inp_doc_s_error_01" id="doc_s_ord" placeholder="Серия" value="<?php echo $doc_s;?>">
                        <label for="doc_s_ord">Серия</label>
                    </div>
                </div>
                <div class="num_doc_toggle col-md-6">
                    <div class="form-floating">
                        <input type="text" <?php echo $look_dis;?> class="inp_ord_valid form-control inp_doc_n_error_01" id="doc_n_ord" placeholder="Номер" value="<?php echo $doc_n;?>">
                        <label for="doc_n_ord">Номер</label>
                    </div>
                </div>
            </div>
            
            <div class="row g-3 mb-5">
                <div class="col-md-9">
                    <div class="form-floating">
                        <input type="text" <?php echo $look_dis;?> class="inp_ord_valid form-control inp_doc_from_error_01" id="doc_from_ord" placeholder="Кем выдан" value="<?php echo $doc_from;?>">
                        <label for="doc_from_ord">Кем выдан</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating">
                        <input type="text" <?php echo $look_dis;?> class="form-control" id="doc_date_ord" placeholder="Дата выдачи" value="<?php echo $doc_date;?>">
                        <label for="doc_date_ord">Дата выдачи</label>
                    </div>
                </div>
                
                <div class="col">
                        <input class="form-check-input" <?php echo $look_dis;?> type="checkbox" value="" id="doc_owner_ord" <?php echo $doc_owner_chb;?>>
                        <label class="form-check-label" for="doc_owner_ord">Собственник иностранный гражданин</label>
                </div>
            </div>
            
            
            <h4 class="mb-4">Информация о транспортном средстве</h4>
        
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <div class="form-floating">
                        <input type="text" <?php echo $look_dis;?> class="inp_ord_valid form-control inp_car_gosn_error_01" id="car_gosn_ord" placeholder="Гос. номер" value="<?php echo $car_gosn;?>">
                        <label for="car_gosn_ord">Гос. номер</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input type="text" <?php echo $look_dis;?> class="inp_ord_valid form-control inp_car_brand_error_01" id="car_brand_ord" placeholder="Марка" value="<?php echo $car_brand;?>">
                        <label for="car_brand_ord">Марка</label>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-floating">
                        <input type="text" <?php echo $look_dis;?> class="inp_ord_valid form-control inp_car_model_error_01" id="car_model_ord" placeholder="Модель" value="<?php echo $car_model;?>">
                        <label for="car_model_ord">Модель</label>
                    </div>
                </div>
            </div>
            
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <div class="form-floating">
                        <input type="text" <?php echo $look_dis;?> class="inp_ord_valid form-control inp_car_vin_error_01" id="car_vin_ord" placeholder="VIN" value="<?php echo $car_vin;?>">
                        <label for="car_vin_ord">VIN</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating input-group">
                        <input type="text" <?php echo $look_dis;?> class="inp_ord_valid form-control inp_car_vin_body_error_01" id="car_vin_body_ord" placeholder="Кузов" value="<?php echo $car_vin_body;?>">
                        <label for="car_vin_body_ord">Кузов</label>
                        <button <?php echo $look_dis;?> class="btn btn-outline-secondary vin_btn_inp_ord" type="button" id="vin_input_body">VIN</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating input-group">
                        <input type="text" <?php echo $look_dis;?> class="inp_ord_valid form-control inp_car_vin_frame_error_01" id="car_vin_frame_ord" placeholder="Рама / Шасси" value="<?php echo $car_vin_frame;?>">
                        <label for="car_vin_frame_ord">Рама / Шасси</label>
                        <button <?php echo $look_dis;?> class="btn btn-outline-secondary vin_btn_inp_ord" type="button" id="vin_input_frame">VIN</button>
                    </div>
                </div>
            </div>
            
            <div class="row g-3 mb-5">
                <div class="col-md-3">
                    <div class="form-floating">
                        <input type="text" <?php echo $look_dis;?> class="inp_ord_valid form-control inp_car_year_error_01" id="car_year_ord" placeholder="Год выпуска" value="<?php echo $car_year;?>">
                        <label for="car_year_ord">Год выпуска</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input type="text" <?php echo $look_dis;?> class="inp_ord_valid form-control inp_car_weight_max_error_01" id="car_weight_max_ord" placeholder="Максимальная масса" value="<?php echo $car_weight_max;?>">
                        <label for="car_weight_max_ord">Максимальная масса</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input type="text" <?php echo $look_dis;?> class="inp_ord_valid form-control inp_car_weight_min_error_01" id="car_weight_min_ord" placeholder="Масса без нагрузки" value="<?php echo $car_weight_min;?>">
                        <label for="car_weight_min_ord">Масса без нагрузки</label>
                    </div>
                </div>
            </div>
            
            <h4 class="mb-4">Дополнительно</h4>
            
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <div class="form-floating">
                        <select class="form-select bg-light" <?php echo $look_dis;?> id="ext_brakes_ord">
                            <option <?php echo $sel_br_gid?> value="1">Гидравлическая</option>
                            <option <?php echo $sel_br_pnev?> value="2">Пневматическая</option>
                            <option <?php echo $sel_br_comb?> value="3">Комбинированная</option>
                            <option <?php echo $sel_br_mech?> value="4">Механическая</option>
                            <option <?php echo $sel_br_none?> value="5">Отсутствует</option>
                        </select>
                        <label for="ext_brakes_ord">Тормозная система</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating">
                        <select class="form-select bg-light" <?php echo $look_dis;?> id="ext_fuel_ord">
                            <option <?php echo $sel_fuel_pet?> value="1">Бензин</option>
                            <option <?php echo $sel_fuel_diz?> value="2">Дизель</option>
                            <option <?php echo $sel_fuel_lgas?> value="3">Сжиженный газ</option>
                            <option <?php echo $sel_fuel_gas?> value="4">Сжатый газ</option>
                        </select>
                        <label for="ext_fuel_ord">Тип топлива</label>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="form-floating">
                        <input type="text" <?php echo $look_dis;?> class="inp_ord_valid form-control inp_ext_mileage_error_01" id="ext_mileage_ord" placeholder="Пробег" value="<?php echo $ext_mileage;?>">
                        <label for="ext_mileage_ord">Пробег</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-floating">
                        <input type="text" <?php echo $look_dis;?> class="inp_ord_valid form-control inp_ext_tire_brand_error_01" id="ext_tire_brand_ord" placeholder="Марка шин" value="<?php echo $ext_tire_brand;?>">
                        <label for="ext_tire_brand_ord">Марка шин</label>
                    </div>
                </div>
            </div>
            
            <div class="row g-3 mb-3">
                <div class="col-md-8">
                    <div class="form-floating">
                        <input type="text" <?php echo $look_dis;?> class="form-control" id="ext_dk_ord" placeholder="Примечание в ДК" value="<?php echo $ext_dk;?>">
                        <label for="ext_dk_ord">Примечание в ДК</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <select class="form-select bg-light" id="ext_spec_ord" <?php echo $look_dis;?>>
                        <option <?php echo $sel_spec_none?> value="0">Нет</option>
                        <option <?php echo $sel_spec_taxi?> value="1">Такси</option>
                        <option <?php echo $sel_spec_stu?> value="2">Учебный</option>
                        <option <?php echo $sel_spec_dan?> value="3">Опасный груз</option>
                        <option <?php echo $sel_spec_sig?> value="4">Спецсигналы</option>
                        </select>
                        <label for="ext_spec_ord">Особые отметки</label>
                    </div>
                </div>
            </div>
            
            <div class="row mb-5">
                <div class="col-12" id="gbo_box_ord">
                    <div class="form-check">
                        <input <?php echo $chb_ext_gas;?> <?php echo $look_dis;?> class="form-check-input" type="checkbox" value="" id="ext_gas_ord" data-bs-toggle="collapse" href="#GBO" role="button" aria-expanded="false" aria-controls="collapseExample">
                        <label class="form-check-label" for="ext_gas_ord">Установлено газобаллонное оборудование</label>
                    </div>
                    <div class="collapse mt-4" id="GBO">
                        <h6 class="mb-3">Оборудование</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="form-floating form-floating-sm">
                                    <input type="text" <?php echo $look_dis;?> class="inp_ord_valid form-control form-control-sm inp_ext_gas_doc_error_01" id="ext_gas_doc_ord" placeholder="Номер свидетельства об испытаниях" value="<?php echo $ext_gas_doc;?>">
                                    <label for="ext_gas_doc_ord">Номер свидетельства об испытаниях</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" <?php echo $look_dis;?> class="form-control" id="ext_gas_date_ord" placeholder="Дата очередного освидетельствования" value="<?php echo $ext_gas_date;?>">
                                    <label for="ext_gas_date_ord">Дата очередного освидетельствования</label>
                                </div>
                            </div>
                        </div>
                        <h6 class="mb-3">Баллон</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="text" <?php echo $look_dis;?> class="inp_ord_valid form-control inp_ext_gb_mnf_error_01" id="ext_gb_mnf_ord" placeholder="Год выпуска" value="<?php echo $ext_gb_mnf;?>">
                                    <label for="ext_gb_mnf_ord">Год выпуска</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="text" <?php echo $look_dis;?> class="inp_ord_valid form-control inp_ext_gb_doc_s_error_01" id="ext_gb_doc_s_doc" placeholder="Серийный номер" value="<?php echo $ext_gb_doc_s;?>">
                                    <label for="ext_gb_doc_s_doc">Серийный номер</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="text" <?php echo $look_dis;?> class="form-control" id="ext_gb_date_last_ord" placeholder="Послед. освид." value="<?php echo $ext_gb_date_last;?>">
                                    <label for="ext_gb_date_last_ord">Послед. освид.</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="text" <?php echo $look_dis;?> class="form-control" id="ext_gb_date_ord" placeholder="Очередн. освид." value="<?php echo $ext_gb_date;?>">
                                    <label for="ext_gb_date_ord">Очередн. освид.</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
           
            
            
            
            <div class="tahograf" style="display: none;">
                <h4 class="mb-4">Тахограф</h4>
                
                <div class="row g-3 mb-5">
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" <?php echo $look_dis;?> class="form-control" id="tah_ord_brand" placeholder="Марка" value="<?php echo $tah_brand;?>">
                            <label for="tah_ord_brand">Марка</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" <?php echo $look_dis;?> class="form-control" id="tah_ord_model" placeholder="Модель" value="<?php echo $tah_model;?>">
                            <label for="tah_ord_model">Модель</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" <?php echo $look_dis;?> class="form-control" id="tah_ord_number" placeholder="Серийный номер" value="<?php echo $tah_n;?>">
                            <label for="tah_ord_number">Серийный номер</label>
                        </div>
                    </div>
                </div>
            </div>
        
        
            
            
            
            <h4 class="mb-4">Фотографии ТС</h4>
            
            <div class="row g-8 mb-1">
                <div class="col-md-6 mb-3">
                    <div>
                        <label for="pic_front_ord" class="form-label h6">Вид спереди</label>
                        <?php
                        if($sm != 'look'){
                        ?>
                        <input <?php echo $look_dis;?> class="inp_ord_valid form-control form-control inp_pic_front_ord_error_01 inp_pic_front_ord_error_02 pic_front_ord_error_03" id="pic_front_ord" type="file" accept="image/png, image/gif, image/jpeg" />
                        <?php
                        }
                        ?>
                        
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div>
                        <label for="pic_back_ord" class="form-label h6">Вид сзади</label>
                        <?php
                        if($sm != 'look'){
                        ?>
                        <input  <?php echo $look_dis;?>class="inp_ord_valid form-control form-control inp_pic_back_ord_error_01 inp_pic_back_ord_error_02 pic_back_ord_error_03" id="pic_back_ord" type="file" accept="image/png, image/gif, image/jpeg" />
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="row g-8 mb-5">
                <div class="col-md-6 mb-3 toggle_mini_photo <?php echo $d_none_pf?>">
                    <div class="pic_front_ord bg-light" <?php echo $name_ph_front;?>>
                    </div>
                </div>
                <div class="col-md-6 mb-3 toggle_mini_photo <?php echo $d_none_pb?>" >
                    <div class="pic_back_ord bg-light" <?php echo $name_ph_back;?>>
                    </div>
                </div>
            </div>
            <?php
                if($sm == 'look'){
                    if($name_nph_front !='' and $name_nph_back !=''){
                ?>
                <div class="row g-8 mb-1">
                    <div class="col-md-6 mb-3">
                        <div>
                            <label for="pic_front_ord_ext" class="form-label h6">Вид спереди (мод.)</label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div>
                            <label for="pic_back_ord_ext" class="form-label h6">Вид сзади (мод.)</label>
                        </div>
                    </div>
                </div>
                <div class="row g-8 mb-5">
                    <div class="col-md-6 mb-3">
                        <div class="pic_front_ord_ext bg-light" <?php echo $name_nph_front;?>>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3" >
                        <div class="pic_back_ord_ext bg-light" <?php echo $name_nph_back;?>>
                        </div>
                    </div>
                </div>
                <?php
                    }
                }
            ?>
            <h4 class="mb-4">Выбор ПТО</h4>
            
            <div class="row g-4 mb-3">
                <div class="col">
                    <div class="form-floating">
                        <?php
                        if($sts != 'moderator'){
                        ?>
                        <select class="inp_ord_valid form-select bg-light inp_select_pto_error_01" id="select_pto_ord" <?php echo $look_dis;?>>
                            <?php/*
                            foreach($total_pto as $pto){
                                if($sm == 'add'){
                                    if($sts == "admin"){
                                        $pra=$pto['price_a'];
                                        $prb=$pto['price_b'];
                                        $prc=$pto['price_c'];
                                        $pre=$pto['price_e'];
                                    }else if($sts == "agent"){
                                        foreach($total_users_ptous as $ptou){
                                            if($ptou['pto_id'] == $pto['id']){
                                                $pra=$ptou['price_a'];
                                                $prb=$ptou['price_b'];
                                                $prc=$ptou['price_c'];
                                                $pre=$ptou['price_e'];
                                            }
                                        }
                                    }
                                }else if($sm == 'edit'){
                                    if($sts == "admin"){
                                        $pra=$pto['price_a'];
                                        $prb=$pto['price_b'];
                                        $prc=$pto['price_c'];
                                        $pre=$pto['price_e'];
                                    }else if($sts == "agent"){
                                        $total_users_ptous = ptous($or_us_id);
                                        foreach($total_users_ptous as $ptou){
                                            if($ptou['pto_id'] == $pto['id']){
                                                $pra=$ptou['price_a'];
                                                $prb=$ptou['price_b'];
                                                $prc=$ptou['price_c'];
                                                $pre=$ptou['price_e'];
                                            }
                                        }
                                    }
                                    
                                }else if($sm == 'look'){
                                    $pra=$val_rub;
                                    $prb=$val_rub;
                                    $prc=$val_rub;
                                    $pre=$val_rub;
                                }
                                if($sm == 'edit' or $sm == 'look'){
                                    if($select_pto == $pto['id']."-price_a") $sel_ptoa_row = "selected";else $sel_ptoa_row = '';
                            
                                    if($select_pto == $pto['id']."-price_b") $sel_ptob_row = "selected";else $sel_ptob_row = '';
                            
                                    if($select_pto == $pto['id']."-price_c") $sel_ptoc_row = "selected";else $sel_ptoc_row = '';
                            
                                    if($select_pto == $pto['id']."-price_e") $sel_ptoe_row = "selected";else $sel_ptoe_row = '';
                            
                                }
                                if($pra != "0"){
                                ?>
                                <option class="priceCtgA" data-id-pto="<?php echo $pto['id']?>" <?php echo $sel_ptoa_row;?> data-price="<?php echo $pra?>" value="<?php echo $pto['id']."-price_a";?>"><?php echo $pto['name']?> &middot; <?php echo $pra?> руб.</option>
                                <?php 
                                }if($prb != "0"){
                                ?>
                                <option class="priceCtgB" data-id-pto="<?php echo $pto['id']?>" <?php echo $sel_ptob_row;?> data-price="<?php echo $prb?>" value="<?php echo $pto['id']."-price_b";?>"><?php echo $pto['name']?> &middot; <?php echo $prb?> руб.</option>
                                <?php 
                                }if($prc != "0"){
                                ?>
                                <option class="priceCtgC" data-id-pto="<?php echo $pto['id']?>" <?php echo $sel_ptoc_row;?> data-price="<?php echo $prc?>" value="<?php echo $pto['id']."-price_c";?>"><?php echo $pto['name']?> &middot; <?php echo $prc?> руб.</option>
                                <?php 
                                }if($pre != "0"){
                                ?>
                                <option class="priceCtgE" data-id-pto="<?php echo $pto['id']?>" <?php echo $sel_ptoe_row;?> data-price="<?php echo $pre?>" value="<?php echo $pto['id']."-price_e";?>"><?php echo $pto['name']?> &middot; <?php echo $pre?> руб.</option>
                                <?php
                                }
                            }*/
                            ?>
                        </select>
                        <?php
                        }else{
                            ?>
                            <input type="text" disabled class="form-control" id="select_pto_ord" placeholder="ПТО" value="<?php echo pto_id($select_pto);?>">
                            <?php
                        }
                        ?>
                        <label for="select_pto_ord">ПТО</label>
                    </div>
        
                </div>
        
            </div>
        
            
            <div class="row g-4 mb-2">
                <div class="col-md-12">
                        <div class="form-floating">
                            <select class="form-select bg-light" id="note_ord" aria-label="Floating label select example" <?php echo $look_dis;?>>
                                <option <?php echo $sel_ph_bg?> value="1">Фотошоп фона</option>
                                <option <?php echo $sel_ph_gn?> value="2">Фотошоп госномера</option>
                                <option <?php echo $sel_ph_none?> value="3">Без фотошопа</option>
                            </select>
                          <label for="note_ord">Режим обработки фото</label>
                        </div>
                </div>
            </div>
            <div class="row g-4 my-2 text-center">
                <div class="col-md-12">
                    <p class="mx-3" id="info_form_ord">
                        <span class="text-danger d-none doc_s_error_01">Укажите серию документа!</span>
                        <span class="text-danger d-none doc_n_error_01">Укажите номер документа!</span>
                        <span class="text-danger d-none doc_from_error_01">Укажите кем выдан документ!</span>
                        
                        <span class="text-danger d-none car_gosn_error_01">Укажите гос. номер!</span>
                        <span class="text-danger d-none car_brand_error_01">Укажите марку!</span>
                        <span class="text-danger d-none car_model_error_01">Укажите модель!</span>
                        <span class="text-danger d-none car_vin_error_01">Укажите значение в поле "VIN"!</span>
                        <span class="text-danger d-none car_vin_body_error_01">Укажите значение в поле "Кузов"!</span>
                        <span class="text-danger d-none car_vin_frame_error_01">Укажите значение в поле "Рама / Шасси"!</span>
                        <span class="text-danger d-none car_year_error_01">Укажите год выпуска транспортного средства!</span>
                        <span class="text-danger d-none car_weight_max_error_01">Укажите максимальную массу!</span>
                        <span class="text-danger d-none car_weight_min_error_01">Укажите массу без нагрузки!</span>
                        
                        <span class="text-danger d-none ext_mileage_error_01">Укажите пробег!</span>
                        <span class="text-danger d-none ext_tire_brand_error_01">Укажите марку шин!</span>
                        
                        <span class="text-danger d-none ext_gas_doc_error_01">Укажите Номер сведетельства об испытаниях!</span>
                        <span class="text-danger d-none ext_gb_mnf_error_01">Укажите год выпуска газового оборудования!</span>
                        <span class="text-danger d-none ext_gb_doc_s_error_01">Укажите серийный номер газового оборудования!</span>
                        
                        <span class="text-danger d-none pic_front_ord_error_01">Файл "Вид спереди" не загружен!</span>
                        <span class="text-danger d-none pic_back_ord_error_01">Файл "Вид сзади" не загружен!</span>
                        <span class="text-danger d-none pic_front_ord_error_02">Ошибка формата или размера файла "Вид спереди"!</span>
                        <span class="text-danger d-none pic_back_ord_error_02">Ошибка формата или размера файла "Вид сзади"!</span>
                        <span class="text-danger d-none pic_front_ord_error_03">Ошибка загрузки файла "Вид спереди"! Попробуйте снова</span>
                        <span class="text-danger d-none pic_back_ord_error_03">Ошибка загрузки файла "Вид сзади"! Попробуйте снова</span>
                        <span class="text-danger d-none select_pto_error_01">Укажите ПТО!</span>
                        
                        <span class="text-danger d-none status_pto_error_01">Статус заявки переведён!</span>
                    </p>
                </div>
            </div>
        
        </div> 
      </div>
        
      <div class="modal-footer bg-light">
          
        <button type="button" class="btn btn-md text-danger" data-bs-dismiss="modal">Отменить</button>
        <?php 
        if($sm != 'look'){
        ?>
        <button type="button" class="btn btn-md text-secondary" id="draft_add_order" value="draft">Сохранить черновик</button>
        <button type="button" class="btn btn-lg btn-success" id="submit_add_order" value="submit">Отправить в работу <span style="padding-left: 4px;">&rarr;</span></button>
        <input type="hidden" value="" id="sts_form_submit">
        <input type="hidden" id="id_form_submit" value="<?php echo $ord_id;?>">
        <?php
        }
        ?>
      </div>
      <script>
        
            $('#doc_date_ord,#ext_gb_date_last_ord').daterangepicker({
                singleDatePicker: true,
                minYear: 1901,
                "autoApply": true,
                locale: {
                    format: 'DD.MM.YYYY'
                },
                maxYear: parseInt(moment().format('YYYY'),10)
            }, function(start, end, label) {
                var years = moment().diff(start, 'years');
            });
            
            $('#ext_gas_date_ord,#ext_gb_date_ord').daterangepicker({
                singleDatePicker: true,
                "autoApply": true,
                minYear: 1901,
                locale: {
                    format: 'DD.MM.YYYY'
                }
            });
        <?php
        if($sm != 'look'){
        ?>
            document.querySelector("#pic_front_ord").addEventListener("change", function () {
                if (this.files[0]) {
                    var fr = new FileReader();
                    fr.addEventListener("load", function () {
                      document.querySelector(".pic_front_ord").style.backgroundImage = "url(" + fr.result + ")";
                      $(".toggle_mini_photo").removeClass("d-none");
                    }, false);
                    fr.readAsDataURL(this.files[0]);
                }
            });
            document.querySelector("#pic_back_ord").addEventListener("change", function () {
                if (this.files[0]) {
                    var fr = new FileReader();
                    fr.addEventListener("load", function () {
                      document.querySelector(".pic_back_ord").style.backgroundImage = "url(" + fr.result + ")";
                      $(".toggle_mini_photo").removeClass("d-none");
                    }, false);
                    fr.readAsDataURL(this.files[0]);
                }
            });

        <?php
        }
        ?>
      </script>