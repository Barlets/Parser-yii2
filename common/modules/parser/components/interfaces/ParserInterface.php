<?php
	
	namespace common\modules\parser\components\interfaces;
	
	interface ParserInterface
	{
		/**
		 * @param string $url
		 * @return mixed
		 */
		public static function getParsingResult($url = '');
		
	}