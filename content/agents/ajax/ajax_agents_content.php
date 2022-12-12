<?php
//ini_set('display_errors',1); error_reporting(E_ALL ^E_NOTICE);
require "php/db.php";
$search = htmlspecialchars($_POST['sa']);
if($sts == "agent"){
   $total_agt = agent_users($id,$usu);
}else if($sts == "admin"){
   $total_agt = admin_users($id);
}
if($total_agt){
    //echo "<tr><td>".$total_agt."</td></tr>";
    foreach($total_agt as $row){
        if($row['last_login'] == "0000-00-00 00:00:00"){
            $last_login = "Не был";
        }else{
            $last_login = date("d.m.Y H:i:s", strtotime($row['last_login']));
        }
        //Старая для вложенных подаегнтов
        if($row['usu'] != ''){
            $podag_btn='&emsp;<button title="Просмотр подагентов" data-usuid = "'.$row['usu_id'].'" data-id-row="'.$row['id'].'" data-usu="'.$row['usu'].'" data-gen-usu="'.agents_agents_users($row['usu_id'],$row['id']).'" class="btn text-primary open_pdagent_btn"><i class="bi bi-plus"></i></button>';
            $usu_ids = $row['usu'];
        }else{
            $podag_btn='';
            $usu_ids = '';
        }
        //////
        $podag_btn='';//Новый вариант, пока оставляем без доп кнопок +
        if($row['id'] == $id){
            $login_row = "Я (".$row['lgn'].")";
            $sum_a_f=sum_a_me($row['id'],$row['usu_id']);
            $sum_b_f=sum_b_me($row['id'],$row['usu_id']);
            $sum_c_f=sum_c_me($row['id'],$row['usu_id']);
            $sum_e_f=sum_e_me($row['id'],$row['usu_id']);
            $sum_ord_f = sum_ord_me($id,$usu_id);
        }else{
            $login_row = $row['lgn'];
            if($row['sts'] == 'agent'){
                $sum_a_f=sum_a($row['id'],$row['usu_id']);
                $sum_b_f=sum_b($row['id'],$row['usu_id']);
                $sum_c_f=sum_c($row['id'],$row['usu_id']);
                $sum_e_f=sum_e($row['id'],$row['usu_id']);
                $sum_ord_f = sum_ord($row['id'],$usu_id);
            }else if($row['sts'] == 'moderator'){
                $sum_a_f=sum_a_mod($row['id'],$row['usu_id']);
                $sum_b_f=sum_b_mod($row['id'],$row['usu_id']);
                $sum_c_f=sum_c_mod($row['id'],$row['usu_id']);
                $sum_e_f=sum_e_mod($row['id'],$row['usu_id']);
                $sum_ord_f = sum_ord_mod($row['id']);
            }
        }
        $class_dang = "";
        $comment_del="";
        $cl_dtn="del_modal_agn";
        $lock_icon = "bi-unlock";
        $tx_color = "text-primary";
        $tl_btn = "Заблокировать";
        if($row['del'] == "1"){
            $class_dang = "table-danger";
            $cl_dtn = "rec_modal_agn";
            $lock_icon = "bi-lock-fill";
            $tx_color = "text-danger";
            $comment_del=$row['del_comment'];
            $tl_btn = "Разблокировать. Причина блокировки: ".$row['del_comment'];
        }
?>
    <tr class="align-middle <?php echo $class_dang;?> tr_row_agent usu_id-<?php echo $row['usu_id']?>" id="tr_agent-<?php echo $row['id']?>" data-id-row="<?php echo $row['id']?>" data-usu="<?php echo $usu_ids?>">
    	<td class="search_text_input" data-lgn="<?php echo $row['lgn']?>"><?php echo $login_row.$podag_btn?></td>
    	<td><?php echo $row['val']?> руб.</td>
        <td><?php echo $sum_a_f;?></td>
        <td><?php echo $sum_b_f;?></td>
        <td><?php echo $sum_c_f;?></td>
        <td><?php echo $sum_e_f;?></td>
        <td><?php echo $sum_ord_f;?></td>
    	<td><?php echo date("d.m.Y H:i:s", strtotime($row['date_reg']));?></td>
    	<td><?php echo $last_login;?></td>
    	<td class="text-center">
    	    <button title="Редактировать" data-id-row="<?php echo $row['id']?>" data-lg-row="<?php echo $row['lgn']?>" data-bs-toggle="modal" data-bs-target="#modalAddAgent" class="btn text-primary edit_modal_agents"><i class="bi bi-pencil"></i></button>
        <?php
        if($row['id'] != $id){
            if($comment_del != ''){
        ?>   
            <button title="<?php echo $tl_btn;?>" data-del-comm="<?php echo $comment_del?>" data-id-row="<?php echo $row['id']?>" data-lg-row="<?php echo $row['lgn']?>" data-bs-toggle="modal" data-bs-target="#modalDelAgent" class="btn <?php echo $tx_color;?> <?php echo $cl_dtn;?>"><i class="bi <?php echo $lock_icon;?>"></i></button>
    	<?php
            }else if($row['del'] == "1"){
                ?>
                <div class="btn text-danger"><i class="bi bi-arrow-up"></i></div>
                <?php
            }else{
                ?>   
                    <button title="<?php echo $tl_btn;?>" data-del-comm="<?php echo $comment_del?>" data-id-row="<?php echo $row['id']?>" data-lg-row="<?php echo $row['lgn']?>" data-bs-toggle="modal" data-bs-target="#modalDelAgent" class="btn <?php echo $tx_color;?> <?php echo $cl_dtn;?>"><i class="bi <?php echo $lock_icon;?>"></i></button>
            	<?php
            }
        }
    	?>
    	</td>
    </tr>
<?php
    }
     ?>
    <tr class="align-middle mail_modal_nullsearch d-none">
    	<td>Нет совпадений</td>
    	<td></td>
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
	<td>нет данных</td>
	<td></td>
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