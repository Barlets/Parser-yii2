<?php
	
	namespace common\modules\parser;
	
	use common\modules\parser\components\interfaces\ParserInterface;
	use common\modules\parser\models\Curl;
	use yii\base\Module;
	
	/**
	 * default module definition class
	 */
	class Parser extends Module implements ParserInterface
	{
		/**
		 * @property string $layout
		 * @property string $controllerNamespace
		 * @property string $url
		 * @property Parser[] $products
		 */
		public $layout = '/parser';
		public $controllerNamespace = 'common\modules\parser\controllers';
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
		
		/**
		 * @param string $url
		 * @return array
		 */
		public static function getParsingResult($url = '')
		{
			self::$url = $url;
			self::getData();
			return self::$products;
		}
		
		/**
		 * @return array
		 */
		private static function getData()
		{
			$product = [self::$url];
			foreach (self::getSite() as $item) {
				$product['name'] = self::getName($item);
				$product['img'] = self::getImg($item);
				$product['link'] = self::getLink($item);
				$product['base_url'] = self::getBaseUrl();
				$product['price'] = self::getPrice($item);
				array_push(self::$products, $product);
			}
		}
		
		/**
		 * @return array
		 */
		private static function getSite()
		{
			$site = Curl::curl(self::$url);
			
			$pattern = "/\<div[^\>]*class\=\"catalog_item\".*/";
			preg_match_all($pattern, $site, $catalog_item);
			return array_pop($catalog_item);
		}
		
		/**
		 * @param $item
		 * @return array
		 */
		private static function getName($item)
		{
			$pattern = '/class="cat_title".+?<h2\>(.+?)<\/h2>/';
			return self::parse($pattern, $item);
		}
		
		/**
		 * @param $item
		 * @return array
		 */
		private static function getImg($item)
		{
			$pattern = '/<img.+?src="(.+?)".+?/';
			return self::parse($pattern, $item);
		}
		
		/**
		 * @param $item
		 * @return int
		 */
		private static function getPrice($item)
		{
			$pattern = '/class="iprice_c">(.+?)<\/span>/';
			return $price_int = (int)preg_replace('/\s/', '', self::parse($pattern, $item));
		}
		
		/**
		 * @param $item
		 * @return array
		 */
		private static function getLink($item)
		{
			$pattern = '/<a.+?href="(.+?)".+?/';
			return self::parse($pattern, $item);
		}
		
		/**
		 * @return array
		 */
		private static function getBaseUrl()
		{
			$pattern = '/(https:\/\/.+?)\//';
			return self::parse($pattern, self::$url);
		}
		
		/**
		 * @param $pattern
		 * @param $item
		 * @return array
		 */
		private static function parse($pattern, $item)
		{
			preg_match_all($pattern, $item, $match);
			$result = array_pop($match);
			return array_pop($result);
		}

		
	}
