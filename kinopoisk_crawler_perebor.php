#!/usr/bin/php
<?php
$dbpasswd='dbpwd';
$dbuser='root';
$dbhost='localhost';
$dbname='kinopoisk';

$user='kpusername';
$password='kppasswd';
//$id='696977';
//$id='229069';
//$id='721153';

$link = mysql_connect($dbhost, $dbuser, $dbpasswd);
if (!$link) {
    die('Ошибка соединения mysql: ' . mysql_error());
}
mysql_select_db($dbname, $link) or die ('Can\'t use database '.$dbname . mysql_error());
mysql_set_charset('utf8',$link); 


//поиск в базе по ид фильма, если не найден - инсерт в базу и идем дальше, если не найден идем дальше

$startUrl = 'http://kinopoisk.ru';

$logFile = '/home/felis/www/scripts/logfilelp.txt';


echo $url."\n";
global $link,$user,$password;
$i=823961; //откуда начинаем 
while ($i < 999999) {
$url='http://kinopoisk.ru/film/'.$i.'/';
crawl($url,$link,$user,$password);
$i++;
}








crawl($url,$link,$user,$password);
             

function crawl($url,$link,$user,$password){
if (strpos($url ,'film')){
$pattern = '/[0-9]{2,}/'; 
preg_match($pattern, $url, $matches);
if (isset($matches[0]))
{
$id = follow_id($matches[0],$link);
if ($id!=false){
echo insert_row($id,$link,$user,$password);
}
}
}
}


function follow_id($id,$link){
$sql = "SELECT * FROM `films` WHERE `kpid` = ".$id." LIMIT 0, 30 ";
$result=mysql_query($sql, $link);
$result=mysql_num_rows($result);
if ($result==0){
return $id;
}
else{
return false;
}
}






function insert_row($id,$link,$user,$password){
$new=getdata($user,$password,$id);
if ($new['name']==''){return $id.' ';}
foreach($new as $key => $value) {
    $new[$key] = mysql_real_escape_string($value);
}
$new['country']=mysql_real_escape_string($new['country']);


$sql = 'INSERT INTO `kinopoisk`.`films` (`id`, `kpid`, `name`, `originalname`, `year`, `country`, `slogan`, `director`, `script`, `producer`, `operator`, `composer`, `artist`, `genre`, `budget`, `usa_charges`, `world_charges`, `rus_charges`, `world_premiere`, `rus_premiere`, `dvd`, `bluray`, `MPAA`, `time`, `description`, `kinopoisk`, `kp_votes`, `imdb`) VALUES (NULL, "'.$id.'", "'.$new['name'].'", "'.$new['originalname'].'","'.$new['year'].'","'.$new['country'].'", "'.$new['slogan'].'", "'.$new['director'].'", "'.$new['script'].'", "'.$new['producer'].'", "'.$new['operator'].'", "'.$new['composer'].'", "'.$new['artist'].'", "'.$new['genre'].'", "'.$new['budget'].'", "'.$new['usa_charges'].'", "'.$new['world_charges'].'", "'.$new['rus_charges'].'", "'.$new['world_premiere'].'", "'.$new['rus_premiere'].'", "'.$new['dvd'].'", "'.$new['bluray'].'", "'.$new['MPAA'].'","'.$new['time'].'", "'.$new['description'].'", "'.$new['kinopoisk'].'", "'.$new['kp_votes'].'", "'.$new['imdb'].'");';


$result=mysql_query($sql, $link);
echo "\n".$sql."\n";
if (!$result) {
   die('Неверный запрос: ' . mysql_error());
}



echo "\n".'Год:'.$new['year']."\n";
echo 'Страна:'.$new['country']."\n";
echo 'Режиссер:'.$new['director']."\n";
echo 'Продюсер:'.$new['producer']."\n";
echo 'Художник:'.$new['artist']."\n";
echo 'Бюджет:'.$new['budget']."\n";
echo 'Сборы в США:'.$new['usa_charges']."\n";
echo 'Сборы в мире:'.$new['world_charges']."\n";
echo 'Сборы в России:'.$new['rus_charges']."\n";
echo 'Премьера (мир):'.$new['world_premiere']."\n";
echo 'Премьера (РФ):'.$new['rus_premiere']."\n";
echo 'Релиз на DVD:'.$new['dvd']."\n";
echo 'Релиз на Blu-Ray:'.$new['bluray']."\n";
echo 'Рейтинг MPAA:'.$new['MPAA']."\n";
echo 'Рейтинг Кинопоиск:'.$new['kinopoisk'].' '.$new['kp_votes']."\n";
echo 'IMDB:'.$new['imdb']."\n";
echo 'Композитор:'.$new['composer']."\n";
echo 'Оригинальное название:'.$new['originalname']."\n";
echo 'Назывние:'.$new['name']."\n";
echo 'http://st.kp.yandex.net/images/film/'.$id.'.jpg'."\n";
echo 'http://st.kp.yandex.net/images/film_big/'.$id.'.jpg'."\n";
echo 'Жанр:'.$new['genre']."\n";
echo 'Оператор:'.$new['operator']."\n";
echo 'Время:'.$new['time']."\n";
echo 'Сценарий:'.$new['script']."\n";
echo 'Слоган:'.$new['slogan']."\n";
echo 'Описание:'.$new['description']."\n";
getimages($id);
}
//грузим постеры
function getimages($id){
$dir = substr($id, 0, 1);
$dir2 = substr($id, 1, 1);
$to = "/home/felis/www/scripts/".$dir."/".$dir2."/";
$to2 = "/home/felis/www/scripts/big/".$dir."/".$dir2."/";
$from = "http://st.kp.yandex.net/images/film/".$id.".jpg";
$from2 = "http://st.kp.yandex.net/images/film_big/".$id.".jpg";
passthru("/usr/bin/wget $from -P $to", $output); 
echo $output;
passthru("/usr/bin/wget $from2 -P $to2", $output);
}







