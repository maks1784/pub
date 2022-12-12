<?php
if(isset($_SESSION['logged_user'])){
    $activeBtnOrders='';$activeBtnAgents='';$activeBtnFinance='';
    if($page == 'orders')$activeBtnOrders = "active";
    else if($page == 'agents')$activeBtnAgents = "active";
    else if($page == 'finance')$activeBtnFinance = "active";
    else if($page == 'pto')$activeBtnPto = "active";
    include "content/common/modal/modal_logout.php";
}
?>
<nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top" aria-label="Fourth navbar example">
	<div class="container">
		<a class="navbar-brand mb-0 h1" href="/" style="padding: 0 26px 0 0;">🍀 Example-TO.ru</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarsExample04">
			<ul class="navbar-nav me-auto mb-2 mb-lg-0">
			    <?php
			    if($sts == 'admin'){
			        ?>
			        <li class="nav-item" style="padding-right: 12px;">
    					<a class="nav-link <?php echo $activeBtnPto;?>" aria-current="page" href="/pto"><i class="bi bi-list-check" style="padding-right: 4px;"></i> ПТО</a>
    				</li>
			        <?php
			    }
			    ?>
				<li class="nav-item" style="padding-right: 12px;">
					<a class="nav-link <?php echo $activeBtnOrders;?>" aria-current="page" href="/orders"><i class="bi bi-journals" style="padding-right: 4px;"></i> Заявки</a>
				</li>
				<?php
			    if($sts != 'moderator'){
			        ?>
				<li class="nav-item" style="padding-right: 12px;">
					<a class="nav-link <?php echo $activeBtnAgents;?>" href="/agents"><i class="bi bi-diagram-3-fill" style="padding-right: 4px;"></i> Агенты</a>
				</li>
				<?php
			    }
			    ?>
				<li class="nav-item">
					<a class="nav-link <?php echo $activeBtnFinance;?>" href="/finance"><i class="bi bi-wallet-fill" style="padding-right: 4px;"></i> Финансы</a>
				</li>
			</ul>
			<div class="navbar-nav me-auto mb-2 mb-lg-0">
				<a class="nav-link text-light <?php echo $activeBtnBalance;?>" href="/balance">
				     <i class="bi bi-credit-card-fill" style="padding-right: 4px;"></i> <?php echo $val;?> руб.
				</a>
			</div>
			<ul class="navbar-nav mb-2 mb-lg-0">
				<li class="nav-item" style="padding-right: 10px;">
					<p class="nav-link m-0">
					    <i class="bi bi-person-circle" style="padding-right: 2px;"></i> <?php echo $lgn;?>
					</p>
				</li>
				<li class="nav-item" data-bs-toggle="modal" data-bs-target="#logoutModal">
					<p class="nav-link m-0" style="cursor:pointer;">
					    <i class="bi bi-box-arrow-right"></i>
					</p>
                </li>
			</ul>
		</div>
	</div>
</nav>