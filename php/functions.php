<?php
function isSecurity($blank){
	$name = $blank["name"];
	$type = $blank["type"];
	$size = $blank["size"];
	$blacklist = array(".php", ".phtml", ".php3", ".php4");
	foreach ($blacklist as $item){
		if(preg_match("/$item\$/i", $name)) return false;
	}
	if (($type != "image/gif") && ($type != "image/png") && ($type != "image/jpg") && ($type != "image/jpeg")) return false;
	if ($size > 5 * 1024 * 1024) return false;
	return true;
}
function isSecurityPdf($blank){
    
	$name = $blank["name"];
	$type = $blank["type"];
	$size = $blank["size"];
	$blacklist = array(".php", ".phtml", ".php3", ".php4", ".heic");
	foreach ($blacklist as $item){
		if(preg_match("/$item\$/i", $name)) return false;
	}
	if (($type != "application/pdf")) return false;
	if ($size > 10 * 1024 * 1024) return false;
	return true;
} 
///             ТАБЛИЦА users
function users(){ /// 
    $res = R::getAll("SELECT * FROM users");
    return $res;
}
function user($id){ /// 
    $res = R::getAll("SELECT * FROM users WHERE id =:id AND del != '1'",[':id' => $id]);
    return $res;
}
function user_all($id){ /// 
    $res = R::getAll("SELECT * FROM users WHERE id =:id",[':id' => $id]);
    return $res;
}
function agents(){ /// 
    $res = R::getAll("SELECT * FROM users WHERE sts ='agent'");
    return $res;
}
function agent_users($id,$usu){ /// 
   /* $pag = R::getAll("SELECT * FROM users WHERE usu = :usu",[':usu'=> $usu]);
    $rusu="";
    foreach($pag as $row){
        $rusu .= $row['usu'].",";
    }
    $rusu = substr($rusu, 0, -1);
    */
    if($usu != ""){
        $us_id = explode(",", $usu);
        $row_ids = "OR ";
        foreach($us_id as $row){
            $row_ids .= "id = ".$row." OR ";
        }
        $row_ids = substr($row_ids, 0, -4);
        $res = R::getAll("SELECT * FROM users WHERE (id = :id ".$row_ids.") AND del = '0'",[':id' => $id]);
        $resd = R::getAll("SELECT * FROM users WHERE (id = :id ".$row_ids.") AND del = '1'",[':id' => $id]);
    }else{
        $res = R::getAll("SELECT * FROM users WHERE id = :id",[':id' => $id]);
    }
    if($res and !$resd) return $res;
    else if(!$res and $resd) return $resd;
    else if(!$res and !$resd) return false;
    else if($res and $resd) return array_merge($res, $resd);
    
}
function admin_users($id){ /// 
    
    $res = R::getAll("SELECT * FROM users WHERE (id=:id OR id=usu_id) AND del = '0'",[':id' => $id]);
    $resd = R::getAll("SELECT * FROM users WHERE (id=:id OR id=usu_id) AND del = '1'",[':id' => $id]);
    if($res and !$resd) return $res;
    else if(!$res and $resd) return $resd;
    else if(!$res and !$resd) return false;
    else if($res and $resd) return array_merge($res, $resd);
}

function agents_agents_users($usui,$ida){
    $res = R::getAll("SELECT * FROM users WHERE usu != '' AND usu_id = :usu AND id >= :ida",[':usu'=> $usui,':ida'=> $ida]);
    $rusu="";
    foreach($res as $row){
        $rusu .= $row['usu'].",";
    }
    $rusu = substr($rusu, 0, -1);
    return $rusu;
}

