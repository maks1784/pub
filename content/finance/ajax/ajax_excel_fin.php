<?php  
//ini_set('display_errors',1); error_reporting(E_ALL ^E_NOTICE);
require "php/db.php";
require'php/libs/PHPExcel-1.8/Classes/PHPExcel.php';
require 'php/libs/PHPExcel-1.8/Classes/PHPExcel/Writer/Excel2007.php';
$sts_us = htmlspecialchars($_POST['su']);//data-sts
$sts_exc = htmlspecialchars($_POST['ev']);//id в value
$start_period = htmlspecialchars($_POST['st']);
$end_period = htmlspecialchars($_POST['en']);
$filt_users = htmlspecialchars($_POST['us']);
$filt_pto = htmlspecialchars($_POST['to']);

$total_ctg = ctg();
$total_pto = pto();
if($sts_exc != ''){
    $total_ptous = ptous($sts_exc);
    $total_user = users();
}else{
    $total_ptous = ptous_db();
    $total_user = users();
}

//Сколько всего вариантов может быть
//1. Авторизован Админ 
//• Кликаем по Админу (по себе)
//• Кликаем по Агенту
//• Кликаем по Итоговому

//2. Авторизован Агент
//• Кликаем по Агенту (по себе)
//• Кликаем по Агенту
//• Кликаем по Итоговому

if($sts == 'admin'){
    if($sts_us == 'admin' and $sts_exc == $id){//• Кликаем по Админу (по себе)
        $total_users_finance=ad_ag_excel($start_period,$end_period,$filt_pto,$sts_exc);
    }
    if($sts_us == 'agent' and $sts_exc != ''){//• Кликаем по Агенту
        $user = R::Load("users",$sts_exc);
        $usuid = $user->usu_id;
        $total_users_finance=agent_exl($sts_exc,$usuid,$start_period,$end_period,$filt_pto);
    }
    if($sts_us == 'admin' and $sts_exc == ''){//• Кликаем по Итоговому
        $total_users_finance=admin_excel($start_period,$end_period,$filt_pto);
    }
}


if($sts == 'agent'){
    if($sts_us == 'agent' and $sts_exc == $id){//• Кликаем по Агенту (по себе)
        $total_users_finance=ad_ag_excel($start_period,$end_period,$filt_pto,$sts_exc);
    }
    if($sts_us == 'agent' and $sts_exc != ''){//• Кликаем по Агенту
        $user = R::Load("users",$sts_exc);
        $usuid = $user->usu_id;
        $total_users_finance=agent_exl($sts_exc,$usuid,$start_period,$end_period,$filt_pto);
        
       /* $error = R::dispense('err');
        $error->date_e=date("Y-m-d H:i:s",strtotime("now"));
        $error->text_e=$total_users_finance;
        R::store($error);*/
    }
    if($sts_us == 'agent' and $sts_exc == ''){//• Кликаем по Итоговому
        $total_users_finance=agent_excel($id,$usu_id,$start_period,$end_period,$filt_pto);
        
       /* $error = R::dispense('err');
        $error->date_e=date("Y-m-d H:i:s",strtotime("now"));
        $error->text_e=$total_users_finance;
        R::store($error);*/
    }
}
/*
//1 data-sts=admin в кнопке и id в value=$id
if($sts_us == 'admin' and $sts_exc == $id){
    
}
//2 data-sts=agent в кнопке и id в value
if($sts_us == 'agent' and $sts_exc != ''){
    $user = R::Load("users",$sts_exc);
    $usuid = $user->usu_id;
    $total_users_finance=agent_exl($sts_exc,$usuid,$start_period,$end_period,$filt_pto);
}
//3. data-sts=admin и value=" "
if($sts_us == 'admin' and $sts_exc == ''){
    $total_users_finance=admin_excel($start_period,$end_period,$filt_pto);
}



//4. data-sts=agent в кнопке и id в value=$id
if($sts_us == 'agent' and $sts_exc == $id){
    $total_users_finance=ad_ag_excel($start_period,$end_period,$filt_pto,$sts_exc);
}
//5. data-sts=agent в кнопке и id в value
if($sts_us == 'agent' and $sts_exc != ''){
    $user = R::Load("users",$sts_exc);
    $usuid = $user->usu_id;
    $total_users_finance=agent_exl($sts_exc,$usuid,$start_period,$end_period,$filt_pto);
}
//6. data-sts=agent и value=" "
if($sts_us == 'agent' and $sts_exc == ''){
    $total_users_finance=agent_excel($id,$usu_id,$start_period,$end_period,$filt_pto);
}
*/
  /*  
$exc = true;
if($sts == "agent" or $sts == "moderator"){
    $total_users_finance = agent_finance($id,$usu,$start_period,$end_period,$filt_users,$filt_pto,$exc,$sts_exc);
}else if($sts == "admin"){
    $total_users_finance = admin_finance_new($id,$usu,$start_period,$end_period,$filt_users,$filt_pto,$exc,$sts_exc);
}*/



