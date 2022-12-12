<?php
require "php/db.php";
$pto_id_del = htmlspecialchars($_POST['ptoid']);
$sm = htmlspecialchars($_POST['sm']);

if(is_numeric($pto_id_del) == false) exit('Ошибка! Попробуйте ещё, после перезагрузки страницы');

$pto = R::load('pto',$pto_id_del); 
if($sm=='rec'){
    $pto->del = '0';
    R::store($pto);
}else if ($sm=='del'){
    $pto->del = '1';
    R::store($pto);
    //Перевести все заявки WHERE status = 'Принята' AND id_pto = :id_pto",[':id_pto'=>$pto_id_del] в статус Черновик
    $orders = R::getAll("SELECT * FROM orders WHERE status = 'Принята' AND id_pto = :id_pto",[':id_pto'=>$pto_id_del]);
    foreach($orders as $ord){
        $ord = R::load('orders',$ord['id']);
        $ord->status = 'Черновик';
        R::store($ord);
    }
}

$ptous = R::getAll("SELECT * FROM ptous WHERE pto_id =:pid",[':pid'=> $pto_id_del]);
if($sm=='rec'){
    foreach($ptous as $row){
        $ptous = R::load('ptous',$row['id']);
        $ptous->del = '0';
        R::store($ptous);
    }
}else if ($sm=='del'){
    foreach($ptous as $row){
        $ptous = R::load('ptous',$row['id']);
        $ptous->del = '1';
        R::store($ptous);
    }
}
