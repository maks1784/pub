<?php
ini_set('display_errors',1); error_reporting(E_ALL ^E_NOTICE);
require "php/db.php";
$start_period = htmlspecialchars($_POST['st']);
$end_period = htmlspecialchars($_POST['en']);
$filt_users = htmlspecialchars($_POST['us']);
$filt_di = htmlspecialchars($_POST['di']);
$change_pages = htmlspecialchars($_POST['pp']);
$lbl_count=false;
if($sts == "agent"){
    $total_log = agent_log($id,$usu_id,$cnt,$start_period,$end_period,$filt_users,$filt_di,$lbl_count,$change_pages);
    $lbl_count=true;
    $count_log = agent_log($id,$usu_id,$cnt,$start_period,$end_period,$filt_users,$filt_di,$lbl_count,$change_pages);
    
}else if($sts == "admin"){
    $total_log = admin_log($cnt,$start_period,$end_period,$filt_users,$filt_di,$lbl_count,$change_pages);
    $lbl_count=true;
    $count_log = admin_log($cnt,$start_period,$end_period,$filt_users,$filt_di,$lbl_count,$change_pages);
}

if($total_log){
   // echo "<tr><td>".$total_log."</td></tr>";
    foreach($total_log as $row){
        
        if($sts == 'agent'){
            if($row['from_users_id'] == $id){
                $login_row = "Я (".$row['from_users_login'].") &rarr; ".$row['to_users_login'];
            }else{
                $login_row = "Руководитель &rarr; ".$row['to_users_login'];
            }
            if($row['to_users_id'] == $id){
                $login_row = "Руководитель";
            }
        }else if ($sts == 'admin'){
            
            if($row['from_users_id'] == $id){
                $login_row = "Я (".$row['from_users_login'].") &rarr; ".$row['to_users_login'];
            }else{
                $login_row = $row['from_users_login']." &rarr; ".$row['to_users_login'];
            }
            if($row['to_users_id'] == $row['from_users_id']){
                $login_row = "Я (".$row['from_users_login'].") &rarr; Я (".$row['to_users_login'].")";
            }  
        }
        if($row['action'] == "Задолженность погашена"){
            $table_color = "table-primary";
            $value_row = "";
        }else if($row['action'] == "Пополнение"){
            $table_color = "table-success";
            $value_row = "+".$row['value'];
        }else if($row['action'] == "Списание"){
            $table_color = "table-danger";
            $value_row = $row['value'];
        }else if(mb_substr($row['action'], 0, 3) == "Изм"){
            $table_color = "table-warning";
            $value_row = '';
        }else{
            $table_color = "table-danger";
            $value_row = $row['value'];
        }
        ?>
         <tr class="align-middle <?php echo $table_color;?> balance_tr_row">
			<td class="search_text_input" style="text-align: left;"><i><?php echo $login_row. " " .$row['id']?></i></td>
            <td><?php echo $value_row;?></td>
			<td><?php echo $row['action'];?></td>
            <td><?php echo date("d.m.Y H:i:s",strtotime($row['date_log']));?></td>
		</tr>
        <?php
    }
    ?>
        <tr class="align-middle text-start mail_modal_nullsearch d-none" data-count-rows="<?php echo $count_log;?>">
        	<td>Нет совпадений</td>
        	<td></td>
        	<td></td>
        	<td></td>
        </tr>
    <?php
}else{
?>
<tr class="align-middle text-start">
	<td style="text-align: left;">Нет данных</td>
    <td></td>
	<td></td>
    <td></td>
</tr>
<?php
}
?>