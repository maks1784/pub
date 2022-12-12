<?php
ini_set('display_errors',1); error_reporting(E_ALL ^E_NOTICE);
require "php/db.php";
$start_period = htmlspecialchars($_POST['st']);
$end_period = htmlspecialchars($_POST['en']);
$ctg_filt = htmlspecialchars($_POST['cg']);
$agt_filt = htmlspecialchars($_POST['ag']);
$sts_filt = htmlspecialchars($_POST['ss']);
$change_pages = htmlspecialchars($_POST['pp']);
$total_ctg = ctg();
$total_users = users();
$lbl_count=false;
if($sts == "agent"){
    $total_users_orders = agent_orders($id,$usu_id,$cnt,$start_period,$end_period,$ctg_filt,$agt_filt,$sts_filt,$lbl_count,$change_pages);
    $lbl_count=true;
    $count_org = agent_orders($id,$usu_id,$cnt,$start_period,$end_period,$ctg_filt,$agt_filt,$sts_filt,$lbl_count,$change_pages);
}else if($sts == "admin"){
    $total_users_orders = admin_orders($id,$cnt,$start_period,$end_period,$ctg_filt,$agt_filt,$sts_filt,$lbl_count,$change_pages);
    $lbl_count=true;
    $count_org = admin_orders($id,$cnt,$start_period,$end_period,$ctg_filt,$agt_filt,$sts_filt,$lbl_count,$change_pages);
}else if($sts == "moderator"){
    $total_users_orders = moderator_orders($id,$cnt,$start_period,$end_period,$ctg_filt,$agt_filt,$sts_filt,$lbl_count,$change_pages);
    $lbl_count=true;
    $count_org = moderator_orders($id,$cnt,$start_period,$end_period,$ctg_filt,$agt_filt,$sts_filt,$lbl_count,$change_pages);
}

