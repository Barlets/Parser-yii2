<?php
	
	use yii\db\Migration;
	
	/**
	 * Handles the creation of table `product`.
	 */
	class m171124_180452_create_product_table extends Migration
	{
		/**
		 * @inheritdoc
		 */
		public function up()
		{
			$this->createTable('product', [
				 'id'       => $this->primaryKey(),
				 'name'     => $this->string(),
				 'img'      => $this->string(),
				 'link'     => $this->string(),
				 'base_url' => $this->string(),
				 'price'    => $this->integer(),
				 'date'     => $this->date()
			]);
		}
		
		/**
		 * @inheritdoc
		 */
		public function down()
		{
			$this->dropTable('product');
		}
	}
