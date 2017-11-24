<?php
	
	namespace app\components\interfaces\parser;
	
	interface ParserInterface
	{
		public function __construct($url);
		
		public function getProductItems();
		
		public function getSite();
		
		public function getData();
		
		public function getName($item);
		
		public function getImg($item);
		
		public function getPrice($item);
		
		public function getLink($item);
		
		public function getBaseUrl();
		
		public function parse($pattern, $item);
		
	}