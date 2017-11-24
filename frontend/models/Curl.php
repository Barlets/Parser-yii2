<?php
	/**
	 * Created by PhpStorm.
	 * User: yaros
	 * Date: 23.11.2017
	 * Time: 12:41
	 */
	
	namespace app\models;
	
	
	class Curl
	{
		public static function curl($url)
		{
			//Инициализируем сеанс
			$curl = curl_init();
			
			//Указываем заголовок
			$headers = 'Chrome/62.0.3202.94';
			
			$options = [
				 CURLOPT_URL            => $url, //Указываем адрес страницы
				 CURLOPT_RETURNTRANSFER => 1, //Ответ сервера сохранять в переменную, а не на экран
				 CURLOPT_FOLLOWLOCATION => 1, //Переходить по редиректам
				 CURLOPT_USERAGENT      => $headers,
			];
			
			//Подключаем опции:
			curl_setopt_array($curl, $options);
			
			//Выполняем запрос:
			$result = curl_exec($curl);
			
			//Отлавливаем ошибки подключения
			if ($result === false) {
				echo "Ошибка CURL: " . curl_error($curl);
				curl_close($curl);
				return false;
			} else {
				return $result;
			}
		}
		
	}