function agent_finance($id,$usu,$start_period,$end_period,$filt_users,$filt_pto,$exc,$sts_exc){
    $row_del='';
    $rusu="";
    $row_i='';
    if($usu == ''){
        $rusu="AND id =".$id;
    }else{
        if(strpos($usu, ',') !== false ) {
            $us_id = explode(",", $usu);//Делаем массив
            $row_i = "OR ";
            foreach($us_id as $row){
                $row_i .= "id = ".$row." OR ";
            }
            $row_i = substr($row_i, 0, -4);//Строка для SQL 
            $rusu ="AND (id =".$id." ".$row_i.")";
        }else{
            $rusu="AND (id =".$id." OR id =".$usu.")";
        } 
    }
    
    if($filt_users == "all_active") $row_del .= " AND del='0'";
    else if($filt_users == "all_deactive") $row_del .= " AND del='1'";
    else $rusu="AND id =".$filt_users;
    
    $res = R::getAll("SELECT * FROM users WHERE sts = 'agent' ".$rusu." ".$row_del);
    if($res){
        //Нужно сделать строку $id_users, чтобы по id-кам юзеров найти ордера по датам и пто из фильтров
        $id_users='AND (';
        foreach($res as $row){
            $id_users.='users_id = '.$row['id'].' OR ';
        }
        $id_users = substr($id_users, 0, -4);
        $id_users .= ')';
        
        if($filt_pto != '0'){
            $id_users .= ' AND pto='.$filt_pto;
        }
        
        $ord = R::getAll("SELECT * FROM orders WHERE DATE(date_exe)>=:sd AND DATE(date_exe)<=:ed ".$id_users,[':sd'=> $start_period,':ed'=> $end_period]);//Получили ордера
       //return "SELECT * FROM orders ".$id_users;
    }else{
        return false;
    }
    if($ord){
        $id_users_filt='WHERE (';
        foreach($ord as $row){
            $id_users_filt.='id = '.$row['users_id'].' OR ';
        }
        $id_users_filt = substr($id_users_filt, 0, -4);
        $id_users_filt .= ')';
        $result = R::getAll("SELECT * FROM users ".$id_users_filt);
    }else{
        return false;
    }
    if($exc) return $ord;
    return $result;
}

///Версия вывод агентов для Админа в Финансах, с подагентами - всей цепочкой подагентов
function admin_finance($id,$usu,$start_period,$end_period,$filt_users,$filt_pto){
    //Нужно достать только тех у которых есть заявки за период времени
   /* $ord = R::getAll("SELECT * FROM orders WHERE DATE(date_added) >=:sd AND DATE(date_added) <=:ed",[':sd'=> $start_period,':ed'=> $end_period]);
    //Затем создать строку с Id-шниками тех у кого есть заявки
    $row_ids="AND";
    if($ord){
        foreach($ord as $row){
            $row_ids .= " id = ".$row['users_id']. " OR";
        }
        $row_ids = substr($row_ids, 0, -3);
    }*/
    
    if($filt_users == "all_active") $row_ids .= " AND del='0'";
    else if($filt_users == "all_deactive") $row_ids .= " AND del='1'";
    else $row_ids .= " AND id=".$filt_users;
    
    $res = R::getAll("SELECT * FROM users WHERE sts != '' ".$row_ids);
    return $res;
}

///Актуальная версия вывода агентов в Финансах
function admin_finance_new($id,$usu,$start_period,$end_period,$filt_users,$filt_pto,$exc,$sts_exc){
    $row_ids="";
    if($filt_users == "all_active") $row_ids .= " AND del='0'";
    else if($filt_users == "all_deactive") $row_ids .= " AND del='1'";
    else $row_ids .= " AND id=".$filt_users;
    
    if($exc){ 
        if($sts_exc != '') $row_ids = " AND id=".$sts_exc;
        $res = R::getAll("SELECT * FROM users WHERE sts!='moderator'".$row_ids);
    }
    if(!$exc) $res = R::getAll("SELECT * FROM users WHERE (id = usu_id OR sts='admin') ".$row_ids);
    if($res){
        //Нужно сделать строку $id_users, чтобы по id-кам юзеров найти ордера по датам и пто из фильтров
        $id_users='AND (';
        foreach($res as $row){
            $id_users.='users_id = '.$row['id'].' OR ';
        }
        $id_users = substr($id_users, 0, -4);
        $id_users .= ')';
        
        if($filt_pto != '0'){
            $id_users .= ' AND pto='.$filt_pto;
        }
        
        $ord = R::getAll("SELECT * FROM orders WHERE DATE(date_exe)>=:sd AND DATE(date_exe)<=:ed ".$id_users,[':sd'=> $start_period,':ed'=> $end_period]);//Получили ордера
       //return "SELECT * FROM orders ".$id_users;
    }else{
        return false;
    }
    if($ord){
        $id_users_filt='WHERE (';
        foreach($ord as $row){
            $id_users_filt.='id = '.$row['users_id'].' OR ';
        }
        $id_users_filt = substr($id_users_filt, 0, -4);
        $id_users_filt .= ')';
        $result = R::getAll("SELECT * FROM users ".$id_users_filt);
    }else{
        return false;
    }
    if($exc) return $ord;
    return $result;
}

