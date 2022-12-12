<?php
//ini_set('display_errors',1); error_reporting(E_ALL ^E_NOTICE);
require "php/db.php";
$order_id=htmlspecialchars($_POST['oid']);
$status_ord=htmlspecialchars($_POST['ost']);
$td_date=htmlspecialchars($_POST['td_date']);


$order = R::load('orders',$order_id);
if($order->statu == 'Готово') exit();
$name_car = $order->car_brand."_".$order->car_model."_".$order->car_gosn."_".$order->car_vin;
$pto_id_ord = $order->pto_id;
$order->status=$status_ord;
$order->mod_id=$id;

if($status_ord == "Черновик"){
    $order->exp_id=$order->users_id;
}

if($status_ord == "Готово"){
    //Сначала надопроверит загружены ли все 3 файла
    if(!isset($_FILES["pdf_file"]))exit('npdf_ord_error_01');
    //После загрузки первого файла проверяем его формат
    if(isset($_FILES["pdf_file"]))if(!isSecurityPdf($_FILES["pdf_file"])) exit('npdf_ord_error_02');
    
        //Сначала надопроверит загружены ли все 3 файла
    if(!isset($_FILES["new_pic_front"]))exit('npic_front_ord_error_01');
    //После загрузки первого файла проверяем его формат
    if(isset($_FILES["new_pic_front"]))if(!isSecurity($_FILES["new_pic_front"])) exit('npic_front_ord_error_02');
    
    //Сначала надопроверит загружены ли все 3 файла
    if(!isset($_FILES["new_pic_back"]))exit('npic_back_ord_error_01');
    //После загрузки первого файла проверяем его формат
    if(isset($_FILES["new_pic_back"]))if(!isSecurity($_FILES["new_pic_back"])) exit('npic_back_ord_error_02');
    
    $files_inputs=array(
        array(
            'name'=>'pdf_file', 
            'lbl'=>'pdf', 
            'ind'=>''
        ),array(
            'name'=>'new_pic_front', 
            'lbl'=>'front', 
            'ind'=>'F'
        ),
        array(
            'name'=>'new_pic_back', 
            'lbl'=>'back', 
            'ind'=>'B'
        )
    );
    $path = 'content/orders/php/files/';
    foreach($files_inputs as $row){
        $input_name = $row["name"];
        //$file_name = $row["name"]."name";
        $input_lbl = $row["lbl"];
        if(isset($_FILES[$input_name])) {
        	//Есть новый файл;
        	$file = $_FILES[$input_name];
        	$ext = pathinfo($_FILES[$input_name]['name'], PATHINFO_EXTENSION);
        	// Проверим на ошибки загрузки.
        	if (!empty($file['error']) || empty($file['tmp_name'])) {
        		exit("pic_".$input_lbl."_ord_error_03");
        	} elseif ($file['tmp_name'] == 'none' || !is_uploaded_file($file['tmp_name'])) {
        		exit("pic_".$input_lbl."_ord_error_03");
        	} else {
        		// Оставляем в имени файла только буквы, цифры и некоторые символы.
        		$pattern = "[^a-zа-яё0-9,~!@#%^-_\$\?\(\)\{\}\[\]\.]";
        		$name = date('Y-m-d',strtotime("now"))."_".uniqid().".".$ext;
        		if($input_name == 'pdf_file'){
        		    $name = $name_car.".".$ext;
        		}else{
        		    $name = "NEW_".$row["ind"]."_".$name_car.".".$ext;
        		}
        		$parts = pathinfo($name);
        		if (empty($name) || empty($parts['extension'])) {
        		    exit("pic_".$input_lbl."_ord_error_03");
        		} elseif (!empty($allow) && !in_array(strtolower($parts['extension']), $allow)) {
        			exit("pic_".$input_lbl."_ord_error_03");
        		} elseif (!empty($deny) && in_array(strtolower($parts['extension']), $deny)) {
        			exit("pic_".$input_lbl."_ord_error_03");
        		} else {
        		    //exit($path . $name);
        			// Перемещаем файл в директорию.
        			if (move_uploaded_file($file['tmp_name'], $path . $name)) {
        				// Далее можно сохранить название файла в БД и т.п.
        				if($input_name == 'pdf_file'){
        				    $order->pdf_name = $_FILES[$input_name]['name'];
        				}
        				$order->$input_name = $name;
        				
        			} else {
        			    exit("pic_".$input_lbl."_ord_error_03");
        			}
        			
        		}
        	}
        }    
    }
    $order->date_exe=date("Y-m-d H:i:s",strtotime("now"));
    
    
    $value=$order->value;
    $uid=$order->users_id;
    $mmn=$order->car_brand." ".$order->car_model." ".$order->car_gosn;
    
    //Отнимаем баланс у Агента, чья заявка
    $users = R::load('users',$uid);
    $usu_id_us=$users->usu_id;
    $ulg=$users->lgn;
    $users->val=(int)$users->val - (int)$value;
    R::store($users);
    
    //Добавляем баланс Модератор
    $users = R::load('users',$id);
    if($users->sts == 'moderator'){
        $users->val=(int)$users->val + (int)$users->pay;
        R::store($users);
    }
    
    
    ///////////////////////////////////////////////////////////////////////////////////////////
    //В рамках ////// представлен код на смену баланса агентов по цепочке вверх
    //Объяснение: Если подагент сделал заявку (в статусе ГОТОВ) то, как-бы все её сделали, кто у него "хозяин", начиная с Админа, подробнее...
    //Цепочка Админ -> Агент -> ПодАгент. Если ПодАгент сделал заявку, то баланс меняется и у Админ, и у Агента, только по стоимости их ПТО-шек
    
    //Меняем баланс вышестоящих Агентов и Админов
    //Сначала находим всех вышестоящих агентов
    $pieces = explode("-", $pto_id_ord);
    $id_pto_ord = $pieces[0];//id ПТО Админа из табл pto
    $price_cat_pto_ord = $pieces[1]; //price + кат ПТО Админа из табл pto
    $ptous = R::getAll("SELECT * FROM ptous WHERE pto_id =:toid",[':toid'=> $id_pto_ord]);//находим все ПТО агентов из табл ptous с id ПТО (по заявке, статус которой меням)
    $us_agt = R::getAll("SELECT * FROM users WHERE id <:id AND usu_id=:usuid OR usu_id='0'",[':id'=> $uid,':usuid'=> $usu_id_us]);
    if($us_agt){
        foreach($us_agt as $rus){
            if($rus['sts'] == "admin"){
                $pto  = R::load('pto',$id_pto_ord); 
                $val_pto_adm = $pto->$price_cat_pto_ord;//помещаем в переменную сумму из таблицы pto конкретной категории, конкретного ПТО (по заявке, статус которой меням)
                //Загружаем Админа и меняем ему баланс в отрицательную сторону по его ценам ПТО в табл pto
                $users = R::load('users',$rus['id']);
                $users->val=(int)$users->val - (int)$val_pto_adm;
                if($id != $rus['id']){
                    R::store($users);
                }
            }else if($rus['sts'] == "agent"){
                foreach($ptous as $rto){
                    if($rto['users_id'] == $rus['id']){
                        $users = R::load('users',$rus['id']);
                        $users->val=(int)$users->val - (int)$rto[$price_cat_pto_ord];
                        R::store($users);
                    }
                }
            }
        }
    }
    ///////////////////////////////////////////////////////////////////////////////////////////
    
    
    $log = R::dispense('log');
    $log->date_log=date("Y-m-d H:i:s",strtotime("now"));
    $log->from_users_id=$id;
    $log->to_users_id=$uid;
    $log->from_users_login=$lgn;
    $log->to_users_login=$ulg;
    $log->value=-(int)$value;
    $log->action="Списание за ".$mmn;
    R::store($log);
    
}
R::store($order);
exit("200");