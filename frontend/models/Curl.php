<?php
	
	namespace app\models;
	
	
	class Curl
	{
		public static function curl($url, $post_data = NULL, $cooliefile = NULL)
		{
			//Инициализируем сеанс
			$curl = curl_init();
			
			//Указываем заголовок
			$headers = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36';
			
			$options = [
				//CURLOPT_HEADER => 1, //включает заголовок запроса
				//CURLOPT_NOBODY => 1, //выключает тело запроса
				//CURLOPT_COOKIEFILE     => $cooliefile,
				//CURLOPT_COOKIEJAR      => $cooliefile,
				 CURLOPT_URL            => $url, //Указываем адрес страницы
				 CURLOPT_RETURNTRANSFER => 1, //Ответ сервера сохранять в переменную, а не на экран
				 CURLOPT_FOLLOWLOCATION => 1, //Переходить по редиректам
				 CURLOPT_USERAGENT      => $headers,
				 CURLOPT_SSL_VERIFYHOST => false, //Отключение проверок SSL
				 CURLOPT_SSL_VERIFYPEER => false, //Отключение проверок SSL
				 CURLOPT_TIMEOUT        => 9,
				 CURLOPT_CONNECTTIMEOUT => 6,
			];
			
			if ($post_data) {
				$options = [
					 CURLOPT_POSTFIELDS => $post_data //Все данные, передаваемые в HTTP POST-запросе
				];
			}
			
			//Подключаем опции:
			curl_setopt_array($curl, $options);
			
			//Выполняем запрос:
			$html = curl_exec($curl);
			
			//Отлавливаем ошибки подключения
			if ($html === false) {
				echo "Ошибка CURL: " . curl_error($curl);
				curl_close($curl);
				return false;
			} else {
				curl_close($curl);
				return $html;
			}
			
		}
		
	}