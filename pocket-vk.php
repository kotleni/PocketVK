<?php
include "src/vklib.php";
include "src/ColoredLib.php";
include "src/PluginLoader.php";
include "src/API.php";

$clr = new ColoredLib();
$api = new API($clr);
$db = $api->loadConfig(); // загрузить конфиг
if(strlen($db['token']) < 1) // если токен не введен
	exit(PHP_EOL . $clr->string("[Ошибка] " , "light_red" , null) . 'Пожалуйста впишите свой токен в конфиг. Получить его можно на сайте ' . $clr->string('vkhost.github.io' , "light_green" , true ));
$vk_api = new VKLIB($db['token'] , '5.101'); // получить VKAPI
$api->syncVKAPI($vk_api);

date_default_timezone_set($db['timezone']); // установить часовой пояс

$api->print(
	"Запуск " .
	$clr->string('PocketVK ' , 'yellow' , null) .
	'v' . $api->api_v) . PHP_EOL;

	$info = $vk_api->method('account.getProfileInfo', array());



$api->print(
	$clr->string('Пользователь: ' , 'light_purple' , null) . PHP_EOL
	. '  Ссылка : vk.com/' .
	$info['response']['screen_name'] . PHP_EOL .'  Имя    : ' .
	$info['response']['first_name'] . ' ' .
	$info['response']['last_name'] . PHP_EOL);

$plLoader = new PluginLoader($api); // Загрузчик плагинов

print PHP_EOL;

while(true){
	$plLoader->loop();

	$sec = (int) date("s"); // получить текущие секунды
	$api->print(
		$clr->string("OK! " , 'light_blue' , 'cyan') .
		$clr->string('[' . $sec . ']' , 'white' , 'cyan') .
		PHP_EOL);

	$tm = date('H:i');
	 
	if($tm == $db['sleep'][0] and $db['auto-sleep'] == true){
		$api->print(
			$clr->string('Работа остановлена до: ' , 'yellow' , null) .
			$clr->string($db['sleep'][1] , 'green' , null) .
			' (' . $clr->string(seconds($tm) - seconds($db['sleep'][1]) , 'green' , null) .
			' сек)' .PHP_EOL);
		
		if($db['use']['status'] == true)
		$vk_api->setStatus(urlencode($arr['sleep_title']));
		while(true){
			if(date('H:i') == $db['sleep'][1])
			break;
			sleep(60 - (int) date("s") + $db['delay']);
		}
	}else{
		if($db['dynamic-status'] == true){ // включен динамический статус
		$banned = $vk_api->getBanned(); // получить данные о ЧС
		$c = sizeof($db['textes']); // количество динамических статусов
		$r = random_int(0 , $c - 1); // рандом выбор

		$status =
		$db['title'] . ' ' . num_emoji(date('H')) . ':' . num_emoji(date('i')) .' | '  . '(В чс: ' . $banned['response']['count'] . ') '.PHP_EOL.
		$db['textes'][$r];
	
		$vk_api->setStatus(urlencode($status)); // установить статус
		$api->print(
			$clr->string('Установлен статус: ' , 'light_red' , null) .PHP_EOL.
			$status . PHP_EOL);
		} // if use->online == true

		sleep(60 - $sec + $db['delay']); // сон до начала минуты
	}
}


/*
	Функции
*/
function seconds($time){
	$time = explode(':', $time . ':00');
	return ($time[0]*3600) + ($time[1]*60) + $time[2];
}

function num_emoji($num){
	global $api;
	$o = $num;
	foreach($api->emoji as $key => $em){
		$o = str_replace($key , $em , $o);
	}
	return $o;
}