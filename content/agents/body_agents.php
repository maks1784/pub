<body class="bg-light">
    <input type="hidden" value="<?php echo $sts;?>" id="sts_user">
<?php

include "modal/modal_add_agents.php";
include "modal/modal_del_agent.php";
include "content/common/nav.php";
?>
<div class="nav-scroller bg-body">
    <nav class="nav nav-underline shadow-sm mb-4">
        <div class="container mb-2">
        <div class="row mt-2">
            <div class="col-6 col-lg-auto col-xl-auto p-2 align-self-center">
                <button type="button" id="open_modal_agents" class="btn btn-success btn-md fw-bold" style="padding: 6px 26px 6px 22px; width: 100%;"  data-bs-toggle="modal" data-bs-target="#modalAddAgent"><i class="bi bi-person-circle" style="padding-right: 4px;"></i> Добавить агента</button>
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
                        <span id="count_users_ag">
                        
                        </span>
                    </div>
                    <!--
                    <div class="col-auto">
                        <select class="form-select form-select-sm">
                        <option selected>50 шт.</option>
                        <option value="1">100 шт.</option>
                        <option value="2">250 шт.</option>
                        <option value="3">500 шт.</option>
                        <option value="4">1000 шт.</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <span>
                        на странице
                        </span>
                    </div>
                    -->
                </div>
            </div>
            <div class="col"></div>
            <div class="col-3 col-lg-3 col-xl-2">
                <input type="text" class="form-control form-control-sm" id="search_agent" placeholder="Поиск агента">
            </div>
        </div>
    </div>
	<div class="row mb-2">
		<div>
			<div class="table-responsive">
				<table class="table table-hover table-stri1ped caption-top">
					<thead>
						<tr>
							<th scope="col">Логин</th>
							<th scope="col">Баланс</th>
                            <th scope="col">A</th>
                            <th scope="col">B</th>
                            <th scope="col">C</th>
                            <th scope="col">E</th>
                            <th scope="col">Заявок</th>
                            <th scope="col">Создан</th>
							<th scope="col">Последний вход</th>
							<th scope="col" class="text-center">Действия</th>
						</tr>
					</thead>
					<tbody id="agents_content">
					    <!--
						<tr class="align-middle" style="display: 1none;">
							<td>AVitalik777</td>
							<td>&minus;45250 руб.</td>
                            <td>150</td>
                            <td>150</td>
                            <td>500</td>
                            <td>600</td>
                            <td>3628</td>
							<td>18.09.2022 12:32:30</td>
							<td>Не был</td>
							<td><a href="#" title="Редактировать"><i class="bi bi-pencil pe-3"></i></a></td>
						</tr>
                        
                        <tr class="align-middle table-danger" style="display: 1none;">
							<td>Martin81</td>
							<td>&minus;3250 руб.</td>
                            <td>175</td>
                            <td>190</td>
                            <td>320</td>
                            <td>900</td>
                            <td>176</td>
							<td>18.09.2022 12:32:30</td>
							<td>19.09.2022 15:32:30</td>
							<td><a href="#" title="Редактировать"><i class="bi bi-pencil pe-3"></i></a> <span class="text-danger"><i class="bi bi-lock-fill danger"></i></span></td>
						</tr>
						-->
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<!--
	<div class="row">
		<div class="col-6 text-secondary">
			<span>Показано c 1 по 50 из 73 агентов</span>
		</div>
		<div class="col-6 align-self-center">
			<nav aria-label="">
				<ul class="pagination pagination-sm justify-content-end">
					<li class="page-item disabled">
						<a class="page-link">Предыдущая</a>
					</li>
					<li class="page-item"><a class="page-link" href="#">1</a></li>
					<li class="page-item"><a class="page-link" href="#">2</a></li>
					<li class="page-item"><a class="page-link" href="#">3</a></li>
					<li class="page-item">
						<a class="page-link" href="#">Следующая</a>
					</li>
				</ul>
			</nav>
		</div>
	</div>
	-->
</div>
<?php 
include "content/common/footer.php";
?>

</body>