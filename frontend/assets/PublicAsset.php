<?php
	/**
	 * Created by PhpStorm.
	 * User: yaros
	 * Date: 22.11.2017
	 * Time: 16:55
	 */
	
	namespace frontend\assets;
	use yii\web\AssetBundle;
	
	/**
	 * Additional frontend application asset bundle.
	 */
	class PublicAsset extends AssetBundle
	{
		public $basePath = '@webroot';
		public $baseUrl = '@web';
		public $css = [
			 'css/site.css',
			 'public/css/style.css',
		];
		public $js = [
		];
		public $depends = [

		];
	}