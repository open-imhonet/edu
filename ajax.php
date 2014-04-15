<?php
//Расковырял как старый имхонет регает юзеров и логинит.
//Этот же скрипт производит опросы. Пока не заставил работать клиентский JS :(
//для работы с БД нужен фреймворк medoo
session_start();
header('Content-type: application/json'); 
require_once  'medoo.min.php';
$database = new medoo('kinopoisk');
$ajax=$_POST; //добавить фильтр. валидация по мылу.
$captcha_keystring=$_SESSION['captcha_keystring'];

/////////////LOG///////////////////
if (isset($_POST)){
foreach ($_POST as $name => $value) {
$var.='POST name: '.$name.' value:'.$value."\n";

}
}
if (isset($_GET)){
foreach ($_GET as $name => $value) {
$var.='GET name: '.$name.' value:'.$value."\n";
}
}
if (isset($_SESSION)){
foreach ($_SESSION as $name => $value) {
$var.='SESSION name: '.$name.' value:'.$value."\n";
}
}
$var=$var."\r\n";
$f = fopen("json.txt", "a+");
fwrite($f, $var); 
fclose($f);
//////////////LOG////////////////

switch($_POST['action'])
{         
         case 'Registration':
         registration($ajax,$database,$captcha_keystring);   
         break; 

 
         case 'Rate2':
         rate2($ajax,$database,$captcha_keystring);   
         break; 


         case 'Opinion':
         opinion($ajax,$database,$captcha_keystring);   
         break; 

       
         
         default:
         die('General error');    
         break;
}



function jerror($errcode){
echo json_encode(array('error' => $errcode), true);
die();
}

function jsuccess(){
echo json_encode(array('success' => true), true);
die();
}
function juserid($userid,$referrer){
echo json_encode(array('success'=>true,'user_id' => $userid,'refferer' => 'http://google.com'), true); //При удачном заходе переход на $referrer

}

function rate2($ajax,$database,$captcha_keystring){
switch($ajax['func'])
{         
         case 'setRate':
//$ajax[user_rate]
//$ajax[user_rate_anonymous]
//$ajax[object_id]
//$ajax[element_id]
//$ajax[content_id]
         jsuccess();
         break;
         default:
         jerror('-25');   
         break;
}
}


function opinion($ajax,$database,$captcha_keystring){
//$ajax[noresponse value]:0
//$ajax[user_opinion value]:1
//$ajax[content_id value]:3
//$ajax[entity value]:element
//$ajax[id value]:198121
//$ajax[object_id] value:198491
         jsuccess();
}









function registration($ajax,$database,$captcha_keystring){
switch($ajax['func'])
{         
         case 'checkLogin':
         if ($database->has("users", ["username" => $ajax['login']])){die();}
         jsuccess();
         break;         
         
         case 'checkEmail':
         if (false===preg_match("/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/", $ajax['email'])){die('-3');}
         if (true===($database->has("users", ["email" => $ajax['email']]))){die('-4');}
         jsuccess();
         break;

         case 'registerUser':
         if ($database->has("users", ["username" => $ajax['login']])){die();}
         if (false===preg_match("/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/", $ajax['email'])){die('-3');}
         if (true===($database->has("users", ["email" => $ajax['email']]))){die('-4');}
         if ($ajax['accept']!=='checked'){jerror('-72');}
         if ($ajax['captcha']!==$captcha_keystring){jerror('-33');}
                  
         $database->insert("users", [
         "username" => $ajax['login'],
         "email" => $ajax['email'],
         "password" => $ajax['password'],
         "regdate" => date("Y-m-d"),
         "lastlogin" => date("Y-m-d")
         ]);

         $datas = $database->select("users", "userid", ["username" => $ajax['login']]);
         juserid($datas[0],$referrer);
         break;   



         case 'finishRegistration':
         jsuccess();
         break;


         default:
         jerror('-25');   
         break;
}
}



/*

POST name: func value:registerUser
POST name: auth value:0
POST name: login value:nbvv
POST name: password value:passwd
POST name: email value:email@bk.ru
POST name: captcha value:y8kd8e
POST name: accept value:checked
POST name: action value:Registration
POST name: noresponse value:1


POST name: func value:finishRegistration
POST name: action value:Registration
POST name: noresponse value:1


*/





//$database->insert("account", [
//"user_name" => "foo",
//"email" => "foo@bar.com"
//]);
//}
//if ($ajax['func']=='registerUser'){
//$ajax['auth'];
//$ajax['login'];
//$ajax['password'];
//$ajax['email'];
//$ajax['captcha'];
//$ajax['accept'];
/*
$database->insert("users", [
"username" => "foo",
"email" => "foo@bar.com",
"password" => "kjsdgfdsjfh",
"regdate" => "20001010",
"lastlogin" => "20001010"
]);

}

}


*/














//$_GET['func']
//$_GET['login']
//$_GET['action']
//$_GET['noresponse']
//$var='func='.$_GET['func'].' login='.$_GET['login'].' action='.$_GET['action'].' noresponse='.$_GET['noresponse']."\n";
//$var='func='.$_POST['func'].' login='.$_POST['login'].' action='.$_POST['action'].' noresponse='.$_POST['noresponse']."\n";














//$n = array("response" => -2);  
    
/*


-72': 'Пользовательское соглашение
-33': 'Секретный код не верен
-21': 'Соц сеть'
-22': 'Логин'
-23': 'Пароль'
-24': 'Email'
-25': 'Email
-2 бан емейл
-3 некорректный емейл
-4 уже зарегестрирован
-5 не указан емейл


*/

?>
