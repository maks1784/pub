<?php
//ini_set('display_errors',1); error_reporting(E_ALL ^E_NOTICE);
require "php/db.php";
$cnt=htmlspecialchars($_POST['vcnt']);
$user = R::load('users',$id);
$user->cnt = $cnt;
R::store($user);