if($total_users_orders){
   // echo "<tr><td>".$total_users_orders."</td></tr>";
    foreach($total_users_orders as $row){
        $ctm = "";$gos_n = "";$car_vin=""; $date_exe = "";$row_tbl="";$date_exec='';$time_exec='';$pdf_name='';
        if($row['car_gosn'] != "")$gos_n = $row['car_gosn'];
        if($row['car_vin'] != "")$car_vin = $row['car_vin'];
        if($row['date_exe'] != "0000-00-00 00:00:00") {
            $date_exe = $row['date_exe'];
            $date_exec = date("d.m.Y",strtotime($date_exe));
            $time_exec = date("H:i:s",strtotime($date_exe));  
        }
        if($row['pdf_name'] !='')$pdf_name=$row['pdf_name'];
        foreach($total_ctg as $ctg){
            if($ctg['id'] == $row['ctg_id']){
                $ctm = $ctg['ctm'] ;
            }
        }
        foreach($total_users as $usr){
            if($usr['id'] == $row['users_id']){
                $usr_lgn = $usr['lgn'] ;
            }
        }
        
        if($row['status'] == "Черновик"){$lbl_sts = '<span class="badge rounded-pill bg-secondary">Черновик</span>';$row_tbl="table-secondary";}
        if($sts == "agent"){
            if($row['status'] == "Принята"){$lbl_sts = '<span class="badge rounded-pill bg-white text-dark">Принята</span>';}
            if($row['status'] == "В работе"){$lbl_sts = '<span class="badge rounded-pill bg-warning">В работе</span>';$row_tbl="table-warning";}
            if($row['status'] == "Проверка"){$lbl_sts = '<span class="badge rounded-pill bg-primary text-white">Проверка</span>';$row_tbl="table-primary";}
        }
        else{
            if($row['status'] == "Принята"){$lbl_sts = '<button data-mmn = "'.$row['car_brand']." ".$row['car_model'].'" data-id-order="'.$row['id'].'" data-bs-toggle="modal" data-bs-target="#modalEditStatusOrder" title = "Изменить статус" class="edit_st_order btn badge rounded-pill bg-white text-dark">Принята</button>';}
            if($row['status'] == "В работе"){$lbl_sts = '<button data-mmn = "'.$row['car_brand']." ".$row['car_model'].'" data-id-order="'.$row['id'].'" data-bs-toggle="modal" data-bs-target="#modalEditStatusOrder" title = "Изменить статус" class="edit_st_order btn badge rounded-pill bg-warning">В работе</button>';$row_tbl="table-warning";}
            if($row['status'] == "Проверка"){$lbl_sts = '<button data-mmn = "'.$row['car_brand']." ".$row['car_model'].'" data-id-order="'.$row['id'].'" data-bs-toggle="modal" data-bs-target="#modalEditStatusOrder" title = "Изменить статус" class="edit_st_order btn badge rounded-pill bg-primary text-white">Проверка</button>';$row_tbl="table-primary";}
        }
        if($row['status'] == "Готово"){$lbl_sts = '<span class="badge rounded-pill bg-success">Готово</span>';}
        if($row['status'] == "Архив"){$lbl_sts = '<span class="badge rounded-pill bg-danger">Архив</span>';$row_tbl="table-danger";}
?>
<tr class="align-middle <?php echo $row_tbl;?> orders_tr_row">
    
	<td class="search_text_input" data-seacrh-text="<?php echo $row['car_brand']." ".$row['car_model']." ".$gos_n." ".$car_vin." ".$pdf_name?>"><?php echo $row['car_brand']." ".$row['car_model']?></td>
	<td><?php echo $gos_n?></td>
	<td><?php echo $car_vin?></td>
	<td align="center"><?php echo $ctm;?></td>
    <td><?php echo $usr_lgn;?></td>
	<td align="center"><?php echo $lbl_sts;?></td>
	<td><?php echo date("d.m.Y",strtotime($row['date_added']));?><br><?php echo date("H:i:s",strtotime($row['date_added']));?></td>
    <td><?php echo $date_exec;?><br><?php echo $time_exec;?></td>
    
    <?php
    if($row['status'] == "Принята" or $row['status'] == "Черновик"){
        
    ?>
    <td align="center">
        <?php
        if($sts != 'moderator'){
        ?>
        <button class="btn text-primary edit_modal_ord" data-id-row="<?php echo $row['id'];?>" data-bs-toggle="modal" data-bs-target="#modalAddOrder" title="Редактировать"><i class="bi bi-pencil"></i></button> 
        <button class="btn text-primary del_modal_ord" data-id-row="<?php echo $row['id'];?>" data-bs-toggle="modal" data-bs-target="#delOrdModal" title="Удалить"><i class="bi bi-trash3"></i></button>
        <?php
        }else{
        ?>
        <button class="btn text-primary look_modal_ord" data-id-row="<?php echo $row['id'];?>" data-bs-toggle="modal" data-bs-target="#modalAddOrder" title="Просмотр"><i class="bi bi-eye"></i></button>
        <?php 
        }
        ?>
    </td>
    <?php
    }
    else if($row['status'] == "В работе"){
    ?>
    <td align="center">
    </td>
    <?php
    }
    else if($row['status'] == "Проверка" or $row['status'] == "Архив"){
    ?>
    <td align="center">
        <button class="btn text-primary look_modal_ord" data-id-row="<?php echo $row['id'];?>" data-bs-toggle="modal" data-bs-target="#modalAddOrder" title="Просмотр"><i class="bi bi-eye"></i></button>
    </td>
    <?php 
    }
    else if($row['status'] == "Готово"){
    ?>
    <td align="center">
        <button class="btn text-primary" onclick="window.open('content/orders/php/files/<?php echo $row['pdf_file']?>','_blank')" title="Скачать"><i class="bi bi-download"></i></button>
        <button class="btn text-primary look_modal_ord" data-id-row="<?php echo $row['id'];?>" data-bs-toggle="modal" data-bs-target="#modalAddOrder" title="Просмотр"><i class="bi bi-eye"></i></button>
    </td>
    <?php    
    }
    ?>
</tr>
<?php  
    }
    ?>
    <tr class="align-middle mail_modal_nullsearch d-none" data-count-rows="<?php echo count($count_org);?>">
    	<td>Нет совпадений</td>
    	<td></td>
    	<td></td>
    	<td></td>
        <td></td>
    	<td></td>
    	<td></td>
        <td></td>
    	<td></td>
    </tr>
    <?php
    
    
}else{
   ?>
    <tr class="align-middle">
    	<td>Список заявок пуст</td>
    	<td></td>
    	<td></td>
    	<td></td>
        <td></td>
    	<td></td>
    	<td></td>
        <td></td>
    	<td></td>
    </tr>
   <?php
}
?>