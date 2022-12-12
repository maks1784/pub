<?php
//ini_set('display_errors',1); error_reporting(E_ALL ^E_NOTICE);
require "php/db.php";
$start_period = htmlspecialchars($_POST['st']);
$end_period = htmlspecialchars($_POST['en']);
$filt_users = htmlspecialchars($_POST['us']);
$filt_pto = htmlspecialchars($_POST['to']);
$exc = false;
$sts_exc="";//Для отчёта в функцию выборки по финансам (функция общая для Финансов и для отчета в Excel)
if($sts == "agent" or $sts == "moderator"){
    $total_users_finance = agent_finance($id,$usu,$start_period,$end_period,$filt_users,$filt_pto,$exc,$sts_exc);
}else if($sts == "admin"){
    $total_users_finance = admin_finance_new($id,$usu,$start_period,$end_period,$filt_users,$filt_pto,$exc,$sts_exc);
}
if($total_users_finance){
    //echo "<tr><td>".agent_exl("41","38",$start_period,$end_period,$filt_pto)."</td></tr>";
    foreach($total_users_finance as $row){
        
        if($row['usu'] != ''){
            //Старая для вложенных подаегнтов
            if(count($total_users_finance) > 1){
                $podag_btn='&emsp;<button title="Просмотр подагентов" data-usuid = "'.$row['usu_id'].'" data-id-row="'.$row['id'].'" data-usu="'.$row['usu'].'" data-gen-usu="'.agents_agents_users($row['usu_id'],$row['id']).'" class="btn btn-sm text-primary open_pdagent_btn"><i class="bi bi-info-circle"></i></button>';
            }else{
                $podag_btn='';
            }
            //////
            $podag_btn='';//Новый вариант, пока оставляем без доп кнопок +
            $usu_ids = $row['usu'];
            
        }else{
            $podag_btn='';
            $usu_ids = '';
        }
        if($row['id'] == $id){
            $login_row = "Я (".$row['lgn'].")";
            
            $sum_a_f=sum_a_filt_me($row['id'],$row['usu_id'],$start_period,$end_period,$filt_pto);
            $sum_b_f=sum_b_filt_me($row['id'],$row['usu_id'],$start_period,$end_period,$filt_pto);
            $sum_c_f=sum_c_filt_me($row['id'],$row['usu_id'],$start_period,$end_period,$filt_pto);
            $sum_e_f=sum_e_filt_me($row['id'],$row['usu_id'],$start_period,$end_period,$filt_pto);
            
            $sum_ord_f = sum_ord_filt_me($row['id'],$row['usu_id'],$start_period,$end_period,$filt_pto);
            
            //Доход
            $sum_income = 0;
            
            //Расход
            $sum_expense = sum_orders_val($row['sts'],$row['id'],$row['usu_id'],$start_period,$end_period,$filt_pto);
            $sum_profit = 0;
            
            
            
        }else{
            $login_row = $row['lgn'];
            
            $sum_a_f=sum_a_filt($row['id'],$row['usu_id'],$start_period,$end_period,$filt_pto);
            $sum_b_f=sum_b_filt($row['id'],$row['usu_id'],$start_period,$end_period,$filt_pto);
            $sum_c_f=sum_c_filt($row['id'],$row['usu_id'],$start_period,$end_period,$filt_pto);
            $sum_e_f=sum_e_filt($row['id'],$row['usu_id'],$start_period,$end_period,$filt_pto);
            $sum_ord_f = sum_ord_filt($row['id'],$row['usu_id'],$start_period,$end_period,$filt_pto);
            
            
            
            //Доход
            if($row['sts'] == 'admin'){
                
            }else if($row['sts'] == 'agent'){
                $sum_income = sum_orders_val($row['sts'],$row['id'],$row['usu_id'],$start_period,$end_period,$filt_pto);
                $sum_expense = sum_pto_user($id,$sts,$row['id'],$row['usu_id'],$start_period,$end_period,$filt_pto);
            }
            $sum_profit = $sum_income - $sum_expense;
        }
        ?>
        <tr class="align-middle tr_row_agent" id="tr_agent-<?php echo $row['id']?>" data-id-row="<?php echo $row['id']?>" data-usu="<?php echo $usu_ids?>">
        	<td class="search_text_input" data-lgn="<?php echo $row['lgn']?>" style="text-align: left;"><i><?php echo $login_row.$podag_btn;?></i></td>
        	<td class="sum_ctg_a"><?php echo $sum_a_f;?></td>
            <td class="sum_ctg_b"><?php echo $sum_b_f;?></td>
            <td class="sum_ctg_c"><?php echo $sum_c_f;?></td>
            <td class="sum_ctg_d"><?php echo $sum_e_f;?></td>
            <td class="table-secondary sum_order"><?php echo $sum_ord_f;?></td>
        	<td class="table-warning sum_income"><?php echo $sum_income;?></td>
        	<td class="table-danger sum_expense"><?php echo $sum_expense;?></td>
        	<td class="table-success sum_profiti"><?php echo $sum_profit;?></td>
            <td class="text-end sum_balance" data-balance="<?php echo $row['val'];?>"><nobr><?php echo $row['val'];?> руб.</nobr> 
            <?php
            
                if($row['id'] != $id or $sts == "admin"){
                ?>
                    <button type="button" data-login-user="<?php echo $row['lgn'];?>" data-id-user="<?php echo $row['id'];?>" class="btn btn-outline-primary btn-sm add_finance_btn" data-bs-toggle="modal" data-bs-target="#modalAddFinance" style="margin-left: 10px;">
                        <i class="bi bi-plus-circle"></i>
                    </button>
                <?php
                }
            
            ?>
            </td>
            <td>
                <button class="btn btn-sm text-primary excel_fin" data-sts="<?php echo $row['sts'];?>" value="<?php echo $row['id'];?>"><i class="bi bi-filetype-xls"></i></button>
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
	<td></td>
</tr>
<?php
}else{
    ?>
<tr class="align-middle mail_modal_nullsearch text-start">
	<td>Нет данных</td>
	<td></td>
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