<?php
//ini_set('display_errors',1); error_reporting(E_ALL ^E_NOTICE);
require "php/db.php";

$status = htmlspecialchars($_POST['sts']);
$login = htmlspecialchars($_POST['lgu']);
$password = htmlspecialchars($_POST['psu']);
$user_id = htmlspecialchars($_POST['igi']);
$pay_moder = 0;
$inputs_ctg = $_POST['inc'];

if(strlen($login) == 0) exit('login_error_01');
if($user_id == ""){
    if(strlen($password) == 0) exit('str_error_01');
}

$user = R::findOne('users', 'lgn = ?', array($login));
if($user_id == ""){
    if($user) exit('login_error_02');
}

if($sts == "admin"){
    if($status == "agent"){
        if($inputs_ctg != ''){
            $total_users_pto = pto();
            $total_users_ptous = ptous($id);
            foreach($inputs_ctg as $row){
                if(strlen($row['price_a']) == 0) exit('pr_a_error_01');
                if(is_numeric($row['price_a']) == false) exit('pr_a_error_01');
                if(strlen($row['price_b']) == 0) exit('pr_b_error_01');
                if(is_numeric($row['price_b']) == false) exit('pr_b_error_01');
                if(strlen($row['price_c']) == 0) exit('pr_c_error_01');
                if(is_numeric($row['price_c']) == false) exit('pr_c_error_01');
                if(strlen($row['price_e']) == 0) exit('pr_e_error_01');
                if(is_numeric($row['price_e']) == false) exit('pr_e_error_01');
                foreach($total_users_pto as $pto){
                    if($pto['id'] == $row['id']){
                        if($pto['price_a'] != '0')if((int)$pto['price_a'] >= (int)$row['price_a']) exit('pr_a_error_02');
                        if($pto['price_b'] != '0')if((int)$pto['price_b'] >= (int)$row['price_b']) exit('pr_b_error_02');
                        if($pto['price_c'] != '0')if((int)$pto['price_c'] >= (int)$row['price_c']) exit('pr_c_error_02');
                        if($pto['price_e'] != '0')if((int)$pto['price_e'] >= (int)$row['price_e']) exit('pr_e_error_02');
                    }
                }
            }
        }
    }
    if($status == "moderator"){
        $pay_moder = htmlspecialchars($_POST['pay']);
        if(strlen($pay_moder) == 0) exit('pay_error_01');
        if(is_numeric($pay_moder) == false) exit('pay_error_01');
    }
    if($user_id == ""){
        $user = R::dispense('users');
    }else{
        //Значит редактирование агента от АДМИНА
        $user = R::load('users',$user_id);
        
        
    }
    if($user_id == ""){
        $user->date_reg = date("Y-m-d H:i:s", strtotime("now"));
        $user->sts = $status;
        $user->lgn = $login;
    }
    $user->pay = $pay_moder;
    if($user_id == ""){
        $user->str = password_hash($password, PASSWORD_DEFAULT);
    }else{
        if($password != ''){
            $user->str = password_hash($password, PASSWORD_DEFAULT);
        }
    }
    $subagent = R::store($user);
    
    $user=R::load('users',$subagent);
    if($user_id == ""){
        $user->lbl = $lgn." → ".$login;
    }
    $user->usu_id = $subagent;
    $user->cnt = '50';
    R::store($user);
    
    if($status == "agent"){
        if($user_id == ""){
            if($inputs_ctg != ''){
                $row_raz=array();
                foreach($inputs_ctg as $row){
                    foreach($total_users_pto as $pto){
                        if($pto['id'] == $row['id']){
                            array_push($row_raz, $row['price_a'] - $pto['price_a'], $row['price_b'] - $pto['price_b'], $row['price_c'] - $pto['price_c'], $row['price_e'] - $pto['price_e']);
                        }
                    }
                    $ptous = R::dispense('ptous');
                    $ptous->users_id = $subagent;
                    $ptous->pto_id = $row['id'];
                    $ptous->price_a = $row['price_a'];
                    $ptous->price_b = $row['price_b'];
                    $ptous->price_c = $row['price_c'];
                    $ptous->price_e = $row['price_e'];
                    $ptous->ext_min = min($row_raz);
                    R::store($ptous);
                }
            }
        }else{
            if($user_id != $id){
                $act_lbl = false;
                $act_a_lbl='';
                $act_b_lbl='';
                $act_c_lbl='';
                $act_e_lbl='';
                //Значит выбрано редактирование Агента
                //Достать ptous редактированного агента
                $ptous_ag = R::getAll("SELECT * FROM ptous WHERE users_id = :id",[':id'=> $user_id]);
                foreach($ptous_ag as $ptu){
                    if($inputs_ctg != ''){
                        foreach($inputs_ctg as $row){
                            if($ptu['pto_id'] == $row['id']){
                                $ptous = R::load('ptous',$ptu['id']);
                                if($ptu['price_a'] != $row['price_a']) $act_a_lbl = "A = ".$ptu['price_a']." → ".$row['price_a'];
                                if($ptu['price_b'] != $row['price_b']) $act_b_lbl = "B = ".$ptu['price_b']." → ".$row['price_b'];
                                if($ptu['price_c'] != $row['price_c']) $act_c_lbl = "C = ".$ptu['price_c']." → ".$row['price_c'];
                                if($ptu['price_e'] != $row['price_e']) $act_e_lbl = "E = ".$ptu['price_e']." → ".$row['price_e'];
                                
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
                                    $log->to_users_id=$user_id;
                                    $log->from_users_login=$lgn;
                                    $log->to_users_login=$login;
                                    $log->action=$act_lbl;
                                    R::store($log); 
                                }
                                $ptous->price_a = $row['price_a'];
                                $ptous->price_b = $row['price_b'];
                                $ptous->price_c = $row['price_c'];
                                $ptous->price_e = $row['price_e'];
                                R::store($ptous);
                            }
                        }
                    }
                }
            }    
        }
    }

}
if($sts == "agent"){
    if($inputs_ctg != ''){
        $total_users_pto = pto();
        $total_users_ptous = ptous($id);
        foreach($inputs_ctg as $row){
            if(strlen($row['price_a']) == 0) exit('pr_a_error_01');
            if(is_numeric($row['price_a']) == false) exit('pr_a_error_01');
            if(strlen($row['price_b']) == 0) exit('pr_b_error_01');
            if(is_numeric($row['price_b']) == false) exit('pr_b_error_01');
            if(strlen($row['price_c']) == 0) exit('pr_c_error_01');
            if(is_numeric($row['price_c']) == false) exit('pr_c_error_01');
            if(strlen($row['price_e']) == 0) exit('pr_e_error_01');
            if(is_numeric($row['price_e']) == false) exit('pr_e_error_01');
            foreach($total_users_ptous as $pto){
                if($pto['pto_id'] == $row['id']){
                    if($pto['price_a'] != '0')if((int)$pto['price_a'] >= (int)$row['price_a']) exit('pr_a_error_02');
                    if($pto['price_b'] != '0')if((int)$pto['price_b'] >= (int)$row['price_b']) exit('pr_b_error_02');
                    if($pto['price_c'] != '0')if((int)$pto['price_c'] >= (int)$row['price_c']) exit('pr_c_error_02');
                    if($pto['price_e'] != '0')if((int)$pto['price_e'] >= (int)$row['price_e']) exit('pr_e_error_02');
                }
            }
        }
    }
    
    if($user_id == ""){
        $user = R::dispense('users');
    }else{
        //Значит редактирование агента от АГЕНТА
        $user = R::load('users',$user_id);
    }
    if($user_id == ""){
        $user->sts = 'agent';
        $user->date_reg = date("Y-m-d H:i:s", strtotime("now"));
        $user->lgn = $login;
    }
    if($user_id == ""){
        $user->str = password_hash($password, PASSWORD_DEFAULT);
    }else{
        if($password != ''){
            $user->str = password_hash($password, PASSWORD_DEFAULT);
        }
    }
    if($user_id == ""){
        $user->lbl = $lbl." → ".$login;
    }
    $user->usu_id = $usu_id;
    $user->cnt = '50';
    $subagent=R::store($user);
    
    if($user_id == ""){
        $user_ag = R::getAll("SELECT * FROM users WHERE id = :id",[':id'=> $id]);
        foreach($user_ag as $rus){
            if($rus['usu'] == ""){
                R::exec("UPDATE users SET usu=:sa WHERE id = :id",[':sa'=> $subagent,':id'=> $id]);
            }else{
                R::exec("UPDATE users SET usu=:sa WHERE id = :id",[':sa'=> $subagent. ",".$rus['usu'],':id'=> $id]);
            }
        }
    }
    //$subagent= 344;
    if($user_id == ""){
        if($inputs_ctg != ''){
            $row_raz=array();
            foreach($inputs_ctg as $row){
                foreach($total_users_pto as $pto){
                    if($pto['id'] == $row['id']){
                        array_push($row_raz, $row['price_a'] - $pto['price_a'], $row['price_b'] - $pto['price_b'], $row['price_c'] - $pto['price_c'], $row['price_e'] - $pto['price_e']);
                    }
                }
                $ptous = R::dispense('ptous');
                $ptous->users_id = $subagent;
                $ptous->pto_id = $row['id'];
                $ptous->price_a = $row['price_a'];
                $ptous->price_b = $row['price_b'];
                $ptous->price_c = $row['price_c'];
                $ptous->price_e = $row['price_e'];
                $ptous->ext_min = min($row_raz);
                R::store($ptous);
            }
        }
    }else{
        if($user_id != $id){
            $act_lbl = false;
            $act_a_lbl='';
            $act_b_lbl='';
            $act_c_lbl='';
            $act_e_lbl='';
            //Значит выбрано редактирование Агента
            //Достать ptous редактированного агента
            $ptous_ag = R::getAll("SELECT * FROM ptous WHERE users_id = :id",[':id'=> $user_id]);
            foreach($ptous_ag as $ptu){
                if($inputs_ctg != ''){
                    foreach($inputs_ctg as $row){
                        if($ptu['pto_id'] == $row['id']){
                            $ptous = R::load('ptous',$ptu['id']);
                            if($ptu['price_a'] != $row['price_a']) $act_a_lbl = "A = ".$ptu['price_a']." → ".$row['price_a'];
                            if($ptu['price_b'] != $row['price_b']) $act_b_lbl = "B = ".$ptu['price_b']." → ".$row['price_b'];
                            if($ptu['price_c'] != $row['price_c']) $act_c_lbl = "C = ".$ptu['price_c']." → ".$row['price_c'];
                            if($ptu['price_e'] != $row['price_e']) $act_e_lbl = "E = ".$ptu['price_e']." → ".$row['price_e'];
                            
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
                                $log->to_users_id=$user_id;
                                $log->from_users_login=$lgn;
                                $log->to_users_login=$login;
                                $log->action=$act_lbl;
                                R::store($log); 
                            }
                            $ptous->price_a = $row['price_a'];
                            $ptous->price_b = $row['price_b'];
                            $ptous->price_c = $row['price_c'];
                            $ptous->price_e = $row['price_e'];
                            R::store($ptous);
                        }
                    }
                }
            }
        }
    }
}
exit('200');