function post($url,$post,$refer){
    if($post==null){$post=false;}
       $ch = curl_init($url);
       curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.4) Gecko/2008102920 AdCentriaIM/1.7 Firefox/3.0.4");
       curl_setopt($ch, CURLOPT_HEADER, 0);
       curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
       curl_setopt($ch, CURLOPT_REFERER, $refer);
       curl_setopt($ch, CURLOPT_COOKIEJAR, "./cookie.txt");
       curl_setopt($ch, CURLOPT_COOKIEFILE, "./cookie.txt");
       curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       $result  = curl_exec($ch);
       return $result;
}
//    preg_match('#([0-9]{2,7})#',$_REQUEST['id'],$id);
    


function getdata($user,$password,$id){
post('http://www.kinopoisk.ru/level/30/','shop_user[login]='.$user.'&shop_user[pass]='.$password.'&shop_user[mem]=on&auth=%E2%EE%E9%F2%E8+%ED%E0+%F1%E0%E9%F2','http://www.kinopoisk.ru');
    $result=post('http://www.kinopoisk.ru/level/1/film/'.$id.'/',null,'http://www.kinopoisk.ru/');
    $result= iconv("cp1251", "utf8", $result);
 // echo $result;
//echo strip_tags($result);


    $parse=array(
    'name' =>         '#<h1 class=\"moviename-big\" itemprop=\"name\">(.*?)</h1>#si',
    'originalname'=>  '#<span itemprop=\"alternativeHeadline\">(.*?)</span>#si',
    'year' =>         '#<td class=\"type\">год</td>(.*?)</div></td>#si',
    'country' =>      '#<td class="type">страна</td>(.*?)</div></td>#si',
    'slogan' =>       '#слоган</td><td style=\"color: \#555\">(.*?)</td></tr>#si',
    'director' =>     '#<td itemprop=\"director\">(.*?)</a></td></tr>#si',
    'script' =>       '#сценарий</td><td>(.*?)</td></tr>#si',
    'producer' =>     '#<td itemprop=\"producer\">(.*?)</td>#si',
    'operator' =>     '#оператор</td><td>(.*?)</td></tr>#si',
    'composer' =>     '#<td itemprop=\"musicBy\">(.*?)</td>#si',
    'artist' =>       '#художник</td><td>(.*?)</td></tr>#si',
    'genre' =>        '#<span itemprop="genre">(.*?)</span>#si',
    'budget' =>       '#<td class=\"dollar\"><div style=\"position: relative\">(.*?)</div></td>#si',
    'usa_charges' =>  '#сборы в США</td>(.*?)</a>#si',
    'world_charges'=> '#сборы в мире</td>(.*?)сборы</a>#si',
    'rus_charges' =>  '#сборы в России</td>(.*?)</div>#si',
    'world_premiere'=>'#премьера \(мир\)</td>(.*?)</a>#si',
    'rus_premiere' => '#премьера \(РФ\)</td>(.*?)</a>#si',
    'dvd' =>          '#dvd">(.*?)</td>#is',
    'bluray' =>       '#bluray">(.*?)</td>#is',
    'MPAA' =>         '#MPAA</td><td class=\"[\S]{1,100}\"><a href=\'[\S]{1,100}\'><img src=\'/[\S]{1,100}\' height=11 alt=\'(.*?)\' border=0#si',
    'time' =>         '#id="runtime">(.*?)</td></tr>#si',
    'description' =>  '#<span class=\"_reachbanner_\">(.*?)</span>#si',
    'imdb' =>         '#IMDB:\s(.*?)</div>#si',
    'kinopoisk' =>    '#rating:(.*?)guest#si',
    'kp_votes' =>     '#ratingCount">(.*?)</span>#si',
    'imdb' => '#IMDb: (.*?)</div>#si',
     );

   $new=array();
   foreach($parse as $index => $value)
   {
   preg_match($value,$result,$matches);
   $new[$index]=@preg_replace("#<a.+?>(.+?)</a>#is","$1",$matches[1]);
   }

preg_match('#getTrailer\("(.*?)","(.*?)","(.*?)","[0-9]+","[0-9]+","(.*?)",""\);#i',$result,$trailer);
$new['kinopoisk']=trim($new['kinopoisk']);
$new['kp_votes']=trim($new['kp_votes']);
$new['year']=trim(strip_tags($new['year']));
$new['country']=trim(strip_tags($new['country']));
$new['director']=strip_tags($new['director']);
$new['producer']=strip_tags($new['producer']);
$new['producer']=str_replace(', ...','',$new['producer']);
$new['artist']=str_replace(', ...','',$new['artist']);
$new['script']=str_replace(', ...','',$new['script']);
$new['script']=strip_tags($new['script']);
$new['composer']=strip_tags($new['composer']);
$new['usa_charges']=strip_tags($new['usa_charges']);
$new['world_charges']=strip_tags($new['world_charges']);
$new['rus_premiere']=trim(strip_tags($new['rus_premiere']));
$new['world_premiere']=trim(strip_tags($new['world_premiere']));
$new['country']=strip_tags($new['country']);
$new['artist']=strip_tags($new['artist']);
$new['time']=strip_tags($new['time']);
$new['description']=strip_tags($new['description']);
$new['description']=str_replace('&#151;','-',$new['description']);
$new['description']=str_replace('&nbsp;',' ',$new['description']);
$new['description']=str_replace('&#133','...',$new['description']);
$new['slogan']=str_replace('&laquo;',' ',$new['slogan']);
$new['slogan']=str_replace('&raquo;',' ',$new['slogan']);
$new['name']=trim(str_replace('&nbsp;',' ',$new['name']));
$new['budget']=trim(str_replace('&nbsp;',' ',$new['budget']));
$new['kp_votes']=trim(str_replace('&nbsp;',' ',$new['kp_votes']));
$new['name']=strip_tags($new['name']);
$new['dvd']=strip_tags($new['dvd']);
$new['dvd']=trim(str_replace('&nbsp;',' ',$new['dvd']));
$new['dvd']=str_replace("\n","",$new['dvd']);
$new['dvd']=str_replace("  ","",$new['dvd']);
$new['bluray']=strip_tags($new['bluray']);
$new['bluray']=trim(str_replace('&nbsp;',' ',$new['bluray']));
$new['bluray']=str_replace("\n","",$new['bluray']);
$new['bluray']=str_replace("  ","",$new['bluray']);
$new['usa_charges']=trim(str_replace('&nbsp;',' ',$new['usa_charges']));
$new['world_charges']=trim(str_replace('&nbsp;',' ',$new['world_charges']));
$new['rus_charges']=strip_tags($new['rus_charges']);
$new['rus_charges']=str_replace('сборы','',$new['rus_charges']);
$new['rus_charges']=trim(str_replace('&nbsp;',' ',$new['rus_charges']));

return $new;
}