///             Вывод excel
function ad_ag_excel($start_period,$end_period,$filt_pto,$sts_exc){
    $sql_row='';
    if($sts_exc != '') $sql_row .= " AND users_id=".$sts_exc;
    if($filt_pto != '0'){
        $sql_row .= ' AND pto='.$filt_pto;
    }
    $ord = R::getAll("SELECT * FROM orders WHERE (DATE(date_exe)>=:sd AND DATE(date_exe)<=:ed) ".$sql_row." ORDER by date_exe DESC",[':sd'=> $start_period,':ed'=> $end_period]);//Получили ордера
    return $ord;
}
function admin_excel($start_period,$end_period,$filt_pto){
    $sql_row='AND (';
    $res = R::getAll("SELECT * FROM users WHERE sts!='moderator'");

    foreach($res as $row){
        $sql_row.='users_id = '.$row['id'].' OR ';
    }
    $sql_row = substr($sql_row, 0, -4);
    $sql_row .= ')';
    
    if($filt_pto != '0'){
        $sql_row .= ' AND pto='.$filt_pto;
    }
    $ord = R::getAll("SELECT * FROM orders WHERE (DATE(date_exe)>=:sd AND DATE(date_exe)<=:ed) ".$sql_row." ORDER by date_exe DESC",[':sd'=> $start_period,':ed'=> $end_period]);//Получили ордера
    return $ord;
}
function agent_exl($id,$usu,$start_period,$end_period,$filt_pto){
    
    $res = R::getAll("SELECT * FROM users WHERE usu_id = :usu AND id >= :ida",[':usu'=> $usu,':ida'=> $id]);
    $rusu="";
    foreach($res as $row){
        $rusu .= $row['usu'].",";
    }
    $rusu = substr($rusu, 0, -2);//Через запятую строка
    
    $sql_row='';
    if($rusu != ""){
        $us_id = explode(",", $rusu);//Делаем массив
        $sql_row = "AND (";
        foreach($us_id as $row){
            $sql_row .= "users_id = ".$row." OR ";
        }
        $sql_row .= 'users_id = '.$id.')';
    }else{
        $sql_row = 'AND users_id = '.$id;
    }
    
    if($filt_pto != '0'){
        $sql_row .= ' AND pto='.$filt_pto;
    }
    $ord = R::getAll("SELECT * FROM orders WHERE (DATE(date_exe)>=:sd AND DATE(date_exe)<=:ed) ".$sql_row." ORDER by date_exe DESC",[':sd'=> $start_period,':ed'=> $end_period]);//Получили ордера
    
    return $ord;//"SELECT * FROM orders WHERE (DATE(date_exe)>=".$start_period." AND DATE(date_exe)<=".$end_period.") ".$sql_row." ORDER by date_exe DESC";
}

function agent_excel($id,$usu,$start_period,$end_period,$filt_pto){
    
    $res = R::getAll("SELECT * FROM users WHERE usu != '' AND usu_id = :usu AND id >= :ida",[':usu'=> $usu,':ida'=> $id]);
    $rusu="";
    foreach($res as $row){
        $rusu .= $row['usu'].",";
    }
    $rusu = substr($rusu, 0, -1);//Через запятую строка
    
    $sql_row='';
    if($rusu != ""){
        $us_id = explode(",", $rusu);//Делаем массив
        $sql_row = "AND (";
        foreach($us_id as $row){
            $sql_row .= "users_id = ".$row." OR ";
        }
        $sql_row .= 'users_id = '.$id.')';
    }else{
        $sql_row = 'AND users_id = '.$id;
    }
    
    if($filt_pto != '0'){
        $sql_row .= ' AND pto='.$filt_pto;
    }
    $ord = R::getAll("SELECT * FROM orders WHERE (DATE(date_exe)>=:sd AND DATE(date_exe)<=:ed) ".$sql_row." ORDER by date_exe DESC",[':sd'=> $start_period,':ed'=> $end_period]);//Получили ордера
    return $ord;
}
///             Вывод excel


