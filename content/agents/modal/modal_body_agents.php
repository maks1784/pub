<?php
require "php/db.php";
$sm = htmlspecialchars($_POST['sm']);
$ldl = "Добавить агента";
$agt_id="";
$sel_sts_ag="";
$sel_sts_md="";
$sel_sts_ad="";
$lgn_us="";
$d_none="";
$d_login="";
$d_none_pay='d-none';
$db_pay='';
$pto_edit_dis = false;
$total_users_pto = pto();
if($sts == "agent"){
    $total_users_ptous = ptous($id);
}
if($sm == 'edit'){
    
    $agt_id = htmlspecialchars($_POST['ai']);
    if($agt_id == $id) $pto_edit_dis = true;
    $users = R::load("users",$agt_id);
    $db_sts = $users->sts;
    if($sts == 'admin'){
        if($db_sts == "agent"){$ldl = "Изменить агента";$sel_sts_ag='selected';}
        if($db_sts == "moderator"){$ldl = "Изменить модератора";$sel_sts_md='selected';}
        if($db_sts == "admin"){$ldl = "Изменить админа";$sel_sts_ad='selected';}
    }
    if($db_sts == "moderator"){
        $d_none = "d-none";
        $d_none_pay = "";
        $db_pay = $users->pay;
    }
    $lgn_us = $users->lgn; 
    $d_login="disabled";
    $total_users_ptous = ptous($agt_id);
}

?>
<div class="modal-header mb-2 bg-dark text-light">
    <h5 class="modal-title" id="staticBackdropLabel"><?php echo $ldl;?></h5>
        <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
