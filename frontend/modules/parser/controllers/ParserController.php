<?php
	/**
	 * Created by PhpStorm.
	 * User: yaros
	 * Date: 22.11.2017
	 * Time: 17:25
	 */
	
	namespace app\modules\parser\controllers;
	
	
	use yii\web\Controller;
	use app\models\Parser;
	
	
	class ParserController extends Controller
	{
		
		public function actionIndex()
		{
			$url = 'https://enko.com.ua/shop/telefoniya/mobilnye-telefony/';
			$model = new Parser($url);
			$items = $model->getProductItems(5);
			
			foreach ($items as $item) {
				var_dump($item);
			}
			
			return $this->render('index');
		}
	}