///             ТАБЛИЦА pto
function pto(){ /// 
    $res = R::getAll("SELECT * FROM pto WHERE del = '0' ORDER by id DESC");
    $resd = R::getAll("SELECT * FROM pto WHERE del = '1' ORDER by id DESC");
    if($res and !$resd) return $res;
    else if(!$res and $resd) return $resd;
    else if(!$res and !$resd) return false;
    else if($res and $resd) return array_merge($res, $resd);
}
function pto_id($spto){ /// 
    $res = R::getCell("SELECT name FROM pto WHERE id = :ptoid",[':ptoid'=> $spto]);
    return $res;
}
function pto_not_del(){ /// 
    $res = R::getAll("SELECT * FROM pto WHERE del = '0'");
    return $res;
}
///             ТАБЛИЦА ptous
function ptous($id){ /// 
    $res = R::getAll("SELECT * FROM ptous WHERE users_id =:id AND del != '1'",[':id'=> $id]);
    return $res;
}///             ТАБЛИЦА ptous
function ptous_all(){ /// 
    $res = R::getAll("SELECT * FROM ptous WHERE del != '1'");
    return $res;
}
function ptous_db(){ /// 
    $res = R::getAll("SELECT * FROM ptous");
    return $res;
}
///             ТАБЛИЦА ctg
function ctg(){ /// 
    $res = R::getAll("SELECT * FROM ctg");
    return $res;
}
function ctg_row($ctg_pto){
    $res = R::getCell("SELECT ctg FROM ctg WHERE id=:ctg_r",[':ctg_r'=>$ctg_pto]);
    return strtolower($res);//буква категории по Id в нижний регистр
}
///             ТАБЛИЦА orders
function agent_orders($id,$usu_id,$cnt,$start_period,$end_period,$ctg_filt,$agt_filt,$sts_filt,$lbl_count,$change_pages){ /// ВИЖУ свои заявки и заявки подагентов
    //Находим всех подагентов Агента
    $res = R::getAll("SELECT * FROM users WHERE usu != '' AND usu_id = :usu AND id >= :id",[':usu'=> $usu_id,':id'=> $id]);
    $rusu="";
    foreach($res as $row){
        $rusu .= $row['usu'].",";
    }
    $rusu = substr($rusu, 0, -1);//Через запятую строка
    
    $row_i='';
    if($rusu != ""){
        $us_id = explode(",", $rusu);//Делаем массив
        $row_i = "OR ";
        foreach($us_id as $row){
            $row_i .= "users_id = ".$row." OR ";
        }
        $row_i = substr($row_i, 0, -4);//Строка для SQL 
    }


    $row_filt = "";
    if($ctg_filt != "0") $row_filt.= " AND ctg_id = ".$ctg_filt;
    if($agt_filt != "0") $row_filt.= " AND users_id = ".$agt_filt;
    if($sts_filt != "0") $row_filt.= " AND status = '".$sts_filt."'";
    //"SELECT * FROM orders WHERE (DATE(date_added)>=".$start_period." AND DATE(date_added)<=".$end_period.") AND (users_id=".$id." ".$row_i.") ".$row_filt." AND (exp_id=".$id." OR exp_id=0)  ORDER BY id DESC";
    if($lbl_count){
        $res = R::getAll("SELECT * FROM orders WHERE (DATE(date_added)>=:sd AND DATE(date_added)<=:ed) AND (users_id=:id ".$row_i.") ".$row_filt." AND (exp_id=:id OR exp_id=0)",[':sd'=> $start_period,':ed'=> $end_period,':id'=>$id]);
    }else{
        $res = R::getAll("SELECT * FROM orders WHERE (DATE(date_added)>=:sd AND DATE(date_added)<=:ed) AND (users_id=:id ".$row_i.") ".$row_filt." AND (exp_id=:id OR exp_id=0) ORDER BY id DESC LIMIT ".$change_pages.",".$cnt,[':sd'=> $start_period,':ed'=> $end_period,':id'=>$id]);
    }
    return $res;
}
function admin_orders($id,$cnt,$start_period,$end_period,$ctg_filt,$agt_filt,$sts_filt,$lbl_count,$change_pages){ ///ВИЖУ все заявки 
    $row_filt = "";
    if($ctg_filt != "0") $row_filt.= " AND ctg_id = ".$ctg_filt;
    if($agt_filt != "0") $row_filt.= " AND users_id = ".$agt_filt;
    if($sts_filt != "0") $row_filt.= " AND status = '".$sts_filt."'";
    if($lbl_count){
        $res = R::getAll("SELECT * FROM orders WHERE (DATE(date_added) >=:sd AND DATE(date_added)<=:ed) ".$row_filt." AND (exp_id=:id OR exp_id=0)",[':sd'=> $start_period,':ed'=> $end_period,':id'=>$id]);
    }else{
        $res = R::getAll("SELECT * FROM orders WHERE (DATE(date_added) >=:sd AND DATE(date_added)<=:ed) ".$row_filt." AND (exp_id=:id OR exp_id=0) ORDER by id DESC LIMIT ".$change_pages.",".$cnt,[':sd'=> $start_period,':ed'=> $end_period,':id'=>$id]);
    }
    return $res;
}
function moderator_orders($id,$cnt,$start_period,$end_period,$ctg_filt,$agt_filt,$sts_filt,$lbl_count,$change_pages){ ///ВИЖУ все заявки 
    $row_filt = "";
    if($ctg_filt != "0") $row_filt.= " AND ctg_id = ".$ctg_filt;
    if($agt_filt != "0") $row_filt.= " AND users_id = ".$agt_filt;
    if($sts_filt != "0") $row_filt.= " AND status = '".$sts_filt."'";
    //(status='В работе' OR status='Проверка' OR status='Готова') 
    if($lbl_count){
        $res = R::getAll("SELECT * FROM orders WHERE (status='Принята' OR mod_id =:id) AND (DATE(date_added) >=:sd AND DATE(date_added)<=:ed) ".$row_filt,[':id'=>$id,':sd'=> $start_period,':ed'=> $end_period]);
    }else{
        $res = R::getAll("SELECT * FROM orders WHERE (status='Принята' OR mod_id =:id) AND (DATE(date_added) >=:sd AND DATE(date_added)<=:ed) ".$row_filt." ORDER by id DESC LIMIT ".$change_pages.",".$cnt,[':id'=>$id,':sd'=> $start_period,':ed'=> $end_period]);
    }
    return $res;
}

