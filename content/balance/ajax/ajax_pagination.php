<?php
//ini_set('display_errors',1); error_reporting(E_ALL ^E_NOTICE);
require "php/db.php";
$count_rows_balance = htmlspecialchars($_POST['cp']);
//Нужно рассчитать количесвто страниц
//Общее число строк, например 99 разделить на выбранное кол-во отображения  строк $cnt, например 10
//99/10=9,9 (10 страниц)
//54/10=5,4 (6 страниц)
//11/10=1,1 (2 страницы)
$count_pages = ceil((int)$count_rows_balance / (int)$cnt);
if((int)$count_rows_balance > 0 and (int)$count_rows_balance > (int)$cnt){
?>
<ul class="pagination pagination-sm justify-content-end">
	<li class="page-item pagination_page_left disabled">
	    <input type="hidden" value="0" id="data_pag_left">
		<button class="page-link">Предыдущая</button>
	</li>
	<?php
	for ($i = 1; $i <= $count_pages; $i++) {
	    $act_cls='';$d_n_row='';
	    $st_row = ((int)$cnt * ($i-1));
	    
	    if($i == 1){
	        $act_cls='active';
	        $st_row = (int)$cnt * ($i-1);
	    }
	    if($st_row == 0){
	        $lbl_row_from = "c 1";
	        $lbl_row_to = "по ".(int)$cnt*$i;
	    }else{
	        $lbl_row_from = "c ".((int)$cnt*$i-(int)$cnt+1);
	        $lbl_row_to = "по ".(int)$cnt*$i;
	    }
	    
	    //Нужно спрятать кнопки, которые $i > 3
	    if($i > 3){
	        $d_n_row = "d-none";
	    }
	   // if(($i % 4) == 0){
	    //}
	    //Находясь на 3. При клике на кнопку Следущий отображать блок кнопок с 4 по 7, закрывать с 1 - 3 и с 8 - 12
	    //Находясь на 4. При клике на кнопку Предыдущий открывать с 1-3 закрывать с 4-12
	    
	?>
	<li class="page-item pagination_page <?php echo $d_n_row;?>" data-end="<?php echo $i;?>" id="pag_btn-<?php echo $i;?>">
	    <button class="page-link <?php echo $act_cls;?>" data-label-from="<?php echo $lbl_row_from?>" data-label-to="<?php echo $lbl_row_to?>" data-start-row="<?php echo $st_row;?>"><?php echo $i;?></button>
	</li>
	<?php
    }
	?>
	<li class="page-item pagination_page_right">
	    <input type="hidden" value="2" id="data_pag_right">
		<button class="page-link">Следующая</button>
	</li>
</ul>
<?php
}
?>
