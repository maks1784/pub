<?php
require "php/db.php";
$sm = htmlspecialchars($_POST['sm']);
$ldl = "Добавить ПТО";
$pto_id="";
$npto="";
$pra="";
$prb="";
$prc="";
$pre="";
if($sm == 'edit'){
    $ldl = "Изменить ПТО";
    $pto_id = htmlspecialchars($_POST['pi']);
    $pto = R::load("pto",$pto_id);
    $npto = $pto->name;
    $pra = $pto->price_a;
    $prb = $pto->price_b;
    $prc = $pto->price_c;
    $pre = $pto->price_e;
}
?>

<div class="modal-header mb-2 bg-dark text-light">
    <h5 class="modal-title" id="modalAddPtoLabel"><?php echo $ldl;?></h5>
    <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row g-3 mb-3">
        <div class="col-md-12">
            <div class="form-floating">
                <input type="text" class="form-control" id="add_pto_name" placeholder="Наименование ПТО" value="<?php echo $npto;?>">
                <label for="add_pto_name">Наименование ПТО</label>
            </div>
        </div>  
        <div class="col-md-3">
            <div class="form-floating">
                <input type="text" class="form-control" id="add_pto_a" placeholder="Категория A" value="<?php echo $pra;?>">
                <label for="add_pto_a">Прайс, кат. A</label>
            </div>
        </div>
         <div class="col-md-3">
            <div class="form-floating">
                <input type="text" class="form-control" id="add_pto_b" placeholder="Категория B" value="<?php echo $prb;?>">
                <label for="add_pto_b">Прайс, кат. B</label>
            </div>
        </div>
         <div class="col-md-3">
            <div class="form-floating">
                <input type="text" class="form-control" id="add_pto_c" placeholder="Категория C" value="<?php echo $prc;?>">
                <label for="add_pto_c">Прайс, кат. C</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-floating">
                <input type="text" class="form-control" id="add_pto_e" placeholder="Категория E" value="<?php echo $pre;?>">
                <label for="add_pto_e">Прайс, кат. E</label>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <p class="mx-3" id="info_form_pto">
        <span class="text-danger d-none name_error_01">Укажите наименование ПТО!</span>
        <span class="text-danger d-none val_error_01">Укажите сумму в рублях хотя бы для одной категории!</span>
        <span class="text-danger d-none vala_error_01">Укажите сумму в рублях для кат. A!</span>
        <span class="text-danger d-none valb_error_01">Укажите сумму в рублях для кат. B!</span>
        <span class="text-danger d-none valc_error_01">Укажите сумму в рублях для кат. C!</span>
        <span class="text-danger d-none vale_error_01">Укажите сумму в рублях для кат. E!</span>
    </p>
    <button type="button" class="btn btn-success" id="submit_add_pto" value="<?php echo $pto_id;?>">Сохранить</button>
</div>
