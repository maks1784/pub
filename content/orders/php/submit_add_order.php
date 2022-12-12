<?php
//ini_set('display_errors',1); error_reporting(E_ALL ^E_NOTICE);
require "php/db.php";
$sm=htmlspecialchars($_POST['fstatus']);
$sub=htmlspecialchars($_POST['sub']);
if($sub == 'draft'){ $sub = '1'; $status_ord = "Черновик"; $exp_id=$id;}else if($sub == 'submit') {$sub = '0'; $status_ord = "Принята"; $exp_id=0;}
$date_added=htmlspecialchars($_POST['date_added']);
$ctg_id=htmlspecialchars($_POST['ctg_id']);
$doc=htmlspecialchars($_POST['doc']);
$doc_s=htmlspecialchars($_POST['doc_s']);
$doc_n=htmlspecialchars($_POST['doc_n']);
$doc_from=htmlspecialchars($_POST['doc_from']);
$doc_date=htmlspecialchars($_POST['doc_date']);
$doc_owner=htmlspecialchars($_POST['doc_owner']);

$car_gosn=htmlspecialchars($_POST['car_gosn']);
$car_brand=htmlspecialchars($_POST['car_brand']);
$car_model=htmlspecialchars($_POST['car_model']);
$car_vin=htmlspecialchars($_POST['car_vin']);
$car_vin_body=htmlspecialchars($_POST['car_vin_body']);
$car_vin_frame=htmlspecialchars($_POST['car_vin_frame']);
$car_year=htmlspecialchars($_POST['car_year']);
$car_weight_max=htmlspecialchars($_POST['car_weight_max']);
$car_weight_min=htmlspecialchars($_POST['car_weight_min']);

$ext_brakes=htmlspecialchars($_POST['ext_brakes']);
$ext_fuel=htmlspecialchars($_POST['ext_fuel']);
$ext_mileage=htmlspecialchars($_POST['ext_mileage']);
$ext_tire_brand=htmlspecialchars($_POST['ext_tire_brand']);
$ext_dk=htmlspecialchars($_POST['ext_dk']);
$ext_spec=htmlspecialchars($_POST['ext_spec']);

$ext_gas=htmlspecialchars($_POST['ext_gas']);
$ext_gas_doc=htmlspecialchars($_POST['ext_gas_doc']);
$ext_gas_date=htmlspecialchars($_POST['ext_gas_date']);
$ext_gb_mnf=htmlspecialchars($_POST['ext_gb_mnf']);
$ext_gb_doc_s=htmlspecialchars($_POST['ext_gb_doc_s']);
$ext_gb_date_last=htmlspecialchars($_POST['ext_gb_date_last']);
$ext_gb_date=htmlspecialchars($_POST['ext_gb_date']);

$tah_brand=htmlspecialchars($_POST['tah_brand']);
$tah_model=htmlspecialchars($_POST['tah_model']);
$tah_n=htmlspecialchars($_POST['tah_n']);
/*$photoFront=htmlspecialchars($_POST['photoFront']);
$photoBack=htmlspecialchars($_POST['photoBack']);*/
$select_pto=htmlspecialchars($_POST['select_pto']);
$id_pto=htmlspecialchars($_POST['id_pto']);
$value=htmlspecialchars($_POST['select_pto_value']);
$note=htmlspecialchars($_POST['note']);
$fstphoto=htmlspecialchars($_POST['fstphoto']);//Если равно 1, значит нажата кнопка submit и поля миниатюр уже открыты, что говорит о редактировании заявки
$bstphoto=htmlspecialchars($_POST['bstphoto']);//Если равно 1, значит нажата кнопка submit и поля миниатюр уже открыты, что говорит о редактировании заявки

//echo $sub."| <br> |".$ctg_id."| <br> |".$doc."| <br> |".$doc_s."| <br> |".$doc_n."| <br> |".$doc_from."| <br> |".$doc_date."| <br> |".$doc_owner."| <br> |".$car_gosn."| <br> |".$car_brand."| <br> |".$car_model."| <br> |".$car_vin."| <br> |".$car_vin_body."| <br> |".$car_vin_frame."| <br> |".$car_year."| <br> |".$car_weight_max."| <br> |".$car_weight_min."| <br> |".$ext_brakes."| <br> |".$ext_fuel."| <br> |".$ext_mileage."| <br> |".$ext_tire_brand."| <br> |".$ext_dk."| <br> |".$ext_spec."| <br> |".$ext_gas."| <br> |".$ext_gas_doc."| <br> |".$ext_gas_date."| <br> |".$ext_gb_mnf."| <br> |".$ext_gb_doc_s."| <br> |".$ext_gb_date_last."| <br> |".$ext_gb_date."| <br> |".$select_pto."| <br> |".$note."| <br> |";
if($sub != '1'){ 
    if($doc != "ЭПТС"){
        if(strlen($doc_s) == 0) exit('doc_s_error_01');
    }
    if(strlen($doc_n) == 0) exit('doc_n_error_01');
    if(strlen($doc_from) == 0) exit('doc_from_error_01');
    
    
    //if(strlen($car_gosn) == 0) exit('car_gosn_error_01');
    if(strlen($car_brand) == 0) exit('car_brand_error_01');
    if(strlen($car_model) == 0) exit('car_model_error_01');
    /*if(strlen($car_vin) == 0) exit('car_vin_error_01');
    if(strlen($car_vin_body) == 0) exit('car_vin_body_error_01');
    if(strlen($car_vin_frame) == 0) exit('car_vin_frame_error_01');*/
    if(strlen($car_year) == 0) exit('car_year_error_01');
    if(strlen($car_weight_max) == 0) exit('car_weight_max_error_01');
    if(strlen($car_weight_min) == 0) exit('car_weight_min_error_01');
    
    if(strlen($ext_mileage) == 0) exit('ext_mileage_error_01');
    if(strlen($ext_tire_brand) == 0) exit('ext_tire_brand_error_01');
    if($ext_gas == "1"){
        if(strlen($ext_gas_doc) == 0) exit('ext_gas_doc_error_01');
        if(strlen($ext_gb_mnf) == 0) exit('ext_gb_mnf_error_01');
        if(strlen($ext_gb_doc_s) == 0) exit('ext_gb_doc_s_error_01');
    }
}   





