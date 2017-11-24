<?php
	
	namespace app\modules\parser;
	
	/**
	 * parser module definition class
	 */
	class Parser extends \yii\base\Module
	{
		/**
		 * @inheritdoc
		 */
		public $layout = '/parser';
		public $controllerNamespace = 'app\modules\parser\controllers';
		
		/**
		 * @inheritdoc
		 */
		public function init()
		{
			parent::init();
			
			// custom initialization code goes here
		}
	}
