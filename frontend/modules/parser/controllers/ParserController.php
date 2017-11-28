<?php
	
	namespace app\modules\parser\controllers;
	
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
			
			$model = new Product();
			
			$parsingResults = $model->parseIt($this->url);
			
			$model->findIdenticalNames($parsingResults);
			
			if ($parsingResults != NULL && is_array($parsingResults)) {
				foreach ($parsingResults as $productItem) {
					$model = new Product();
					$model->load(['Product' => $productItem]);
					$model->save();
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