<?php
require "php/db.php";
$ctg_pto = htmlspecialchars($_POST['ctgpto']);//Категория, выбранная в селекте #select_ctg_modal_ord...по ней выюираются столбцы в pto или в ptous
$usid = htmlspecialchars($_POST['usid']);//users_id из orders, передаётся для Редактировании заявки
$orid = htmlspecialchars($_POST['orid']);//users_id из orders, передаётся для Редактировании заявки
$sm = htmlspecialchars($_POST['sm']);//Статус формы: Добавить/Изменить/Просмотреть заявку
$total_ctg = ctg_row($ctg_pto);//буква категории по Id в нижнем регистре
//echo $ctg_pto." ".$sm." ".$sts." ".$usid." ".$orid;
//Нужно понимать что открыто и кем
if($sm == 'add'){
    if($sts=='admin'){
        $total_pto = pto_not_del();//Достать из pto, всё где del = 0
        //Может не быть
    }
    if($sts=='agent'){
        $ptos = pto_not_del();//Достать из pto, всё где del = 0
        //Может не быть
        $total_pto = ptous($id);
        //Может не быть
    }
}
if($sm == 'edit'){
    $order = R::load('orders',$orid);
    $idpto=$order->id_pto;//id pto из orders
    //Может не быть
    
    if($usid == $id){//Открыта моя заявка, значит $usid (переданный users_id из orders == id авториз юзера)
        if($sts=='admin'){
            $total_pto = pto_not_del();//Достать из pto, всё где del = 0
            //Может не быть
        }
        if($sts=='agent'){
            $ptos = pto_not_del();//Достать из pto, всё где del = 0
            //Может не быть
            $total_pto = ptous($id);
            //Может не быть
        }
    }else{//Открыта чужая
        if($sts=='admin' or $sts=='moderator'){
            $ptos = pto_not_del();//Достать из pto, всё где del = 0
            //Может не быть
            $total_pto = ptous($usid);
            //Может не быть
        }
    }
}
if($sm == 'look'){
    $order = R::load('orders',$orid);
    $vlpto=$order->value;//id pto из orders
    $idpto=$order->id_pto;//id pto из orders
    $total_pto = pto_id($idpto);
    ?>
    <option><?php echo $total_pto?> &middot; <?php echo $vlpto?> руб.</option>
    <?php
    exit;
}
//var_dump($total_pto);
if($total_pto){
    $error=0;
    foreach($total_pto as $pto){
        //echo $pto." ".$pto['name']." ".$sm;
        if($sm == 'add'){
            if($sts=='admin'){
                if($pto['price_'.$total_ctg] != "0"){
                ?>
                <option value="<?php echo $pto['id']."-price_".$total_ctg;?>"><?php echo $pto['name']?> &middot; <?php echo $pto['price_'.$total_ctg]?> руб.</option>
                <?php 
                $error++;
                }
            }
            if($sts=='agent'){
                
                $name_pto = '';
                foreach($ptos as $row){
                    if($row['id'] == $pto['pto_id']){
                        $name_pto = $row['name'];
                    }
                }
                if($pto['price_'.$total_ctg] != "0"){
                ?>
                <option value="<?php echo $pto['id']."-price_".$total_ctg;?>"><?php echo $name_pto?> &middot; <?php echo $pto['price_'.$total_ctg]?> руб.</option>
                <?php 
                $error++;
                }
            }
        }   
        if($sm == 'edit'){
            if($usid == $id){//Открыта моя заявка, значит $usid (переданный users_id из orders == id авториз юзера)
                if($sts=='admin'){
                   
                    if($pto['price_'.$total_ctg] != "0"){
                        $select_pto = '';
                        
                        if($idpto == $pto['id']){
                            $select_pto = 'selected';
                        }
                        
                    ?>
                    <option <?php echo $select_pto;?> value="<?php echo $pto['id']."-price_".$total_ctg;?>"><?php echo $pto['name']?> &middot; <?php echo $pto['price_'.$total_ctg]?> руб.</option>
                    <?php
                    $error++;
                    }
                }
                if($sts=='agent'){
                    $name_pto = '';
                    foreach($ptos as $row){
                        if($row['id'] == $pto['pto_id']){
                            $name_pto = $row['name'];
                        }
                    }
                    if($pto['price_'.$total_ctg] != "0"){
                        $select_pto = '';
                        //echo $orid."|".$idpto. "==" .$pto['pto_id'];
                        if($idpto == $pto['pto_id']){
                            $select_pto = 'selected';
                        }
                    ?>
                    <option <?php echo $select_pto;?> value="<?php echo $pto['id']."-price_".$total_ctg;?>"><?php echo $name_pto?> &middot; <?php echo $pto['price_'.$total_ctg]?> руб.</option>
                    <?php 
                        $error++;
                    }
                }
            }else{//Открыта чужая
                if($sts=='admin' or $sts=='moderator'){
                    
                    $name_pto = '';
                    foreach($ptos as $row){
                        if($row['id'] == $pto['pto_id']){
                            $name_pto = $row['name'];
                        }
                    }
                    if($pto['price_'.$total_ctg] != "0"){
                        $select_pto = '';
                        //echo $orid."|".$idpto. "==" .$pto['pto_id'];
                        if($idpto == $pto['pto_id']){
                            $select_pto = 'selected';
                        }
                        ?>
                        <option <?php echo $select_pto;?> value="<?php echo $pto['id']."-price_".$total_ctg;?>"><?php echo $name_pto?> &middot; <?php echo $pto['price_'.$total_ctg]?> руб.</option>
                        <?php
                        $error++;
                    }
                }
            }
        }
    }
    if($error == 0){
        ?>
        <option value="">ПТО по категории <?php echo strtoupper($total_ctg);?> не найдено</option>
        <?php
    }
}else{
    //если нет pto или ptous, значит всё блокировано
    ?>
    <option value="">ПТО по категории <?php echo strtoupper($total_ctg);?> не найдено</option>
    <?php
}
?>