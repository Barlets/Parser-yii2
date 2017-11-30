<?php
	
	namespace common\modules\parser\console\controllers;
	
	
	use common\modules\parser\models\Product;
	use common\modules\parser\Parser;
	use yii\console\Controller;
	
	
	class TestController extends Controller
	{
		public $url = 'https://enko.com.ua/shop/telefoniya/mobilnye-telefony/';
		
		/**
		 *default test action
		 */
		public function actionIndex()
		{
			echo "Yes, service is running.";
		}
		
		/**
		 * Execute parsing
		 * @return int
		 */
		public function actionParse()
		{
			$parsingResults = Parser::getParsingResult($this->url);
			
			$model = new Product();
			$model->findIdenticalNames($parsingResults, true);
			
			if ($parsingResults != NULL && is_array($parsingResults)) {
				
				foreach ($parsingResults as $productItem) {
					$model = new Product();
					$model->load(['Product' => $productItem]);
					
					if ($model->validate($productItem)) {
						$model->save();
					}
					
				}
				echo 'Парсинг выполнен и результат сохранён в баз банных';
				return 1;
			} else {
				echo 'Ошибка, что-то пошло не так';
				return 0;
			}
			
		}
		
		/**
		 * Execute deleting all entries in db
		 * @return int
		 */
		public function actionDeleteAll()
		{
			if (Product::deleteAll()) {
				echo 'Все записи из базы данных успешно удалены';
				return 1;
			} else {
				echo 'Ошибка удаления';
				return 0;
			}
			
		}
		
	}