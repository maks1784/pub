<!-- Regist -->
<div class="modal fade" id="modalDelAgent" tabindex="-1" aria-labelledby="modalDelAgentLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header mb-2 bg-dark text-light">
                <h5 class="modal-title" id="modalDelAgentLabel">Блокировать пользователя?</h5>
                <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body del_comm_agent">
                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="del_comm_agent" placeholder="Причина блокировки" value="">
                            <label for="del_comm_agent">Причина блокировки</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <p class="mx-3" id="info_form_del_a">
                    <span class="text-danger d-none error_comm_01">Укажите комментарий!</span>
                    <span class="text-danger d-none error_id_num_01">Попробуйте после обновления страницы!</span>
                </p>
                <button type="button" class="btn btn-success" id="submit_del_agn" value="">Подтвердить</button>
                <input type="hidden" id="sts_modal_rec_or_del" value="">
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="content/common/js/script_nav.js"></script>