///             ТАБЛИЦА log
function agent_log($id,$usu_id,$cnt,$start_period,$end_period,$filt_users,$filt_di,$lbl_count,$change_pages){
    
    $pag = R::getAll("SELECT * FROM users WHERE usu != '' AND usu_id = :usu AND id >= :id",[':usu'=> $usu_id,':id'=> $id]);
    $rusu="";
    foreach($pag as $row){
        $rusu .= $row['usu'].",";
    }
    $rusu = substr($rusu, 0, -1);
    $row_i='';
    if($rusu != ""){
        $us_id = explode(",", $rusu);
        $row_i = "OR ";
        foreach($us_id as $row){
            $row_i .= "id = ".$row." OR ";
        }
        $row_i = substr($row_i, 0, -4);
    }
    $row_ids='';
    if($filt_users == "all_active") $row_ids .= " AND del='0'";
    else if($filt_users == "all_deactive") $row_ids .= " AND del='1'";
    else $row_ids .= " AND id=".$filt_users;
    
    
    $usrs = R::getAll("SELECT * FROM users WHERE id = :id ".$row_i." ".$row_ids,[':id'=> $id]);
    $row_id = " ";
    if($usrs){
        foreach($usrs as $row){
            $row_id .= "from_users_id = ".$row['id']." OR to_users_id = ".$row['id']." OR ";
        }
        $row_id = substr($row_id, 0, -4);
    }else{
        return false;
    }
    $row_di="";
    if($filt_di != '0') $row_di .= " AND action='".$filt_di."'";
    if (!is_numeric($change_pages)) return false;
    if($lbl_count){
        //Нужно количество всех строк без Limit 
        $res = R::getAll("SELECT * FROM log WHERE (DATE(date_log)>=:sd AND DATE(date_log)<=:ed) AND (".$row_id.") ".$row_di,[':sd'=> $start_period,':ed'=> $end_period]);
        $res = count($res);
    }else{
        $res = R::getAll("SELECT * FROM log WHERE (DATE(date_log)>=:sd AND DATE(date_log)<=:ed) AND (".$row_id.") ".$row_di." ORDER BY id DESC LIMIT ".$change_pages.", ".$cnt,[':sd'=> $start_period,':ed'=> $end_period]);
    }
    
    return $res;
}

function admin_log($cnt,$start_period,$end_period,$filt_users,$filt_di,$lbl_count,$change_pages){
    
    $row_ids='';
    if($filt_users == "all_active") $row_ids .= " AND del='0'";
    else if($filt_users == "all_deactive") $row_ids .= " AND del='1'";
    else $row_ids .= " AND id=".$filt_users;
    
    
    $usrs = R::getAll("SELECT * FROM users WHERE sts != '' ".$row_ids);
    $row_id = " ";
    if($usrs){
        foreach($usrs as $row){
            $row_id .= "from_users_id = ".$row['id']." OR to_users_id = ".$row['id']." OR ";
        }
        $row_id = substr($row_id, 0, -4);
    }else{
        return false;
    }
    $row_di="";
    if($filt_di != '0') $row_di .= " AND action='".$filt_di."'";
    if (!is_numeric($change_pages)) return false;
    if($lbl_count){
        //Нужно количество всех строк без Limit 
        $res = R::getAll("SELECT * FROM log WHERE (DATE(date_log)>=:sd AND DATE(date_log)<=:ed) AND (".$row_id.") ".$row_di,[':sd'=> $start_period,':ed'=> $end_period]);
        $res = count($res);
    }else{
        $res = R::getAll("SELECT * FROM log WHERE (DATE(date_log)>=:sd AND DATE(date_log)<=:ed) AND (".$row_id.") ".$row_di." ORDER BY id DESC LIMIT ".$change_pages.", ".$cnt,[':sd'=> $start_period,':ed'=> $end_period]);
    }
    
    return $res;
}
function ctg_count($ct){
    $ctg = R::getAll("SELECT * FROM ctg WHERE ctg = :ctg",[":ctg"=>$ct]);
    if(count($ctg) > 1){
        $row_id = "AND (";
    }else{
        $row_id = "AND";
    }
    foreach($ctg as $row){
        $row_id .= " ctg_id = ".$row['id']." OR ";
    }
    $row_id = substr($row_id, 0, -4);
    if(count($ctg) > 1){
        $row_id .= ")";
    }
    return $row_id;
}