//Запись данных
if($sm == 'add'){
    $order = R::dispense('orders');
}else if($sm == 'edit'){
    $ord_id=htmlspecialchars($_POST['fid']);
    $order = R::load('orders',$ord_id);
    $ord_users_id=$order->users_id;
    if($order->status != 'Принята' and $order->status != 'Черновик'){exit("status_pto_error_01");}
    
}


if($sub == '0'){ //Значит зажата кнопка submit  
    if($fstphoto == '0'){ //Значит зажата кнопка submit в окне add и файлы ещё не выбраны
        //Файл может быть не загружен если $order->status == 'Принята' (Такое возможно, только при $sm == 'edit')
        if($order->status != 'Принята'){
            if(!isset($_FILES["pic_front"])){//Если файл не загружен
                exit('pic_front_ord_error_01');
            }
        }
    }
}
if(isset($_FILES["pic_front"])){
    $blank = $_FILES["pic_front"];
    if(!isSecurity($blank)) exit('pic_front_ord_error_02');
}
if($sub == '0'){  //Значит зажата кнопк submit 
    if($bstphoto == '0'){ //Значит зажата кнопка submit в окне add и файлы ещё не выбраны
        //Файл может быть не загружен если $order->status == 'Принята' (Такое возможно, только при $sm == 'edit')
        if($order->status != 'Принята'){
            if(!isset($_FILES["pic_back"])){//Если файл не загружен
                exit('pic_back_ord_error_01');
            }
        }
    }
}
if(isset($_FILES["pic_back"])){
    $blank = $_FILES["pic_back"];
    if(!isSecurity($blank)) exit('pic_back_ord_error_02');
}
$files_inputs=array(
    array(
        'name'=>'pic_front', 
        'lbl'=>'front', 
        'ind'=>'F'
    ),
    array(
        'name'=>'pic_back', 
        'lbl'=>'back', 
        'ind'=>'B'
    )
);

if($sub == '0'){
    if($select_pto == "") exit('select_pto_error_01');
}
if($sm == 'add'){
    $order->users_id=$id;
    $order->draft=$sub;
    $order->date_added=date("Y-m-d H:i:s",strtotime("now"));
    $order->mod_id=0;
    $order->exp_id=$exp_id;//Меняеятся, когда редактирует владелец заявки, или когда add
    //если edit и тогда не менять $ord_users_id
}
if($sm == 'edit'){
    if($ord_users_id == $id){
        $order->exp_id=$exp_id;
    }else{
        if($sub == '1'){//Зажат черновик модератором
            $order->exp_id=$ord_users_id;
        }
    }
}

$order->status=$status_ord;
//$order->date_exe Только после проведения от модератора
//$order->exp_id=$exp_id;//если edit тогда не менять
$order->value=$value;
$order->ctg_id=$ctg_id;
$order->doc=$doc;
$order->doc_s=$doc_s;
$order->doc_n=$doc_n;
$order->doc_from=$doc_from;
$order->doc_date=date("Y-m-d",strtotime("now"));
$order->doc_owner=$doc_owner;
$order->car_gosn=$car_gosn;
$order->car_brand=$car_brand;
$order->car_model=$car_model;
$order->car_vin=$car_vin;
$order->car_vin_body=$car_vin_body;
$order->car_vin_frame=$car_vin_frame;
$order->car_year=$car_year;
$order->car_weight_max=$car_weight_max;
$order->car_weight_min=$car_weight_min;
$order->ext_brakes=$ext_brakes;
$order->ext_fuel=$ext_fuel;
$order->ext_mileage=$ext_mileage;
$order->ext_tire_brand=$ext_tire_brand;
$order->ext_dk=$ext_dk;
$order->ext_spec=$ext_spec;
$order->ext_gas = $ext_gas;
if($ext_gas == "1"){
    $order->ext_gas_doc=$ext_gas_doc;
    $order->ext_gas_date=$ext_gas_date;
    $order->ext_gb_mnf=$ext_gb_mnf;
    $order->ext_gb_doc_s=$ext_gb_doc_s;
    $order->ext_gb_date_last=$ext_gb_date_last;
    $order->ext_gb_date=$ext_gb_date;
}
$order->tah_brand=$tah_brand;
$order->tah_model=$tah_model;
$order->tah_n=$tah_n;

$path = 'content/orders/php/files/';// Директория куда будут загружаться файлы.


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
        		$name = $row["ind"]."_".$car_brand."_".$car_model."_".$car_gosn.".".$ext;
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
        				
        				$order->$input_name = $name;
        				
        			} else {
        			    exit("pic_".$input_lbl."_ord_error_03");
        			}
        			
        		}
        	}
        }    
    }
if($select_pto != ''){
    $order->pto_id=$select_pto;
    $order->id_pto=$id_pto;
}else{
    $order->pto_id='';
    $order->id_pto='';    
}
$order->note=$note;
R::store($order);

exit("200");
?>