<div class="modal-body">
    <div class="row g-3 mb-3">
        <?php
        if($sts == 'admin' and $sm == 'add'){
        ?>  
        <div class="col-md-12">
            <div class="form-floating">
                <select class="form-select bg-light" id="status_user_select">
                    <option <?php echo $sel_sts_ag;?> value="agent" selected>Агент</option>
                    <option <?php echo $sel_sts_md;?> value="moderator">Модератор</option>
                    <option <?php echo $sel_sts_ad;?> value="admin">Админ</option>
                </select>
                <label for="status_user_select">Статус пользователя</label>
            </div>
        </div>
        <?php
        $lbl_row_input = "";
        }else if($sts == 'agent'){  $lbl_row_input = " агента";
        ?>
        <input type="hidden" id="status_user_select" value="agent">
        <?php
        }else if($sts == 'admin' and $db_sts == 'moderator' and $sm == 'edit'){
            ?>
            <input type="hidden" id="status_user_select" value="moderator">
            <?php
        }else if($sts == 'admin' and $db_sts == 'agent' and $sm == 'edit'){
            ?>
            <input type="hidden" id="status_user_select" value="agent">
            <?php
        }
        ?>
        <div class="col-md-6">
            <div class="form-floating">
                <input type="text" class="form-control" <?php echo $d_login;?> id="login_new_user" placeholder="Логин<?php echo $lbl_row_input;?>" value="<?php echo $lgn_us;?>">
                <label for="floatingInput">Логин<?php echo $lbl_row_input;?></label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                <input type="password" class="form-control" id="pass_new_user" placeholder="Пароль<?php echo $lbl_row_input;?>" value="">
                <label for="floatingInput">Пароль<?php echo $lbl_row_input;?></label>
            </div>
        </div>
    </div>
    
    <div class="row <?php echo $d_none_pay;?>" id="moder_pay_content">
        <div class="col-md-12">
            <div class="form-floating">
                <input type="text" class="form-control" id="pay_new_user" placeholder="З/П Модератора,  руб." value="<?php echo $db_pay;?>">
                <label for="floatingInput">З/П Модератора, руб.</label>
            </div>
        </div>
    </div>
    <?php
    if(!$pto_edit_dis){  
    ?>
    <div class="row <?php echo $d_none;?>" id="table_pto_content">
    		<div class="table-responsive">
    			<table class="table table-hover table-stri1ped caption-top" style="text-align: center; table-layout : fixed;" width="766">
    				<thead>
    					<tr>
    						<th scope="col" style="text-align: left; table-layout : fixed" width="456">Название</th>
                            <th scope="col" width="76">A</th>
                            <th scope="col" width="76">B</th>
                            <th scope="col" width="76">C</th>
                            <th scope="col" width="76">E</th>
    					</tr>
    				</thead>
    				<tbody id="pto_content_form">
    				    <?php
                            //$start_period = htmlspecialchars($_POST['st']);//Пример
                            
                            foreach($total_users_pto as $row){
                                if($sm == 'add'){
                                    if($sts == "admin"){
                                        $pra=$row['price_a'];
                                        $prb=$row['price_b'];
                                        $prc=$row['price_c'];
                                        $pre=$row['price_e'];
                                    }else if($sts == "agent"){
                                        foreach($total_users_ptous as $ptu){
                                            if($ptu['pto_id'] == $row['id']){
                                                $pra=$ptu['price_a'];
                                                $prb=$ptu['price_b'];
                                                $prc=$ptu['price_c'];
                                                $pre=$ptu['price_e'];
                                            }
                                        }
                                    }
                                }else if($sm == 'edit'){
                                    if($db_sts == "admin"){
                                        $pra=$row['price_a'];
                                        $prb=$row['price_b'];
                                        $prc=$row['price_c'];
                                        $pre=$row['price_e'];
                                    }else if($db_sts == "agent"){
                                        foreach($total_users_ptous as $ptu){
                                            if($ptu['pto_id'] == $row['id']){
                                                $pra=$ptu['price_a'];
                                                $prb=$ptu['price_b'];
                                                $prc=$ptu['price_c'];
                                                $pre=$ptu['price_e'];
                                            }
                                        }
                                    }
                                }
                                if($pra == "0") $dis_a = 'disabled';else $dis_a='';
                                if($prb == "0") $dis_b = 'disabled';else $dis_b='';
                                if($prc == "0") $dis_c = 'disabled';else $dis_c='';
                                if($pre == "0") $dis_e = 'disabled';else $dis_e='';
                                ?>
                                <tr class="align-middle inp_pto_ctg" data-pto-id="<?php echo $row['id']?>" >
                            		<td style="text-align: left; table-layout : fixed;"><?php echo $row['name']?></td>
                                    <td><input type="text" <?php echo $dis_a;?> data-price="<?php echo $pra;?>" class="form-control inp_price_a" value="<?php echo $pra;?>"></td>
                            		<td><input type="text" <?php echo $dis_b;?> data-price="<?php echo $prb;?>" class="form-control inp_price_b" value="<?php echo $prb;?>"></td>
                                    <td><input type="text" <?php echo $dis_c;?> data-price="<?php echo $prc;?>" class="form-control inp_price_c" value="<?php echo $prc;?>"></td>
                                    <td><input type="text" <?php echo $dis_e;?> data-price="<?php echo $pre;?>" class="form-control inp_price_e" value="<?php echo $pre;?>"></td>
                            	</tr>
                                <?php
                            }
                            
                            ?>
    				</tbody>
                </table>
    		</div>
    </div>
    <?php
    } 
    ?>
</div>
<div class="modal-footer">
    <p class="mx-3" id="info_form_ag">
        <span class="text-danger d-none login_error_01">Укажите логин!</span>
        <span class="text-danger d-none login_error_02">Логин занят!</span>
        <span class="text-danger d-none str_error_01">Укажите пароль!</span>
        
        <span class="text-danger d-none pr_a_error_01">Укажите все значения кат. A в рублях!</span>
        <span class="text-danger d-none pr_b_error_01">Укажите все значения кат. B в рублях!</span>
        <span class="text-danger d-none pr_c_error_01">Укажите все значения кат. C в рублях!</span>
        <span class="text-danger d-none pr_e_error_01">Укажите все значения кат. E в рублях!</span>
        
        <span class="text-danger d-none pr_a_error_02">Указано слишком низкая стоимость в одной из строк кат. A!</span>
        <span class="text-danger d-none pr_b_error_02">Указано слишком низкая стоимость в одной из строк кат. B!</span>
        <span class="text-danger d-none pr_c_error_02">Указано слишком низкая стоимость в одной из строк кат. C!</span>
        <span class="text-danger d-none pr_e_error_02">Указано слишком низкая стоимость в одной из строк кат. E!</span>
        
        <span class="text-danger d-none pay_error_01">Укажите З/П Модератора в рублях!</span>
        
    </p>

    <button type="button" class="btn btn-success" id="submit_add_user">Сохранить</button>
    <input type="hidden" id="id_form_submit" value="<?php echo $agt_id;?>">
</div>