<?php
//ini_set('display_errors',1); error_reporting(E_ALL ^E_NOTICE);
require "php/db.php";
//$start_period = htmlspecialchars($_POST['st']);//Пример
$total_users_pto = pto();
if($total_users_pto){
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //СПИСОК ПТО
    foreach($total_users_pto as $row){
        $class_dang = "";
        $cl_dtn="del_modal_pto";
        $lock_icon = "bi-unlock";
        $tx_color = "text-primary";
        $tl_btn = "Заблокировать";
        if($row['del'] == "1"){
            $class_dang = "table-danger";
            $cl_dtn = "rec_modal_pto";
            $lock_icon = "bi-lock-fill";
            $tx_color = "text-danger";
            $tl_btn = "Разблокировать";
        }
        ?>
        <tr class="align-middle <?php echo $class_dang;?>">
    		<td style="text-align: left;"><?php echo $row['name']?></td>
            <td><?php echo $row['price_a']?></td>
    		<td><?php echo $row['price_b']?></td>
            <td><?php echo $row['price_c']?></td>
            <td><?php echo $row['price_e']?></td>
            <td>
                <button title="Редактировать" data-id-row="<?php echo $row['id']?>" data-bs-toggle="modal" data-bs-target="#modalAddPto" class="btn text-primary edit_modal_pto"><i class="bi bi-pencil"></i></button>
                <button title="<?php echo $tl_btn;?>" data-id-row="<?php echo $row['id']?>" data-bs-toggle="modal" data-bs-target="#delPtoModal" class="btn <?php echo $tx_color;?> <?php echo $cl_dtn;?>"><i class="bi <?php echo $lock_icon;?>"></i></button>
            </td>
    	</tr>
        <?php
    }
    //СПИСОК ПТО

}else{
    ?>
    <tr class="align-middle">
        <td style="text-align: left;">Нет данных</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <?php
}
?>