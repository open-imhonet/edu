<?php
//возвращает коэффициент корреляции пирсона между двумя пользователями.
if (!defined('RUN')) {
   $critics = array(
      "user1" => array(
//фильм => оценка
      "a" => "1",
      "b" => "2",
      "c" => "3",
      "d" => "4",
      "e" => "5"
      ),
      
      "user2" => array(
      "a" => "5",
      "b" => "5",
      "c" => "3",
      "d" => "4",
      "x" => "5",
      "e" => "5"
      ),
      
      "user3" => array(
      "qqw" => "3",
      "wqq" => "5",
      "wqe" => "8"
      )
   );
echo sim_pearson($critics,'user1','user2');
}

//Функция вычисления коореляции Пирсона
function pirson($kritik1,$kritik2,$kol){
//Вычисляем сумму всех оценок фильмам для каждого критика
$length = count($kritik1);
for($i = 0; $i<$length; $i++){
$sum1+=$kritik1[$i];
$sum2+=$kritik2[$i];
//Вычисляем сумму, полученную возведением в квадрат каждой оценки
$pow1+=pow($kritik1[$i],2);
$pow2+=pow($kritik2[$i],2);
//Вычисляем сумму произведений
$psum+=$kritik1[$i]*$kritik2[$i];
}
//Приступаем к вычислению коофициента
$num = $psum-($sum1*$sum2/$kol);
$itog = sqrt(($pow1-pow($sum1,2)/$kol)*($pow2-pow($sum2,2)/$kol));
$res = $num/$itog;
return $res;
}

function sim_pearson($prefs, $p1, $p2) {
	# Если у когото нет вообще предпочтений
	if (false==isset($prefs[$p1]) or false==isset($prefs[$p2])){
		return false;
	 }
	# Получить список предметов, оцененных обоими
	$si=array();
	$n=0;
	foreach($prefs[$p1] as $item => $value) {
		if (array_key_exists($item, $prefs[$p2])){
			$kritik1[$n]=	$prefs[$p1][$item];
			$kritik2[$n]=	$prefs[$p2][$item];
			$n++;
			}
	}
	# Если нет ни одной общей оценки, вернуть 0
	if ($n==0){
		return 0;
		}
$result = pirson($kritik1,$kritik2,$n);
return $result;
}	

?>
