<?php
	
	use yii\helpers\Html;

?>

<h2>Результаты парсинга:</h2>
<p><?=Html::a('Удалить все результаты', ['default/delete-all']) ?></p>
<table class="table table-hover">
	<thead>
	<tr>
		<th>id</th>
		<th>Название товара</th>
		<th>Ссылка на товар</th>
		<th>Цена</th>
		<th>Изображение</th>
	</tr>
	</thead>
	
	<?php foreach ($model as $product) : ?>

		<tr>
			<td><?= Html::tag('p', $product['id']) ?></td>
			<td><?= Html::tag('p', $product['name']) ?></td>
			<td><?= Html::a('Просмотр на сайте', $product['base_url'] . $product['link']) ?></td>
			<td><?= Html::tag('p', $product['price']) ?></td>
			<td><?= Html::a(Html::img($product['base_url'] . $product['img'], ['width' => 100]), $product['base_url'] . $product['img']); ?></td>
			<td></td>
		</tr>
	
	<?php endforeach; ?>

</table>

