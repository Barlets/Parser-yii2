<?php
	
	namespace app\models;
	
	use app\components\interfaces\parser\ParserInterface;
	
	
	class Parser implements ParserInterface
	{
		private $url;
		private $product;
		
		public function __construct($url)
		{
			$this->url = $url;
			$this->getData();
		}
		
		public function getProductItems($items_count = 5)
		{
			return array_chunk($this->product, $items_count);
		}
		
		public function getData()
		{
			$product = [];
			//Получаем все дочерние div в виде массива
			foreach ($this->getSite() as $item) {
				//Получаем название товара
				$product[]['name'] = $this->getName($item);
				//Получаем изображение товара
				$product[]['img'] = $this->getImg($item);
				//Получаем ссылку на товар
				$product[]['link'] = $this->getLink($item);
				//Получаем адрес сайта
				$product[]['base_url'] = $this->getBaseUrl();
				//Получаем цену товара
				$product[]['price'] = $this->getPrice($item);
			}
			return $this->product = $product;
		}
		
		public function getSite()
		{
			$site = Curl::curl($this->url);
			
			//Получаем массив div со всей продукцией на странице
			$pattern = "/\<div[^\>]*class\=\"catalog_item\".*/";
			preg_match_all($pattern, $site, $catalog_item);
			return array_pop($catalog_item);
		}
		
		public function getName($item)
		{
			$pattern = '/class="cat_title".+?<h2\>(.+?)<\/h2>/';
			return $this->parse($pattern, $item);
		}
		
		public function getImg($item)
		{
			$pattern = '/<img.+?src="(.+?)".+?/';
			return $this->parse($pattern, $item);
		}
		
		public function getPrice($item)
		{
			$pattern = '/class="iprice_c">(.+?)<\/span>/';
			return $price_int = (int)preg_replace('/\s/', '', $this->parse($pattern, $item));
		}
		
		public function getLink($item)
		{
			$pattern = '/<a.+?href="(.+?)".+?/';
			return $this->parse($pattern, $item);
		}
		
		public function getBaseUrl()
		{
			$pattern = '/(https:\/\/.+?)\//';
			return $this->parse($pattern, $this->url);
		}
		
		public function parse($pattern, $item)
		{
			preg_match_all($pattern, $item, $match);
			$result = array_pop($match);
			return array_pop($result);
		}
		
		
	}