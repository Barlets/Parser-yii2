<?php
	
	namespace app\models;
	
	use app\components\interfaces\parser\ParserInterface;
	
	
	class Parser implements ParserInterface
	{
		private $url;
		private $product = [];
		
		public function __construct($url)
		{
			$this->url = $url;
			$this->getData();
		}
		
		public function getProduct()
		{
			return $this->product;
		}
		
		public function getData()
		{
			//Получаем все дочерние div в виде массива
			foreach ($this->getSite() as $item) {
				//Получаем название товара
				$this->getName($item);
				//Получаем изображение товара
				$this->getImg($item);
				//Получаем цену товара
				$this->getPrice($item);
				//Получаем ссылку на товар
				$this->getLink($item);
				
				//var_dump($this->product);
			}
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
			return $this->product['name'] = $this->parse($pattern, $item);
		}
		
		public function getImg($item)
		{
			$pattern = '/<img.+?src="(.+?)".+?/';
			return $this->product['img'] = $this->parse($pattern, $item);
		}
		
		public function getPrice($item)
		{
			$pattern = '/class="iprice_c">(.+?)<\/span>/';
			return $this->product['price'] = $this->parse($pattern, $item);
		}
		
		public function getLink($item)
		{
			$pattern = '/<a.+?href="(.+?)".+?/';
			return $this->product['link'] = $this->parse($pattern, $item);
		}
		
		public function parse($pattern, $item)
		{
			preg_match_all($pattern, $item, $match);
			$result = array_pop($match);
			return array_pop($result);
		}
		
	}