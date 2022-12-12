<body class="bg-light">
    <?php
        //ini_set('display_errors',1); error_reporting(E_ALL ^E_NOTICE);
        include "modal/modal_add_orders.php";
        include "modal/modal_del_ord.php";
        include "modal/modal_not_pto.php";
        if($sts != "agent"){
            include "modal/modal_edit_status.php";
        }
        include "content/common/nav.php";
      /*  include "content/common/modal/modal_logout.php";
        include "content/common/modal/modal_regist.php";
        include "content/common/modal/modal_info.php";*/
        if(pto_not_del()){
            $modal_order="#modalAddOrder";
        }else{
            $modal_order="#notPtoModal";
        }
        
    ?>
    
    
    <div class="nav-scroller bg-body">
        <nav class="nav nav-underline shadow-sm mb-4">
            <div class="container mb-2">
                
            <div class="row mt-2">
                <div class="col-6 col-lg-auto col-xl-auto p-2 align-self-center">
                    <button type="button" id="open_modal_ord" class="btn btn-success btn-md fw-bold open_modal_add_order" style="padding: 6px 26px 6px 22px; width: 100%;" data-bs-toggle="modal" data-bs-target="<?php echo $modal_order;?>"><i class="bi bi-pencil-square" style="padding-right: 4px;"></i> Создать заявку</button>
                </div>
                <div class="col-6 col-lg-auto col-xl-auto p-2">
                    <div id="reportrange" class="form-control form-control-md">
                        <i class="bi bi-calendar3"></i>&nbsp;
                        <span></span> <i class="bi bi-caret-down-fill"></i>
                    </div>
                </div>
                <div class="col-4 col-lg col-xl gx-4 p-2">
                    <select class="form-select form-select-md" id="select_ctg_ord">
                        <option value="0">Категория</option>
                        <?php
                        $total_ctg=ctg();
                        foreach($total_ctg as $ctg){
                        ?>
                        <option <?php echo $selected_ctg;?> value="<?php echo $ctg["id"]?>" data-ctg="<?php echo $ctg["ctg"]?>" data-ctm="<?php echo $ctg["ctm"]?>"><?php echo $ctg["ctm"]?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col-4 col-lg col-xl gx-4 p-2"> 
                    <select class="form-select form-select-md" id="select_agt_ord">
                        <option value="0">Агент</option>
                        <?php
                        if($sts == "agent"){
                           $total_agt = agent_users($id,$usu);
                        }else if($sts == "admin" or $sts == "moderator"){
                            $total_agt = admin_users($id);
                        }
                        if($total_agt){
                            foreach($total_agt as $row){
                                if($row['sts'] != 'moderator'){
                                    ?>
                                    <option value="<?php echo $row['id']?>"><?php echo $row['lgn']?></option>
                                    <?php
                                }
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-4 col-lg col-xl gx-4 p-2">
                    <select class="form-select form-select-md" id="select_sts_ord">
                        <option value="0">Статус</option>
                        <option value="Принята">Принята</option>
                        <option value="Черновик">Черновик</option>
                        <option value="В работе">В работе</option>
                        <option value="Проверка">Проверка</option>
                        <option value="Готово">Готово</option>
                        <option value="Архив">Архив</option>
                    </select>
                </div>
            </div>
        </div>
        </nav>
    </div>
    
    <div class="container">
        <div class="mb-3">
            <div class="row g-0">
                <div class="col-9 text-secondary">
                    <div class="row g-2 align-items-center">
                        <div class="col-auto">
                            <span id="count_ord_row">
                            </span>
                        </div>
                        <div class="col-auto">
                            <select class="form-select form-select-sm" id="select_cnt_ord">
                                <?php
                                if($cnt == "")$sel_50='selected';
                                if($cnt == "10")$sel_10='selected';
                                if($cnt == "50")$sel_50='selected';
                                if($cnt == "100")$sel_100='selected';
                                if($cnt == "200")$sel_200='selected';
                                if($cnt == "500")$sel_500='selected';
                                if($cnt == "1000")$sel_1000='selected';
                                ?>
                            <option <?php echo $sel_10;?> value="10">10 шт.</option>    
                            <option <?php echo $sel_50;?> value="50">50 шт.</option>
                            <option <?php echo $sel_100;?> value="100">100 шт.</option>
                            <option <?php echo $sel_200;?> value="200">250 шт.</option>
                            <option <?php echo $sel_500;?> value="500">500 шт.</option>
                            <option <?php echo $sel_1000;?> value="1000">1000 шт.</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <span>
                            на странице
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col"></div>
                <div class="col-3 col-lg-3 col-xl-2">
                    <input type="text" class="form-control form-control-sm" id="ord_search" placeholder="Поиск ТС">
                </div>
            </div>
        </div>
    
    	<div class="row mb-2">
    		<div>
    			<div class="table-responsive">
    				<table class="table table-hover table-stri1ped caption-top">
    					<thead>
    						<tr>
    							<th scope="col">ТС</th>
    							<th scope="col">Г/н</th>
    							<th scope="col">VIN</th>
    							<th scope="col">Кат.</th>
                                <th scope="col">Агент</th>
    							<th scope="col" class="text-center">Статус</th>
    							<th scope="col">Создана</th>
                                <th scope="col">Проведена</th>
    							<th scope="col" class="text-center">Действия</th>
    						</tr>
    					</thead>
    					<tbody id="orders_content">
    					</tbody>
    				</table>
    			</div>
    		</div>
    	</div>
    	<div class="row">
    		<div class="col-6 text-secondary">
    			<span id="count_ord_rows"></span>
    		</div>
    		<div class="col-6 align-self-center">
    			<nav aria-label="" class="pagination_nav d-none">
    			</nav>
    		</div>
    	</div>
    </div>
    
<?php 
include "content/common/footer.php";
?>

</body>