if($total_users_finance){
    $xls = new PHPExcel();//Создаём документ Excel
    //Вводные
    $xls->getProperties()->setTitle("Отчёт");
    $xls->getProperties()->setSubject("Отчёт");
    $xls->getProperties()->setCreator("ПТО");
    $xls->getProperties()->setManager("Руководитель");
    $xls->getProperties()->setCompany("Организация");
    $xls->getProperties()->setCategory("Группа");
    $xls->getProperties()->setCreated(date("d.m.Y",strtotime("now")));
    
    //Создаём страницу
    $xls->setActiveSheetIndex(0);
    $sheet = $xls->getActiveSheet();
    $sheet->setTitle('Отчёт'); 
    
    //Работаем с ячейками
    $sheet->setCellValue("A1", "ДАТА");
    $sheet->setCellValue("B1", "ТС");
    $sheet->setCellValue("C1", "Г/н");
    $sheet->setCellValue("D1", "VIN");
    $sheet->setCellValue("E1", "Кат.");
    $sheet->setCellValue("F1", "Номер ЕАИСТО");
    $sheet->setCellValue("G1", "ПТО");
    $sheet->setCellValue("H1", "Цена");
    $sheet->setCellValue("I1", "Агент");
    // Высота 1-й строки
    $sheet->getRowDimension("1")->setRowHeight(21);
    // Ширина столбца
    $sheet->getColumnDimension("A")->setWidth(14);
    $sheet->getColumnDimension("B")->setWidth(14);
    $sheet->getColumnDimension("C")->setWidth(12);
    $sheet->getColumnDimension("D")->setWidth(23);
    $sheet->getColumnDimension("E")->setWidth(5);
    $sheet->getColumnDimension("F")->setWidth(18);
    $sheet->getColumnDimension("G")->setWidth(30);
    $sheet->getColumnDimension("H")->setWidth(6);
    $sheet->getColumnDimension("I")->setWidth(50);
    $i=2;
    foreach($total_users_finance as $row){
        $pieces = explode("-", $row['pto_id']);
        $prc_n = $pieces[1];
        $ctg_l = "";
        $pto_l = "";
        $row_v="";
        foreach($total_ctg as $ctg){
            if($row['ctg_id'] == $ctg['id']){
                $ctg_l = $ctg['ctg'];
            }
        }
        if($sts_exc != ''){
            if($sts_us == 'agent'){
                foreach($total_ptous as $ptu){
                    if($row['id_pto'] == $ptu['pto_id']){
                        foreach($total_pto as $pto){
                            if($pto['id'] == $ptu['pto_id']){
                                $pto_l = $pto['name'];
                            }
                        }
                    }
                }
            }
            if($sts_us == 'admin'){
                foreach($total_pto as $pto){
                    if($pto['id'] == $row['id_pto']){
                        $pto_l = $pto['name'];
                    }
                }
            }
            foreach($total_user as $usr){
                if($row['users_id'] == $usr['id']){
                    $lgn_ag = $usr['lbl'];
                }
            }
            if($sts == 'admin'){
                if($sts_us == 'agent'){
                   // $sts_exc//id агента по которому клик
                    foreach($total_ptous as $ptu){
                        if($row['id_pto'] == $ptu['pto_id']){
                            $row_v = $ptu[$prc_n];
                        }
                    }
                }
                if($sts_us == 'admin'){
                    foreach($total_pto as $pto){
                        if($pto['id'] == $row['id_pto']){
                            $row_v = $pto[$prc_n];
                        }
                    }
                }
            }
            if($sts == 'agent'){
                $total_ptous = ptous($id);
                foreach($total_ptous as $ptu){
                    if($row['id_pto'] == $ptu['pto_id']){
                        $row_v = $ptu[$prc_n];
                    }
                }
            }
            
            
        }else{
            //Значит запросили итоговый отчёт ИТОГОВЫЙ ОТЧЁТ
            $lgn_ag="";
            
            //Для вывода названий ПТО
            foreach($total_pto as $pto){
                if($pto['id'] == $row['id_pto']){
                    $pto_l = $pto['name'];
                }
            }
            
            //Для вывода цен ПТО вышестоящего агента
            if($sts == 'admin'){
                foreach($total_pto as $pto){
                    if($row['id_pto'] == $pto['id']){
                        $row_v = $pto[$prc_n];
                    }
                }
            }
            if($sts == 'agent'){
                $total_ptous = ptous($id);
                foreach($total_ptous as $ptu){
                    if($row['id_pto'] == $ptu['pto_id']){
                        $row_v = $ptu[$prc_n];
                    }
                }
                
               /* $stack=[];
                
                //Ищем вышестоящего агента, чтобы взять его цены для нас
                foreach($total_user as $usr){
                    $us_usu = $usr['usu'];
                    array_push($stack, $us_usu);
                    if(strpos($us_usu, ',') !== false ){
                        $res_usu = explode(',', $us_usu);
                        $pos = in_array($id, $res_usu);
                        if($pos){
                            $error = R::dispense('err');
                            $error->date_e=date("Y-m-d H:i:s",strtotime("now"));
                            $error->text_e='Есть';
                            R::store($error);
                            $total_ptous = ptous($usr['id']); 
                            foreach($total_ptous as $ptu){
                                if($row['id_pto'] == $ptu['pto_id']){
                                    $row_v = $ptu[$prc_n];
                                }
                            }
                        }
                    }else{
                        if($us_usu == $id){
                            $total_ptous = ptous($usr['id']);
                            foreach($total_ptous as $ptu){
                                if($row['id_pto'] == $ptu['pto_id']){
                                    $row_v = $ptu[$prc_n];
                                }
                            }
                        }
                    }
                }
                $posy = in_array($id, $stack);
                if(!$posy){
                    foreach($total_pto as $pto){
                        if($row['id_pto'] == $pto['id']){
                            $row_v = $pto[$prc_n];
                        }
                    }
                }*/
            }
            
            foreach($total_user as $usr){
                if($row['users_id'] == $usr['id']){
                    $lgn_ag = $usr['lbl'];
                }
            }
        }
        
        $sheet->setCellValue("A".$i, date("d.m.y H:i", strtotime($row['date_exe'])));
        $sheet->setCellValue("B".$i, $row['car_brand']." ".$row['car_model']);
        $sheet->setCellValue("C".$i, $row['car_gosn']);
        $sheet->setCellValue("D".$i, $row['car_vin']);
        $sheet->setCellValue("E".$i, $ctg_l);
        $sheet->setCellValue("F".$i, substr($row['pdf_name'], 0, -4));
        $sheet->setCellValue("G".$i, $pto_l);
        $sheet->setCellValue("H".$i, $row_v);
        $sheet->setCellValue("I".$i, $lgn_ag);
        $i++;
    }
    if(file_exists('content/finance/php/files/')) {
        foreach (glob('content/finance/php/files/*') as $file) {
            unlink($file);
        }
    }
    if($sts_exc != '') $f_name = $lgn_ag.'_'.date("d-m-Y", strtotime($start_period)).'_'.date("d-m-Y", strtotime($end_period)).'.xlsx';
    else $f_name = 'otchet_'.date("d-m-Y", strtotime($start_period)).'_'.date("d-m-Y", strtotime($end_period)).'.xlsx';
    
    $file_name = mb_convert_encoding($f_name, 'UTF-8');
    $objWriter = new PHPExcel_Writer_Excel2007($xls);
    $objWriter->save('content/finance/php/files/'.$file_name);
    exit('content/finance/php/files/'.$file_name);
    
}else{
    exit("ri34fo");
}