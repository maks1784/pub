<?php
//ini_set('display_errors',1); error_reporting(E_ALL ^E_NOTICE);
$client  = @$_SERVER['HTTP_CLIENT_IP'];
$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
$remote  = @$_SERVER['REMOTE_ADDR'];
 
if(filter_var($client, FILTER_VALIDATE_IP)) $ip = $client;
elseif(filter_var($forward, FILTER_VALIDATE_IP)) $ip = $forward;
else $ip = $remote;
 
//echo $ip;
//217.20.75.106
/*if($ip != '217.20.75.106'){
    exit("Technical work on the site");
}*/
    
define( '_PTO', 1 );
if(strtolower(filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH')) === 'xmlhttprequest'){
    $URL_Path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    include trim($URL_Path, ' /');
    exit;
}else{
    include "php/db.php";
}

if($_SERVER['REQUEST_URI'] == '/'){
    $page = 'index';
    $module = 'index';
}else{
    $URL_Path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $URL_Parts = explode('/',trim($URL_Path, ' /'));//Массив данных по строке http
    //trim($URL_Path, ' /') убирает " /" с начача и с конца строки (было "/account/13/15/" стало так "account/13/15")
    $page = array_shift($URL_Parts);// Берёт первое значение массива (из массива "account","13","15" $page = account)
    $module = array_shift($URL_Parts);
    if(!empty($module)){
        $params = array();
        for($i = 0; $i < count($URL_Parts); $i++){
            $params[$URL_Parts[$i]] = $URL_Parts[++$i];
        }
    }
}
if(isset($_SESSION['logged_user'])){
    if($page == 'index' and $module == 'index'){//Главная Доступна
        header("Location: /orders");
    }else if($page == 'orders'){
        $content = "orders";
    }else if($page == 'agents' and $sts != 'moderator'){
        $content = "agents";
    }else if($page == 'finance'){
        $content = "finance";
    }else if($page == 'balance'){
        $content = "balance";
    }else if($page == 'pto' and $sts == 'admin'){
        $content = "pto";
    }else{
        
        header("Location: /orders");
    }
}else{
    if($page == 'index' and $module == 'index'){//Главная Доступна
        $content = "login";
    }else{
        header("Location: /");
    }
}

?>
<!DOCTYPE html>
<html lang="ru">
<?php include "content/".$content."/head_".$content.".php"; ?>
<?php include "content/".$content."/body_".$content.".php"; ?>
<script type="text/javascript" src="content/<?php echo $content;?>/js/script_<?php echo $content;?>.js"></script>
</html>