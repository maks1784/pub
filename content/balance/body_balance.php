<body class="bg-light">
<?php
include "content/common/nav.php";
//echo agent_log($id,$usu);
?>

<div class="nav-scroller bg-body">
    <nav class="nav nav-underline shadow-sm mb-4">
        <div class="container mb-2">
            <div class="row mt-2">
                <div class="col-6 col-lg-auto col-xl-auto p-2">
                    <div id="reportrange" class="form-control form-control-md">
                        <i class="bi bi-calendar3"></i>&nbsp;
                        <span></span> <i class="bi bi-caret-down-fill"></i>
                    </div>
                </div>
                <div class="col-4 col-lg col-xl gx-4 p-2">
                    <select class="form-select form-select-md" id="select_filt_users">
                        <option value="all_active">Все активные агенты</option>
                        <option value="all_deactive">Все заблокированные агенты</option>
                        <?php
                        if($sts == "agent"){
                           $total_agt = agent_users($id,$usu);
                        }else if($sts == "admin"){
                            $total_agt = admin_users($id);
                        }
                        if($total_agt){
                            foreach($total_agt as $row){
                                ?>
                                <option value="<?php echo $row['id']?>"><?php echo $row['lgn']?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-4 col-lg col-xl gx-4 p-2">
                    <select class="form-select form-select-md" id="select_filt_doit">
                        <option value="0">Все действия</option>
                        <option value="Списание">Списание</option>
                        <option value="Пополнение">Пополнение</option>
                        <option value="Задолженность погашена">Задолженность погашена</option>
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
                        <span id="count_balance_row">
                        </span>
                    </div>
                    <div class="col-auto">
                        <select class="form-select form-select-sm" id="select_cnt_blc">
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
                <input type="text" class="form-control form-control-sm" id="search_balance" placeholder="Поиск записи">
            </div>
        </div>
    </div>
	<div class="row mb-2">
		<div>
			<div class="table-responsive">
				<table class="table table-hover table-stri1ped caption-top" style="text-align: center;">
					<thead>
						<tr>
							<th scope="col" style="text-align: left;">Логин</th>
                            <th scope="col">Сумма</th>
                            <th scope="col">Действие</th>
                            <th scope="col">Дата</th>
						</tr>
					</thead>
					<tbody id="balance_content">
					   
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-6 text-secondary">
			<span id="count_balance_rows"></span>
		</div>
		<div class="col-6 align-self-center">
			<nav aria-label="" class="pagination_nav">
				
			</nav>
		</div>
	</div>
</div>

<?php 
include "content/common/footer.php";
?>

</body>