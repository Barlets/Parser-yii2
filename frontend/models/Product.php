<?php
	
	namespace app\models;
	
	use Yii;
	use yii\db\ActiveRecord;
	use yii\helpers\ArrayHelper;
	use app\models\Parser;
	
	class Product extends ActiveRecord
	{
		public static function tableName()
		{
			return 'product';
		}
		
		/**
		 * @inheritdoc
		 */
		public function rules()
		{
			return [
				 [['name', 'img', 'link', 'price'], 'required'],
				 [['name', 'img', 'link', 'base_url'], 'string'],
				 [['price'], 'integer'],
				 [['date'], 'date', 'format' => 'php:Y-m-d'],
				 [['date'], 'default', 'value' => date('Y-m-d')],
			];
		}
		
		/**
		 * @inheritdoc
		 */
		public function attributeLabels()
		{
			return [
				 'id'       => 'ID',
				 'name'     => 'Product Name',
				 'img'      => 'Product Image',
				 'link'     => 'Product Link',
				 'base_url' => 'Base URL',
				 'price'    => 'Product Price',
				 'date'     => 'Date'
			];
		}
		
		
		public static function getAll()
		{
			return Product::find()->asArray()->all();
		}
		
		public function parseIt($url)
		{
			return Parser::getParsingResult($url);
		}
		
		public function findIdenticalNames($parsingResults)
		{
			$namesInDbHash = [];
			$namesInDb = $this->getNamesFromDB();
			foreach ($namesInDb as $key => $value) {
				$namesInDbHash[$key] = md5($value);
			}

			$namesInParsed = $this->getNamesFromParsedSite($parsingResults);
			
			foreach ($namesInParsed as $key => $value) {
				
				$result = [$key => md5($value)];

				if (in_array($result, $namesInDbHash)) {
					echo 'true';
					return true;
				} else {
					$key = array_search(implode($result), $namesInDbHash);
					$models = Product::find()->where(['id' => $key])->all();
					foreach ($models as $model) {
						$model->delete();
					}
				}
			}
			
		}
		
		public function getNamesFromDB()
		{
			return ArrayHelper::map(Product::find()->all(), 'id', 'name');
		}
		
		public function getNamesFromParsedSite($parsingResults)
		{
			return ArrayHelper::map($parsingResults, 'id', 'name');
		}
	}