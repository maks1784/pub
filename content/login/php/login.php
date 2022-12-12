<?php
require "php/db.php";

$lgn = htmlspecialchars($_POST['login']);
$str = htmlspecialchars($_POST['password']);

if(strlen($lgn) == 0) exit('login_error_01');//Если в поле Email не указано значение
//if(!strpos($email, '@')) exit('email_error_02');//Email указан без @

$user = R::findOne('users', 'lgn = ?', array($lgn));
if(!$user) exit('login_error_02');//Если в базе данных нет пользователя с таким email-ом Ошибка

if(strlen($str) == 0) exit('pass_error_01');//Если в поле Password не указано значение

if($user){
    if($user->del == '1') exit('login_error_03');//Пользователь заблокирован
    if(password_verify($str, $user->str)){
        $_SESSION['logged_user'] = $user;
        $user->last_login=date("Y-m-d H:i:s", strtotime("now"));
        R::store($user);
        exit("200");
    }else{
        exit("pass_error_02");
    }
}