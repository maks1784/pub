<?php 
require "libs/rbphp/rb.php";
require "functions.php";
R::setup( 'mysql:host=localhost;dbname=i92363kr_to','i92363kr_to', '%UifRtweR' );
session_start();
$id;$total_user;$admin;$url;$surname;$name;$patronymic;$avatar;$email;$phone;$avatar;
if(isset($_SESSION['logged_user'])){
    $id = $_SESSION['logged_user']->id;
    $users = R::Load('users',$id);
    $sts = $users->sts;
    $lgn = $users->lgn;
    $usu = $users->usu;
    $lbl = $users->lbl;
    $usu_id = $users->usu_id;
    $val = $users->val;
    $cnt = $users->cnt;
   /* $total_user = user($id);
    $admin = acc($id);
    foreach($total_user as $row){
        $url = $row['url'];
        $surname = $row['surname'];
        $name = $row['name'];
        $patronymic = $row['patronymic'];
        $avatar = $row['avatar'];
        $email = $row['email'];
        $phone= $row['phone'];
        $avatar= $row['avatar'];
    }*/
}
?>