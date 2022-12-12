<?php
require "php/db.php";
$user_id = htmlspecialchars($_POST['uid']);
$user_value = htmlspecialchars($_POST['vfr']);
$td_date=htmlspecialchars($_POST['td_date']);
if(strlen($user_value) == 0) exit('val_error_01');
if(is_numeric($user_value) == false) exit('val_error_01');
$user = R::load('users',$user_id);
$ulg=$user->lgn;
$vl_old = (int)$user->val;
$user->val = (int)$user->val + (int)$user_value;

$log = R::dispense('log');
$log->date_log=date("Y-m-d H:i:s",strtotime("now"));
$log->from_users_id=$id;
$log->to_users_id=$user_id;
$log->from_users_login=$lgn;
$log->to_users_login=$ulg;
$log->value=(int)$user_value;
if((int)$user_value < 0){
    $log->action="Списание";
}else{
    $log->action="Пополнение";
}
R::store($log);

if($vl_old < 0){
    if($vl_old + (int)$user_value >= 0){
        $log = R::dispense('log');
        $log->date_log=$td_date;
        $log->from_users_id=$id;
        $log->to_users_id=$user_id;
        $log->from_users_login=$lgn;
        $log->to_users_login=$ulg;
        $log->action="Задолженность погашена";
        R::store($log);
    }
}

R::store($user);


exit("200");
?>