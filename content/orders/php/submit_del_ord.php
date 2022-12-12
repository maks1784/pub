<?php
require "php/db.php";
$ord_id_del = htmlspecialchars($_POST['ord_id']);

if(is_numeric($ord_id_del) == false) exit('Ошибка! Попробуйте ещё, после перезагрузки страницы');

R::trash('orders',$ord_id_del); 