function subagents($id,$usu_id){ 
    
    $pag = R::getAll("SELECT * FROM users WHERE usu != '' AND usu_id = :usu AND id >= :id",[':usu'=> $usu_id,':id'=> $id]);
    $row_i = "OR ";
    foreach($pag as $row){
        $row_i .= "users_id = ".$row['usu']." OR ";
    }
    $row_i = substr($row_i, 0, -4);
    return $row_i;
}



 //ЭТО ЕСЛИ ЗАКАЗЧИК ЗАХОЧЕТ показывать все заявки   
function sum_a($id,$usu_id){
    $row_id = ctg_count("A");
    $row_i = subagents($id,$usu_id);
    $res = R::getAll("SELECT * FROM orders WHERE (users_id = :id ".$row_i.") AND status='Готово' ".$row_id,[':id'=> $id]);
    return count($res);
}
function sum_b($id,$usu_id){
    $row_id = ctg_count("B");
    $row_i = subagents($id,$usu_id);
    $res = R::getAll("SELECT * FROM orders WHERE (users_id = :id ".$row_i.") AND status='Готово' ".$row_id,[':id'=> $id]);
    return count($res);
}
function sum_c($id,$usu_id){
    $row_id = ctg_count("C");
    $row_i = subagents($id,$usu_id);
    $res = R::getAll("SELECT * FROM orders WHERE (users_id = :id ".$row_i.") AND status='Готово' ".$row_id,[':id'=> $id]);
    return count($res);
}
function sum_e($id,$usu_id){
    $row_id = ctg_count("E");
    $row_i = subagents($id,$usu_id);
    $res = R::getAll("SELECT * FROM orders WHERE (users_id = :id ".$row_i.") AND status='Готово' ".$row_id,[':id'=> $id]);
    return count($res);
}

function sum_a_mod($id,$usu_id){
    $row_id = ctg_count("A");
    $res = R::getAll("SELECT * FROM orders WHERE mod_id = :id AND status='Готово' ".$row_id,[':id'=> $id]);
    return count($res);
}
function sum_b_mod($id,$usu_id){
    $row_id = ctg_count("B");
    $res = R::getAll("SELECT * FROM orders WHERE mod_id = :id AND status='Готово' ".$row_id,[':id'=> $id]);
    return count($res);
}
function sum_c_mod($id,$usu_id){
    $row_id = ctg_count("C");
    $res = R::getAll("SELECT * FROM orders WHERE mod_id = :id AND status='Готово' ".$row_id,[':id'=> $id]);
    return count($res);
}
function sum_e_mod($id,$usu_id){
    $row_id = ctg_count("E");
    $res = R::getAll("SELECT * FROM orders WHERE mod_id = :id AND status='Готово' ".$row_id,[':id'=> $id]);
    return count($res);
}

function sum_a_me($id,$usu_id){
    $row_id = ctg_count("A");
    $res = R::getAll("SELECT * FROM orders WHERE users_id = :id AND status='Готово' ".$row_id,[':id'=> $id]);
    return count($res);
}
function sum_b_me($id,$usu_id){
    $row_id = ctg_count("B");
    $res = R::getAll("SELECT * FROM orders WHERE users_id = :id AND status='Готово' ".$row_id,[':id'=> $id]);
    return count($res);
}
function sum_c_me($id,$usu_id){
    $row_id = ctg_count("C");
    $res = R::getAll("SELECT * FROM orders WHERE users_id = :id AND status='Готово' ".$row_id,[':id'=> $id]);
    return count($res);
}
function sum_e_me($id,$usu_id){
    $row_id = ctg_count("E");
    $res = R::getAll("SELECT * FROM orders WHERE users_id = :id AND status='Готово' ".$row_id,[':id'=> $id]);
    return count($res);
}

