<?php
	
	namespace console\controllers;
	
	
	use app\modules\parser\Parser;
	use yii\console\Controller;

	
	class TestController extends Controller
	{
		public $url = 'https://enko.com.ua/shop/telefoniya/mobilnye-telefony/';
		
		public function actionIndex()
		{
			echo "Yes, service is running.";
		}
		
		public function actionList()
		{
		
		
		}
		
		public function actionParse()
		{
			$parsingResults = Parser::getParsingResult($this->url);
			
			if (!$parsingResults) {
				echo 'Вознкла проблема' . "\n";
				return 1;
			} else {
				return 0;
			}
		}
		
	}