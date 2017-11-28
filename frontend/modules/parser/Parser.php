<?php
	
	namespace app\modules\parser;
	


	use app\modules\parser\components\interfaces\parser\ParserInterface;
	use app\modules\parser\components\models\Curl;
	use yii\base\Module;

	
	/**
	 * parser module definition class
	 */
	class Parser extends Module implements ParserInterface
	{
		/**
		 * @inheritdoc
		 */
		public $layout = '/parser';
		public $controllerNamespace = 'app\modules\parser\controllers';
		private static $url;
		private static $products = [];
		
		/**
		 * @inheritdoc
		 */
		public function init()
		{
			parent::init();
			
			// custom initialization code goes here
		}
		
		public static function getParsingResult($url = '')
		{
			self::$url = $url;
			self::getData();
			return self::$products;
		}
		
		private static function getData()
		{
			$product = [self::$url];
			foreach (self::getSite() as $item) {
				$product['id'] = self::setTempId();
				$product['name'] = self::getName($item);
				$product['img'] = self::getImg($item);
				$product['link'] = self::getLink($item);
				$product['base_url'] = self::getBaseUrl();
				$product['price'] = self::getPrice($item);
				array_push(self::$products, $product);
			}
		}
		
		private static function getSite()
		{
			$site = Curl::curl(self::$url);
			
			$pattern = "/\<div[^\>]*class\=\"catalog_item\".*/";
			preg_match_all($pattern, $site, $catalog_item);
			return array_pop($catalog_item);
		}
		
		private static function getName($item)
		{
			$pattern = '/class="cat_title".+?<h2\>(.+?)<\/h2>/';
			return self::parse($pattern, $item);
		}
		
		private static function getImg($item)
		{
			$pattern = '/<img.+?src="(.+?)".+?/';
			return self::parse($pattern, $item);
		}
		
		private static function getPrice($item)
		{
			$pattern = '/class="iprice_c">(.+?)<\/span>/';
			return $price_int = (int)preg_replace('/\s/', '', self::parse($pattern, $item));
		}
		
		private static function getLink($item)
		{
			$pattern = '/<a.+?href="(.+?)".+?/';
			return self::parse($pattern, $item);
		}
		
		private static function getBaseUrl()
		{
			$pattern = '/(https:\/\/.+?)\//';
			return self::parse($pattern, self::$url);
		}
		
		private static function parse($pattern, $item)
		{
			preg_match_all($pattern, $item, $match);
			$result = array_pop($match);
			return array_pop($result);
		}
		
		private static function setTempId()
		{
			return count(self::$products);
		}
		
		
	}