//insert_row($new,$id);

/////////////////////////print/////////////////////////////
/*
//echo '[Ссылка на трейлер] http://'.$trailer[4].'.kinopoisk.ru/trailers/flv/'.$trailer[2]."\n";
//echo '[Трейлер preview] http://'.$trailer[4].'.kinopoisk.ru/trailers/flv/'.$trailer[3]."\n";

echo 'Год:'.$new['year']."\n";
echo 'Страна:'.$new['country']."\n";
echo 'Режиссер:'.$new['director']."\n";
echo 'Продюсер:'.$new['producer']."\n";
echo 'Художник:'.$new['artist']."\n";
echo 'Бюджет:'.$new['budget']."\n";
echo 'Сборы в США:'.$new['usa_charges']."\n";
echo 'Сборы в мире:'.$new['world_charges']."\n";
echo 'Сборы в России:'.$new['rus_charges']."\n";
echo 'Премьера (мир):'.$new['world_premiere']."\n";
echo 'Премьера (РФ):'.$new['rus_premiere']."\n";
echo 'Релиз на DVD:'.$new['dvd']."\n";
echo 'Релиз на Blu-Ray:'.$new['bluray']."\n";
echo 'Рейтинг MPAA:'.$new['MPAA']."\n";
echo 'Рейтинг Кинопоиск:'.$new['kinopoisk'].'('.$new['kp_votes'].' )<br />';
echo 'IMDB:'.$new['imdb']."\n";
echo 'Композитор:'.$new['composer']."\n";
echo 'Оригинальное название:'.$new['originalname']."\n";
echo 'Назывние:'.$new['name']."\n";
echo 'http://st.kp.yandex.net/images/film/'.$id.'.jpg[/img]<br />';
echo '[img_big]http://st.kp.yandex.net/images/film_big/'.$id.'.jpg[/img]<br />';
echo 'Жанр:'.$new['genre']."\n";
echo 'Оператор:'.$new['operator']."\n";
echo 'Время:'.$new['time']."\n";
echo 'Рейтинг IMDb:'.$new['imdb']."\n";
echo 'Сценарий:'.$new['script']."\n";
echo 'Слоган:'.$new['slogan']."\n";
echo 'Описание:'.$new['description']."\n";

*/
mysql_close($link);
?>
