<body class="bg-light">
<?php
include "modal/modal_add_pto.php";
include "modal/modal_del_pto.php";
include "content/common/nav.php";
?>

<div class="nav-scroller bg-body">
    <nav class="nav nav-underline shadow-sm mb-4">
        <div class="container mb-2">
            <div class="row mt-2">
                <div class="col-6 col-lg-auto col-xl-auto p-2 align-self-center">
                    <button type="button" id="open_modal_pto" class="btn btn-success btn-md fw-bold" style="padding: 6px 26px 6px 22px; width: 100%;" data-bs-toggle="modal" data-bs-target="#modalAddPto"><i class="bi bi-ui-checks" style="padding-right: 4px;"></i> Добавить ПТО</button>
                </div>
            </div>
        </div>
    </nav>
</div>

<div class="container">
    
    <h3>Профиль администратора</h3>
    
    <div class="mb-3">
        <h4>Доступные ПТО и цены</h4>
    </div>
	<div class="row mb-2">
		<div>
			<div class="table-responsive">
				<table class="table table-hover table-stri1ped caption-top" style="text-align: center;">
					<thead>
						<tr>
							<th scope="col" style="text-align: left;">Название</th>
                            <th scope="col">A</th>
                            <th scope="col">B</th>
                            <th scope="col">C</th>
                            <th scope="col">E</th>
                            <th scope="col">Действия</th>
						</tr>
					</thead>
					<tbody id="pto_content"></tbody>
                </table>
			</div>
		</div>
	</div>
</div>

<?php 
include "content/common/footer.php";
?>

</body>