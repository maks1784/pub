<?php
require "php/db.php";
$name_pto = htmlspecialchars($_POST['nto']);
$price_cat_a = htmlspecialchars($_POST['toa']);
$price_cat_b = htmlspecialchars($_POST['tob']);
$price_cat_c = htmlspecialchars($_POST['toc']);
$price_cat_e = htmlspecialchars($_POST['toe']);
$pto_id_edit = htmlspecialchars($_POST['toi']);


if(strlen($name_pto) == 0) exit('name_error_01');

if(strlen($price_cat_a) == 0 and strlen($price_cat_b) == 0 and strlen($price_cat_c) == 0 and strlen($price_cat_e) == 0) exit('val_error_01');

if(strlen($price_cat_a) != 0){
    if(is_numeric($price_cat_a) == false) exit('vala_error_01');
}
if(strlen($price_cat_b) != 0){
    if(is_numeric($price_cat_b) == false) exit('valb_error_01');
}

if(strlen($price_cat_c) != 0){
    if(is_numeric($price_cat_c) == false) exit('valc_error_01');
}

if(strlen($price_cat_e) != 0){
    if(is_numeric($price_cat_e) == false) exit('vale_error_01');
}



if($pto_id_edit == ""){
    //Значит нажата кнопка добавления ПТО
    $pto = R::dispense('pto');   
}else{
    //Значит нажата кнопка редактирования ПТО
    $pto = R::load('pto',$pto_id_edit); 
    $new_ext_a = $price_cat_a - $pto->price_a;
    $new_ext_b = $price_cat_b - $pto->price_b;
    $new_ext_c = $price_cat_c - $pto->price_c;
    $new_ext_e = $price_cat_e - $pto->price_e;
    
    $act_lbl = false;
    $act_a_lbl='';
    $act_b_lbl='';
    $act_c_lbl='';
    $act_e_lbl='';
    if($price_cat_a != $pto->price_a) $act_a_lbl = "A = ".$pto->price_a." → ".$price_cat_a;
    if($price_cat_b != $pto->price_b) $act_b_lbl = "B = ".$pto->price_b." → ".$price_cat_b;
    if($price_cat_c != $pto->price_c) $act_c_lbl = "C = ".$pto->price_c." → ".$price_cat_c;
    if($price_cat_e != $pto->price_e) $act_e_lbl = "E = ".$pto->price_e." → ".$price_cat_e;
    
    if($act_a_lbl != '' and $act_b_lbl != '' and $act_c_lbl != '' and $act_e_lbl != '') $act_lbl="Изменение цен: ".$act_a_lbl.", ".$act_b_lbl.", ".$act_c_lbl.", ".$act_e_lbl;
    if($act_a_lbl == '' and $act_b_lbl == '' and $act_c_lbl == '' and $act_e_lbl == '') $act_lbl=false;
    if($act_a_lbl != '' and $act_b_lbl == '' and $act_c_lbl == '' and $act_e_lbl == '') $act_lbl="Изменение цен: ".$act_a_lbl;
    if($act_a_lbl == '' and $act_b_lbl != '' and $act_c_lbl == '' and $act_e_lbl == '') $act_lbl="Изменение цен: ".$act_b_lbl;
    if($act_a_lbl == '' and $act_b_lbl == '' and $act_c_lbl != '' and $act_e_lbl == '') $act_lbl="Изменение цен: ".$act_c_lbl;
    if($act_a_lbl == '' and $act_b_lbl == '' and $act_c_lbl == '' and $act_e_lbl != '') $act_lbl="Изменение цен: ".$act_e_lbl;
    if($act_a_lbl != '' and $act_b_lbl != '' and $act_c_lbl == '' and $act_e_lbl == '') $act_lbl="Изменение цен: ".$act_a_lbl.", ".$act_b_lbl;
    if($act_a_lbl == '' and $act_b_lbl != '' and $act_c_lbl != '' and $act_e_lbl == '') $act_lbl="Изменение цен: ".$act_b_lbl.", ".$act_c_lbl;
    if($act_a_lbl == '' and $act_b_lbl == '' and $act_c_lbl != '' and $act_e_lbl != '') $act_lbl="Изменение цен: ".$act_c_lbl.", ".$act_e_lbl;
    if($act_a_lbl != '' and $act_b_lbl == '' and $act_c_lbl != '' and $act_e_lbl == '') $act_lbl="Изменение цен: ".$act_a_lbl.", ".$act_b_lbl;
    if($act_a_lbl != '' and $act_b_lbl == '' and $act_c_lbl == '' and $act_e_lbl != '') $act_lbl="Изменение цен: ".$act_a_lbl.", ".$act_e_lbl;
    if($act_a_lbl == '' and $act_b_lbl != '' and $act_c_lbl == '' and $act_e_lbl != '') $act_lbl="Изменение цен: ".$act_b_lbl.", ".$act_e_lbl;
    if($act_a_lbl != '' and $act_b_lbl != '' and $act_c_lbl != '' and $act_e_lbl == '') $act_lbl="Изменение цен: ".$act_a_lbl.", ".$act_b_lbl.", ".$act_c_lbl;
    if($act_a_lbl == '' and $act_b_lbl != '' and $act_c_lbl != '' and $act_e_lbl != '') $act_lbl="Изменение цен: ".$act_b_lbl.", ".$act_c_lbl.", ".$act_e_lbl;
    if($act_a_lbl != '' and $act_b_lbl == '' and $act_c_lbl != '' and $act_e_lbl != '') $act_lbl="Изменение цен: ".$act_a_lbl.", ".$act_c_lbl.", ".$act_e_lbl;
    if($act_a_lbl != '' and $act_b_lbl != '' and $act_c_lbl == '' and $act_e_lbl != '') $act_lbl="Изменение цен: ".$act_a_lbl.", ".$act_b_lbl.", ".$act_e_lbl;
    
    if($act_lbl){
        $log = R::dispense('log');
        $log->date_log=date("Y-m-d H:i:s",strtotime("now"));
        $log->from_users_id=$id;
        $log->to_users_id=$id;
        $log->from_users_login=$lgn;
        $log->to_users_login=$lgn;
        $log->action=$act_lbl;
        R::store($log); 
    }
}
$pto->name = $name_pto;
$pto->price_a = $price_cat_a;
$pto->price_b = $price_cat_b;
$pto->price_c = $price_cat_c;
$pto->price_e = $price_cat_e;
$pto_id=R::store($pto);

