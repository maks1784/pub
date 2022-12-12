<div class="modal fade " id="modalAddFinance" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalAddFinanceLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header mb-2 bg-dark text-light">
        <h5 class="modal-title" id="modalAddFinanceLabel">Пополнение баланса</h5>
        <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div class="row">
            <div class="col-auto align-middle" style="padding-top: 4px;"><span class="align-middle">Пополнить баланс агенту <b id="login_lbl_modal_finance"></b> на сумму </span></div>
            <div class="col-auto"><input class="form-control" type="text" placeholder="50" id="value_add_finance"></div>
            <div class="col-auto" style="padding-top: 4px;">руб.</div>
          </div>
      </div>
      <div class="modal-footer">
        <p class="mx-3" id="info_form_finance"><span class="text-danger d-none val_error_01">Укажите сумму в рублях!</span></p>
        <button type="button" class="btn btn-success" id="submit_add_finance">Пополнить</button>
        <input type="hidden" value="" id="user_id_add_finance">
      </div>
    </div>
  </div>
</div>