function sum_a_filt($id,$usu_id,$start_period,$end_period,$filt_pto){
    $row_id = ctg_count("A");
    $row_i = subagents($id,$usu_id);
    $pto_sql='';
    if($filt_pto != '0') $pto_sql .= ' AND pto='.$filt_pto;
    $res = R::getAll("SELECT * FROM orders WHERE (users_id = :id ".$row_i.") AND DATE(date_exe)>=:sd AND DATE(date_exe)<=:ed ".$pto_sql." AND status='Готово' ".$row_id,[':id'=> $id,':sd'=> $start_period,':ed'=> $end_period]);
    return count($res);
}
function sum_b_filt($id,$usu_id,$start_period,$end_period,$filt_pto){
    $row_id = ctg_count("B");
    $row_i = subagents($id,$usu_id);
    $pto_sql='';
    if($filt_pto != '0') $pto_sql .= ' AND pto='.$filt_pto;
    $res = R::getAll("SELECT * FROM orders WHERE (users_id = :id ".$row_i.") AND DATE(date_exe)>=:sd AND DATE(date_exe)<=:ed ".$pto_sql." AND status='Готово' ".$row_id,[':id'=> $id,':sd'=> $start_period,':ed'=> $end_period]);
    return count($res);
}
function sum_c_filt($id,$usu_id,$start_period,$end_period,$filt_pto){
    $row_id = ctg_count("C");
    $row_i = subagents($id,$usu_id);
    $pto_sql='';
    if($filt_pto != '0') $pto_sql .= ' AND pto='.$filt_pto;
    $res = R::getAll("SELECT * FROM orders WHERE (users_id = :id ".$row_i.") AND DATE(date_exe)>=:sd AND DATE(date_exe)<=:ed ".$pto_sql." AND status='Готово' ".$row_id,[':id'=> $id,':sd'=> $start_period,':ed'=> $end_period]);
    return count($res);
}
function sum_e_filt($id,$usu_id,$start_period,$end_period,$filt_pto){
    $row_id = ctg_count("E");
    $row_i = subagents($id,$usu_id);
    $pto_sql='';
    if($filt_pto != '0') $pto_sql .= ' AND pto='.$filt_pto;
    $res = R::getAll("SELECT * FROM orders WHERE (users_id = :id ".$row_i.") AND DATE(date_exe)>=:sd AND DATE(date_exe)<=:ed ".$pto_sql." AND status='Готово' ".$row_id,[':id'=> $id,':sd'=> $start_period,':ed'=> $end_period]);
    return count($res);
}

function sum_a_filt_me($id,$usu_id,$start_period,$end_period,$filt_pto){
    $row_id = ctg_count("A");
    
    $pto_sql='';
    if($filt_pto != '0') $pto_sql .= ' AND pto='.$filt_pto;
    $res = R::getAll("SELECT * FROM orders WHERE users_id = :id AND DATE(date_exe)>=:sd AND DATE(date_exe)<=:ed ".$pto_sql." AND status='Готово' ".$row_id,[':id'=> $id,':sd'=> $start_period,':ed'=> $end_period]);
    return count($res);
}
function sum_b_filt_me($id,$usu_id,$start_period,$end_period,$filt_pto){
    $row_id = ctg_count("B");
    
    $pto_sql='';
    if($filt_pto != '0') $pto_sql .= ' AND pto='.$filt_pto;
    $res = R::getAll("SELECT * FROM orders WHERE users_id = :id AND DATE(date_exe)>=:sd AND DATE(date_exe)<=:ed ".$pto_sql." AND status='Готово' ".$row_id,[':id'=> $id,':sd'=> $start_period,':ed'=> $end_period]);
    return count($res);
}
function sum_c_filt_me($id,$usu_id,$start_period,$end_period,$filt_pto){
    $row_id = ctg_count("C");
    
    $pto_sql='';
    if($filt_pto != '0') $pto_sql .= ' AND pto='.$filt_pto;
    $res = R::getAll("SELECT * FROM orders WHERE users_id = :id AND DATE(date_exe)>=:sd AND DATE(date_exe)<=:ed ".$pto_sql." AND status='Готово' ".$row_id,[':id'=> $id,':sd'=> $start_period,':ed'=> $end_period]);
    return count($res);
}
function sum_e_filt_me($id,$usu_id,$start_period,$end_period,$filt_pto){
    $row_id = ctg_count("E");
    
    $pto_sql='';
    if($filt_pto != '0') $pto_sql .= ' AND pto='.$filt_pto;
    $res = R::getAll("SELECT * FROM orders WHERE users_id = :id AND DATE(date_exe)>=:sd AND DATE(date_exe)<=:ed ".$pto_sql." AND status='Готово' ".$row_id,[':id'=> $id,':sd'=> $start_period,':ed'=> $end_period]);
    return count($res);
}

