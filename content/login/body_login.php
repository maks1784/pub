<body class="text-center" id="login_body">
    <main class="container">
        <div class="row d-flex justify-content-center mt-5">
            <div class="col-12 col-lg-3">
                <p class="mt-5 fs-1">🍀</p>
                <p class="mb-3">Example-TO</p>
                
                <h1 class="h3 mb-3 fw-normal text-secondary">Авторизация</h1>
            
                <div class="form-floating mb-3">
                  <input type="login" class="form-control" id="login" placeholder="Ваш логин">
                  <label for="floatingInput">Ваш логин</label>
                </div>
                <div class="form-floating mb-4">
                  <input type="password" class="form-control" id="password" placeholder="Ваш пароль">
                  <label for="floatingPassword">Ваш пароль</label>
                </div>
            
                <button class="w-100 btn btn-lg btn-success btn-md" type="submit" id="submit_login">Войти</button>
                <p class="mt-3 mb-3" id="info_form">
                    <span class="text-danger d-none login_error_01">Укажите ваш логин!</span>
                    <span class="text-danger d-none login_error_02">Пользователь не найден!</span>
                    <span class="text-danger d-none login_error_03">Пользователь заблокирован, обратитесь в тех поддержку!</span>
                    <span class="text-danger d-none pass_error_01">Укажите ваш пароль!</span>
                    <span class="text-danger d-none pass_error_02">Ошибка пароля!</span>
                </p>
                <p class="mt-5 mb-3 text-muted">© <span id="year"></span></p>
            </div>
        </div>
    </main>
</body>
<script>
    document.getElementById("year").innerHTML = new Date().getFullYear();
</script>