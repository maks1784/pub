<div class="modal fade" id="modalEditStatusOrder" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalEditStatusOrderLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header mb-2 bg-dark text-light">
                <h5 class="modal-title" id="modalEditStatusOrderLabel">Изменить статус заявки <b></b></h5>
                <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-3"> 
                    <div class="col-md-12">
                        <div class="form-floating">
                            <select class="form-select bg-light" id="status_order_select">
                                <option value="Принята">Принята</option>
                                <option value="В работе">В работе</option>
                                <option value="Проверка">Проверка</option>
                                <option value="Готово">Готово</option>
                                <?php
                                if($sts == 'admin'){
                                ?>
                                <option value="Черновик">Черновик</option>
                                <option value="Архив">Архив</option>
                                <?php
                                }
                                ?>
                                
                            </select>
                            <label for="status_order_select">Статус заявки</label>
                        </div>
                    </div>
                </div>
                <div class="row g-3 mt-3 mb-3 load_files_box_ord d-none"> 
                    <div class="col-md-4">
                        <div>
                            <label for="pic_front_ord" class="form-label h6">PDF файл</label>
                            <input class="inp_ord_valid form-control form-control inp_pic_front_ord_error_01 inp_pic_front_ord_error_02 pic_front_ord_error_03" id="new_pdf_ord" type="file" accept="application/pdf,application/vnd.ms-excel" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div>
                            <label for="pic_front_ord" class="form-label h6">Вид спереди</label>
                            <input class="inp_ord_valid form-control form-control inp_pic_front_ord_error_01 inp_pic_front_ord_error_02 pic_front_ord_error_03" id="new_pic_front_ord" type="file" accept="image/png, image/gif, image/jpeg" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div>
                            <label for="pic_back_ord" class="form-label h6">Вид сзади</label>
                            <input class="inp_ord_valid form-control form-control inp_pic_back_ord_error_01 inp_pic_back_ord_error_02 pic_back_ord_error_03" id="new_pic_back_ord" type="file" accept="image/png, image/gif, image/jpeg" />
                        </div>
                    </div>
                    
                </div>
                <div class="row g-3 mb-3 load_files_box_ord d-none">
                    <div class="col-md-4 toggle_mini_new_photo d-none">
                        <div class="new_pdf_ord bg-light">
                            <i class="bi-filetype-pdf"></i>
                        </div> 
                    </div>
                    <div class="col-md-4 toggle_mini_new_photo d-none">
                        <div class="new_pic_front_ord bg-light">
                        </div>
                    </div>
                    <div class="col-md-4 toggle_mini_new_photo d-none">
                        <div class="new_pic_back_ord bg-light">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <p class="mx-3" id="info_form_orst">
                    <span class="text-danger d-none npdf_ord_error_01">Загрузите файл PDF!</span>
                    <span class="text-danger d-none npdf_ord_error_02">Ошибка формата или размера (PDF)!</span>
                    <span class="text-danger d-none npic_front_ord_error_01">Загрузите файл "Вид спереди"!</span>
                    <span class="text-danger d-none npic_front_ord_error_02">Ошибка формата или размера (Вид спереди)!</span>
                    <span class="text-danger d-none npic_back_ord_error_01">Загрузите файл "Вид сзади"!</span>
                    <span class="text-danger d-none npic_back_ord_error_02">Ошибка формата или размера (Вид сзади)!</span>
                    
                    <span class="text-danger d-none login_error_01">Ошибка!</span>
                </p>
                
                <button type="button" class="btn btn-success" id="submit_edit_st_order" value="">Сохранить</button>
            </div>
        </div>
    </div>
    <script>
            document.querySelector("#new_pic_front_ord").addEventListener("change", function () {
                if (this.files[0]) {
                    var fr = new FileReader();
                    fr.addEventListener("load", function () {
                      document.querySelector(".new_pic_front_ord").style.backgroundImage = "url(" + fr.result + ")";
                      $(".toggle_mini_new_photo").removeClass("d-none");
                    }, false);
                    fr.readAsDataURL(this.files[0]);
                }
            });
            document.querySelector("#new_pic_back_ord").addEventListener("change", function () {
                if (this.files[0]) {
                    var fr = new FileReader();
                    fr.addEventListener("load", function () {
                      document.querySelector(".new_pic_back_ord").style.backgroundImage = "url(" + fr.result + ")";
                      $(".toggle_mini_new_photo").removeClass("d-none");
                    }, false);
                    fr.readAsDataURL(this.files[0]);
                }
            });
            document.querySelector("#new_pdf_ord").addEventListener("change", function () {
                if (this.files[0]) {
                    $(".toggle_mini_new_photo").removeClass("d-none");
                }
            });
      </script>
</div>