$total_agents = agents();
$total_ptous = ptous_all();
if($pto_id_edit == ""){
    //Значит нажата кнопка добавления ПТО
    if($total_agents){
        foreach($total_agents as $row){
            $ext_min = array();
            foreach($total_ptous as $ptou){
                if($ptou['users_id'] == $row['id']){
                    array_push($ext_min, $ptou['ext_min']); 
                }
            }
            $ptous = R::dispense('ptous');
            $ptous->users_id = $row['id'];
            $ptous->pto_id = $pto_id;
            if((int)$price_cat_a != 0)$ptous->price_a = $price_cat_a+min($ext_min);
            if((int)$price_cat_b != 0)$ptous->price_b = $price_cat_b+min($ext_min);
            if((int)$price_cat_c != 0)$ptous->price_c = $price_cat_c+min($ext_min);
            if((int)$price_cat_e != 0)$ptous->price_e = $price_cat_e+min($ext_min);
            $ptous->ext_min = min($ext_min);
            R::store($ptous);
            
        }
    }
}else{
    //Значит нажата кнопка редактирования ПТО
    
    foreach($total_ptous as $row){
        if($row['pto_id'] == $pto_id_edit){
            $ptous = R::load('ptous',$row['id']);
            
            if($ptous->price_a == '0'){//Значит сейчас в ptous цена равна 0
                //...и нам нужно добаввить минимальную наценку
                if((int)$price_cat_a != 0)$ptous->price_a = $price_cat_a + $row['ext_min'];//Значит цена ПТО исправлена с 0 на 100 (например)
                else $ptous->price_a = 0;
            }else{
                //Значит сейчас в ptous цена больше 0
                //.. и нужно прибавить к ней разницу
                if((int)$price_cat_a != 0)$ptous->price_a = $ptous->price_a + $new_ext_a;
                else $ptous->price_a = 0;
            }
            
            if($ptous->price_b == '0'){//Значит сейчас в ptous цена равна 0
                //...и нам нужно добаввить минимальную наценку
                if((int)$price_cat_b != 0)$ptous->price_b = $price_cat_b + $row['ext_min'];//Значит цена ПТО исправлена с 0 на 100 (например)
                else $ptous->price_b = 0;
            }else{
                //Значит сейчас в ptous цена больше 0
                //.. и нужно прибавить к ней разницу
                if((int)$price_cat_b != 0)$ptous->price_b = $ptous->price_b + $new_ext_b;
                else $ptous->price_b= 0;
            }
            
            if($ptous->price_c == '0'){//Значит сейчас в ptous цена равна 0
                //...и нам нужно добаввить минимальную наценку
                if((int)$price_cat_c != 0)$ptous->price_c = $price_cat_c + $row['ext_min'];//Значит цена ПТО исправлена с 0 на 100 (например)
                else $ptous->price_c = 0;
            }else{
                //Значит сейчас в ptous цена больше 0
                //.. и нужно прибавить к ней разницу
                if((int)$price_cat_c != 0)$ptous->price_c = $ptous->price_c + $new_ext_c;
                else $ptous->price_c= 0;
            }
            
            if($ptous->price_e == '0'){//Значит сейчас в ptous цена равна 0
                //...и нам нужно добаввить минимальную наценку
                if((int)$price_cat_e != 0)$ptous->price_e = $price_cat_e + $row['ext_min'];//Значит цена ПТО исправлена с 0 на 100 (например)
                else $ptous->price_e = 0;
            }else{
                //Значит сейчас в ptous цена больше 0
                //.. и нужно прибавить к ней разницу
                if((int)$price_cat_e != 0)$ptous->price_e = $ptous->price_e + $new_ext_e;
                else $ptous->price_e= 0;
            }
            
            R::store($ptous);
        }
    }
    //Перевести все заявки WHERE status = 'Принята' AND id_pto = :id_pto",[':id_pto'=>$pto_id_edit] в статус Черновик
    $orders = R::getAll("SELECT * FROM orders WHERE status = 'Принята' AND id_pto = :id_pto",[':id_pto'=>$pto_id_edit]);
    foreach($orders as $ord){
        //echo (int)$price_cat_a."||".(int)$price_cat_b."||".(int)$price_cat_c."||".(int)$price_cat_e."||".$ord['pto_id']."==". $pto_id_edit."_price_a";
        if((int)$price_cat_a == 0){
            if($ord['pto_id'] == $pto_id_edit."-price_a"){
                $ord = R::load('orders',$ord['id']);
                $ord->status = 'Черновик';
                R::store($ord);
            }
        }
        if((int)$price_cat_b == 0){
            if($ord['pto_id'] == $pto_id_edit."-price_b"){
                $ord = R::load('orders',$ord['id']);
                $ord->status = 'Черновик';
                R::store($ord);
            }
        }
        if((int)$price_cat_c == 0){
            if($ord['pto_id'] == $pto_id_edit."-price_c"){
                $ord = R::load('orders',$ord['id']);
                $ord->status = 'Черновик';
                R::store($ord);
            }
        }
        if((int)$price_cat_e == 0){
            if($ord['pto_id'] == $pto_id_edit."-price_e"){
                $ord = R::load('orders',$ord['id']);
                $ord->status = 'Черновик';
                R::store($ord);
            }
        }
        
    }
    
}
exit("200");
?>