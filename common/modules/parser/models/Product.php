<?php
	
	namespace common\modules\parser\models;
	
	use yii\db\ActiveRecord;
	use yii\helpers\ArrayHelper;
	
	/**
	 * @property array $namesHashFromDb
	 * @property mixed $namesFromDB
	 */
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
		
		/**
		 * @return Product[]|array|ActiveRecord[]
		 */
		public static function getAll()
		{
			return Product::find()->asArray()->all();
		}
		
		/**
		 * @param $parsingResults []
		 * @param bool $delete
		 * @return bool
		 */
		public function findIdenticalNames($parsingResults, $delete)
		{
			$namesInDbHash = $this->getNamesHashFromDb();
			$namesInParsed = $this->getNamesFromParsedSite($parsingResults);
			
			if ($delete) {
				return $this->findAndDeleteExistingRows($namesInDbHash, $namesInParsed);
			}
		}
		
		/**
		 * @return array
		 */
		public function getNamesFromDB()
		{
			return ArrayHelper::map(Product::find()->all(), 'id', 'name');
		}
		
		/**
		 * @param $parsingResults []
		 * @return array
		 */
		public function getNamesFromParsedSite($parsingResults)
		{
			return ArrayHelper::getColumn($parsingResults, 'name');
		}
		
		/**
		 * @param $namesInDbHash
		 * @param $namesInParsed
		 * @return bool
		 */
		public function findAndDeleteExistingRows($namesInDbHash, $namesInParsed)
		{
			foreach ($namesInParsed as $key => $value) {
				$namesInParsedHash[$key] = md5($value);
				
				if (in_array($namesInParsedHash, $namesInDbHash)) {
					return true;
				} else {
					$id = $this->getProductId($namesInParsedHash, $namesInDbHash);
					$models = $this->getProductById($id);
					$this->deleteDuplicateRow($models);
				}
			}
		}
		
		/**
		 * @return array $namesInDbHash[]
		 */
		public function getNamesHashFromDb()
		{
			$namesInDbHash = [];
			$namesInDb = $this->getNamesFromDB();
			foreach ($namesInDb as $key => $value) {
				$namesInDbHash[$key] = md5($value);
			}
			return $namesInDbHash;
		}
		
		/**
		 * @param $id
		 * @return Product[]|array|ActiveRecord[]
		 */
		public function getProductById($id)
		{
			return Product::find()->where(['id' => $id])->all();
		}
		
		/**
		 * @param $result
		 * @param $namesInDbHash
		 * @return false|int|string
		 */
		public function getProductId($result, $namesInDbHash)
		{
			return array_search(implode($result), $namesInDbHash);
		}
		
		/**
		 * @param $models
		 */
		public function deleteDuplicateRow($models)
		{
			foreach ($models as $model) {
				$model->delete();
			}
		}
		
		
	}