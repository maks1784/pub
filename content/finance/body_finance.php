<body class="bg-light">
    <input type="hidden" value="<?php echo $sts;?>" id="sts_user">
<?php
include "modal/modal_add_finance.php";
if($sts == 'admin'){
    include "modal/modal_info.php";
}
include "content/common/nav.php";
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
                    <select class="form-select form-select-md" id="select_filt_pto">
                        <option value="0">Все ПТО</option>
                        <?php
                        $total_users_pto = pto();
                        if($total_users_pto){
                            foreach($total_users_pto as $row){
                        ?>
                        <option value="<?php echo $row['id']?>"><?php echo $row['name']?></option>
                        <?php
                            }
                        }
                        ?>
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
                        <span id="count_users">
                        
                        </span>
                    </div>
                    <!--<div class="col-auto">
                        <select class="form-select form-select-sm" id="select_filt_count_rows">
                            <option value="1">50 шт.</option>
                            <option selected>100 шт.</option>
                            <option value="2">250 шт.</option>
                            <option value="3">500 шт.</option>
                            <option value="4">1000 шт.</option>
                        </select>
                    </div
                    <div class="col-auto">
                        <span>
                        на странице
                        </span>
                    </div>>-->
                </div>
            </div>
            <div class="col"></div>
            <div class="col-3 col-lg-3 col-xl-2">
                <input type="text" class="form-control form-control-sm" id="search_filt_row" placeholder="Поиск агента">
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
                            <th scope="col">A</th>
                            <th scope="col">B</th>
                            <th scope="col">C</th>
                            <th scope="col">E</th>
                            <th scope="col" class="table-secondary">Заявок</th>
                            <th scope="col" class="table-warning">Доход</th>
							<th scope="col" class="table-danger">Расход</th>
                            <th scope="col" class="table-success">Прибыль</th>
							<th scope="col" class="text-end">Баланс</th>
                            <th scope="col">Отчет</th>
						</tr>
					</thead>
					<tbody id="finance_content">
                        <!--
						<tr class="align-middle" style="display: 1none;">
							<td style="text-align: left;"><i>Я (TestUsername)</i></td>
							<td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td class="table-secondary">4</td>
							<td class="table-warning">8000</td>
							<td class="table-danger">8000</td>
							<td class="table-success">0</td>
                            <td><nobr>0 руб.</nobr> <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdrop" style="margin-left: 10px;"><i class="bi bi-plus-circle"></i></button>
                            </td>
                            <td><a href="#"><i class="bi bi-filetype-xls"></i></a></td>
						</tr>
                        
                        <tr class="align-middle" style="display: 1none;">
							<td style="text-align: left;">AVitalik777</td>
							<td>4</td>
                            <td>162</td>
                            <td>51</td>
                            <td>45</td>
                            <td class="table-secondary">269</td>
							<td class="table-warning">285640</td>
							<td class="table-danger">184500</td>
							<td class="table-success">101140</td>
                            <td><nobr>0 руб.</nobr> &nbsp;<button type="button" class="btn btn-outline-primary btn-sm" style="margin-left: 10px;"><i class="bi bi-plus-circle"></i></button></td>
                            <td><a href="#"><i class="bi bi-filetype-xls"></i></a></td>
						</tr>
                        
                        <tr class="align-middle" style="display: 1none;">
							<td style="text-align: left;">GlobusGurm</td>
							<td>23</td>
                            <td>40</td>
                            <td>91</td>
                            <td>205</td>
                            <td class="table-secondary">359</td>
							<td class="table-warning">37820</td>
							<td class="table-danger">14300</td>
							<td class="table-success">23520</td>
                            <td><nobr>&minus;500 руб.</nobr> &nbsp;<button type="button" class="btn btn-outline-primary btn-sm" style="margin-left: 10px;"><i class="bi bi-plus-circle"></i></button></td>
                            <td><a href="#"><i class="bi bi-filetype-xls"></i></a></td>
						</tr>
                        <tr class="align-middle" style="display: 1none;">
							<td style="text-align: left;">Kredbnk</td>
							<td>0</td>
                            <td>25</td>
                            <td>13</td>
                            <td>6</td>
                            <td class="table-secondary">44</td>
							<td class="table-warning">30800</td>
							<td class="table-danger">16280</td>
							<td class="table-success">14520</td>
                            <td><nobr>30 руб.</nobr> &nbsp;<button type="button" class="btn btn-outline-primary btn-sm" style="margin-left: 10px;"><i class="bi bi-plus-circle"></i></button></td>
                            <td><a href="#"><i class="bi bi-filetype-xls"></i></a></td>
						</tr>-->
					</tbody>
                    
                    <tfoot>
                    <tr>
                        <th style="text-align: left;">Итого</th>
                        <th id="sum_ctg_a"></th>
                        <th id="sum_ctg_b"></th>
                        <th id="sum_ctg_c"></th>
                        <th id="sum_ctg_d"></th>
                        <th id="sum_order" class="table-secondary"></th>
                        <th id="sum_income" class="table-warning"></th>
                        <th id="sum_expense" class="table-danger"></th>
                        <th id="sum_profiti" class="table-success"></th>
                        <th id="sum_balance" class="text-end"></th>
                        <th><button class="btn btn-sm text-primary excel_fin" data-sts="<?php echo $sts;?>" value=""><i class="bi bi-filetype-xls"></i></button></th>
                    </tr>
                    </tfoot>
                    
				</table>
                
                
                
                
                
			</div>
		</div>
	</div>
	<div class="row">
	<!--<div class="col-6 text-secondary">
			<span>Показано c 1 по 12 из 12 агентов</span>
		</div>
		<div class="col-6 align-self-center">
			<nav aria-label="">
				<ul class="pagination pagination-sm justify-content-end">
					<li class="page-item disabled">
						<a class="page-link">Предыдущая</a>
					</li>
					<li class="page-item active"><a class="page-link" href="#">1</a></li>
					<li class="page-item"><a class="page-link" href="#">2</a></li>
					<li class="page-item"><a class="page-link" href="#">3</a></li>
					<li class="page-item">
						<a class="page-link" href="#">Следующая</a>
					</li>
				</ul>
			</nav>
		</div>-->
	</div>
</div>
<?php 
include "content/common/footer.php";
?>

</body>