function sum_ord($id,$usu_id){
    $row_i = subagents($id,$usu_id);
    $res = R::getAll("SELECT * FROM orders WHERE (users_id = :id ".$row_i.") AND status='Готово' ",[':id'=> $id]);
    return count($res);
}
function sum_ord_mod($id){
    $res = R::getAll("SELECT * FROM orders WHERE mod_id = :id AND status='Готово' ",[':id'=> $id]);
    return count($res);
}
function sum_ord_me($id,$usu_id){
    $res = R::getAll("SELECT * FROM orders WHERE users_id = :id AND status='Готово' ",[':id'=> $id]);
    return count($res);
}
function sum_ord_filt($id,$usu_id,$start_period,$end_period,$filt_pto){
    $row_i = subagents($id,$usu_id);
    $pto_sql='';
    if($filt_pto != '0') $pto_sql .= ' AND pto='.$filt_pto;
    $res = R::getAll("SELECT * FROM orders WHERE (users_id = :id ".$row_i.") AND DATE(date_exe)>=:sd AND DATE(date_exe)<=:ed ".$pto_sql." AND status='Готово' ",[':id'=> $id,':sd'=> $start_period,':ed'=> $end_period]);
    return count($res);
}
function sum_ord_filt_me($id,$usu_id,$start_period,$end_period,$filt_pto){
    
    $pto_sql='';
    if($filt_pto != '0') $pto_sql .= ' AND pto='.$filt_pto;
    $res = R::getAll("SELECT * FROM orders WHERE users_id = :id AND DATE(date_exe)>=:sd AND DATE(date_exe)<=:ed ".$pto_sql." AND status='Готово' ",[':id'=> $id,':sd'=> $start_period,':ed'=> $end_period]);
    return count($res);
}
function sum_orders_val($sts,$a_id,$usu_id,$start_period,$end_period,$filt_pto){
    
    if($sts == "admin"){
        $pto = R::getAll("SELECT * FROM pto");
    }else if($sts == "agent"){
        $pto = R::getAll("SELECT * FROM ptous WHERE users_id = :id",[':id'=> $a_id]);
    }
    $row_i = subagents($a_id,$usu_id);
    $pto_sql='';
    if($filt_pto != '0') $pto_sql .= ' AND pto='.$filt_pto;
    $ord = R::getAll("SELECT * FROM orders WHERE (users_id = :id ".$row_i.") AND DATE(date_exe)>=:sd AND DATE(date_exe)<=:ed ".$pto_sql." AND status='Готово' ",[':id'=> $a_id,':sd'=> $start_period,':ed'=> $end_period]);
    $sum_income=0;
    foreach($ord as $row){
        $pieces = explode("-", $row['pto_id']);
        $id_pto_ord = $pieces[0];
        $price_cat_pto_ord = $pieces[1];
        foreach($pto as $ptr){
            if($sts == "admin"){
                if($id_pto_ord == $ptr['id']){
                    $sum_income += $ptr[$price_cat_pto_ord]; 
                }
            }else if($sts == "agent"){
                if($id_pto_ord == $ptr['pto_id']){
                    $sum_income += $ptr[$price_cat_pto_ord]; 
                }
            }
        }
    }
    return $sum_income;
}

function sum_pto_user($id,$sts,$a_id,$usu_id,$start_period,$end_period,$filt_pto){
    if($sts == "admin"){
        $pto = R::getAll("SELECT * FROM pto");
    }else if($sts == "agent"){
        $pto = R::getAll("SELECT * FROM ptous WHERE users_id = :id",[':id'=> $id]);
    }
    $row_i = subagents($a_id,$usu_id);
    $pto_sql='';
    if($filt_pto != '0') $pto_sql .= ' AND pto='.$filt_pto;
    $ord = R::getAll("SELECT * FROM orders WHERE (users_id = :id ".$row_i.") AND DATE(date_exe)>=:sd AND DATE(date_exe)<=:ed ".$pto_sql." AND status='Готово' ",[':id'=> $a_id,':sd'=> $start_period,':ed'=> $end_period]);
    $sum_expense=0;
    foreach($ord as $row){
        $pieces = explode("-", $row['pto_id']);
        $id_pto_ord = $pieces[0];
        $price_cat_pto_ord = $pieces[1];
        foreach($pto as $ptr){
            if($sts == "admin"){
                if($id_pto_ord == $ptr['id']){
                    $sum_expense += $ptr[$price_cat_pto_ord]; 
                }
            }else if($sts == "agent"){
                if($id_pto_ord == $ptr['pto_id']){
                    $sum_expense += $ptr[$price_cat_pto_ord]; 
                }
            }
           
        }
    }
    return $sum_expense;
    return $sum_expense;
}


?>