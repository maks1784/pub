<?php
require "php/db.php";
$agn_id_del = htmlspecialchars($_POST['agnid']);
$sm = htmlspecialchars($_POST['sm']);
$dca = htmlspecialchars($_POST['dca']);
if(is_numeric($agn_id_del) == false) exit('error_id_num_01');
if($sm=='del'){
    if($dca == "") exit('error_comm_01');
}

$users = R::load('users',$agn_id_del); 
$usu_id=$users->usu_id;
$res = R::getAll("SELECT * FROM users WHERE id >= :ag_ids AND usu_id = :usu_id",[':ag_ids' => $agn_id_del,':usu_id'=>$usu_id]);
//var_dump($res);
foreach($res as $row){
    $user = R::load('users',$row['id']);
    
    if($sm=='rec'){
        $user->del = '0';
    }if ($sm=='del'){
        $user->del = '1';
    }
    if($row['id'] == $agn_id_del){
        if ($sm=='del'){
            $user->del_comment = $dca;
        }if($sm=='rec'){
            $user->del_comment = '';
        }
    }
    R::store($user);
}

exit("200");
/*
$user = R::load('users',$agn_id_del); 
if($sm=='rec'){
    $user->del = '0';
    R::store($user);
}else if ($sm=='del'){
    $user->del = '1';
    R::store($user);
}*/

