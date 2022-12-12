<?php
//ini_set('display_errors',1); error_reporting(E_ALL ^E_NOTICE);


require "php/db.php";

$lgn = htmlspecialchars($_POST['login']);
$str = htmlspecialchars($_POST['password']);

$user = R::dispense('users');
$user->lgn = $lgn;
$user->str = password_hash($str, PASSWORD_DEFAULT);
R::store($user);
/*
$surname = htmlspecialchars($_POST['surname']);
if(strlen($surname) == 0) exit('surnr_error_01');//Если в поле Фамилия не указано значение
$name = htmlspecialchars($_POST['name']);
if(strlen($name) == 0) exit('namer_error_01');//Если в поле Имя не указано значение
$patronymic = htmlspecialchars($_POST['patronymic']);
if(strlen($patronymic) == 0) exit('patror_error_01');//Если в поле Отчество не указано значение
$company = htmlspecialchars($_POST['company']);
if(strlen($company) == 0) exit('compr_error_01');//Если в поле Компания не указано значение
$personal = htmlspecialchars($_POST['personal']);
if(strlen($personal) == 0) exit('persr_error_01');//Если в поле Число сотрудников не указано значение
if(is_numeric($personal) == 0) exit('persr_error_01');//Если в поле Число сотрудников значение указано не в формате числа
$department = htmlspecialchars($_POST['department']);
if(strlen($department) == 0) exit('depr_error_01');//Если в поле Отдел для мотивации не указано значение

$phone= htmlspecialchars($_POST['phone']);
$email = htmlspecialchars($_POST['email']);
$password = htmlspecialchars($_POST['password']);

if(strlen($phone) == 0) exit('phoner_error_01');//Если в поле Телефон не указано значение
if(strlen($email) == 0) exit('emailr_error_01');//Если в поле Email не указано значение
if(!strpos($email, '@')) exit('emailr_error_02');//Email указан без @
$email_db = R::findOne('users', 'email = ?', array($email));
if($email_db) exit('emailr_error_03');//Если в базе данных уже есть пользователь с таким email-ом
if(strlen($password) == 0) exit('passr_error_01');//Если в поле Password не указано значение
if(strlen($password) < 6) exit('passr_error_02');//Если в поле Password указан слабый пароль

//ОПРЕДЕЛИТЬ БРАУЗЕР КЛИЕНТА
if ( stristr($_SERVER['HTTP_USER_AGENT'], 'Firefox') ) $browser = 'firefox'; elseif ( stristr($_SERVER['HTTP_USER_AGENT'], 'Chrome') ) $browser = 'chrome';elseif ( stristr($_SERVER['HTTP_USER_AGENT'], 'Safari') ) $browser = 'safari';elseif ( stristr($_SERVER['HTTP_USER_AGENT'], 'Opera') ) $browser = 'opera';elseif ( stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE 6.0') ) $browser = 'IE6.0' ;elseif ( stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE 7.0') ) $browser = 'IE7.0' ;  elseif ( stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.0') ) $browser = 'IE8.0' ;elseif ( stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE 10.0') ) $browser = 'IE10.0' ;  elseif ( stristr($_SERVER['HTTP_USER_AGENT'], 'Trident/7') ) $browser = 'IE11.0' ;
$user_ip = $_SERVER['REMOTE_ADDR'];

$user = R::dispense('users');
$user->surname = $surname;
$user->name = $name;
$user->patronymic = $patronymic;
$user->company = $company;
$user->personal = $personal;
$user->department = $department;
$user->date_reg = date("Y-m-d H:i:s", strtotime("now"));
$user->ip_address = $user_ip;
$user->browser = $browser;
$user->phone = $phone;
$user->email = $email;
$user->password = password_hash($password, PASSWORD_DEFAULT);
$user->avatar='';
$user->status=1;
$id=R::store($user);

//Добавим самого себя в usersus (в друзья), чтобы выводить себя в статистики с другими друзьями
$userus = R::dispense('usersus');
$userus->you_users_id = $id;
$userus->me_users_id = $id;
R::store($userus);

$user = R::findOne('users', 'email = ?', array($email));
$user->url='id'.$id;
//$_SESSION['logged_user'] = $user;
R::store($user);
//Создаём дирикторию для хранения файлов пользователя
$path_ava = 'img/users_avatars/id_'.$id;
mkdir($path_ava , 0777);
$path_fls = 'img/users_files/id_'.$id;
mkdir($path_fls , 0777);
*/
exit("200");
?>