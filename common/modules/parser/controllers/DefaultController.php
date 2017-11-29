<?php
	
	namespace common\modules\parser\controllers;
	
	use common\modules\parser\models\Product;
	use common\modules\parser\Parser;
	use yii\web\Controller;
	
	/**
	 * Default controller for the `parser` module
	 */
	class DefaultController extends Controller
	{
		private $url = 'https://enko.com.ua/shop/telefoniya/mobilnye-telefony/';
		
		/**
		 * Renders the index view for the module
		 * @return string
		 */
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
		
		/**
		 * Executing parsing script
		 * @return \yii\web\Response
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
			}
			
			return $this->redirect('list');
		}
		
		/**
		 * Execute deleting all entries in db
		 * @return \yii\web\Response
		 */
		public function actionDeleteAll()
		{
			Product::deleteAll();
			
			return $this->redirect('list');
		}
		
	}