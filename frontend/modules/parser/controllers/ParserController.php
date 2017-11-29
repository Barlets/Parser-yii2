<?php
	
	namespace app\modules\parser\controllers;
	
	use app\modules\parser\Parser;
	use yii\web\Controller;
	use app\models\Product;
	
	class ParserController extends Controller
	{
		private $url = 'https://enko.com.ua/shop/telefoniya/mobilnye-telefony/';
		
		public function actionIndex()
		{
			return $this->render('index');
		}
		
		public function actionList()
		{
			$model = new Product();
			
			$product = $model->getAll();
			
			return $this->render('list', [
				 'model' => $product
			]);
		}
		
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
			}
			
			return $this->redirect('list');
		}
		
		public function actionDeleteAll()
		{
			Product::deleteAll();
			
			return $this->redirect('list');
		}
		
	}