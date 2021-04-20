<?php
$now = new Datetime();

$times = array(
	'6:49',
	'10:49',
	'12:54',
	'15:49',
	'19:54'
);

// selectタグ用の初期設定を連想配列でセット
$form_group = array(
	array(
		'unit' => 'hour', // 英語単位
		'unit_ja' => '時', // 日本語単位
		'range_to' => 23, // 最大値
		'format' => 'H' //format文字列
	),
	array(
		'unit' => 'min',
		'unit_ja' => '分',
		'range_to' => 59,
		'format' => 'i'
	),
	array(
		'unit' => 'sec',
		'unit_ja' => '秒',
		'range_to' => 59,
		'format' => 's'
	)
);

// ページャーの準備
$prev_offset = $offset - $limit;
$next_offset = $offset + $limit;

// 総件数÷1ページに表示する件数 を切り上げたものが総ページ数
$pages = ceil($total / $limit);

// 前へボタンのoffsetがマイナスになってしまう場合はdisabled
$is_prev = $prev_offset < 0 ? 'disabled' : '';

// 次へボタンのoffsetがtotal以下の場合はdisabled
$is_next = $offset + $limit >= $total ? 'disabled': '';

// 現在のページ数
$is_current = $offset / $limit + 1;

?>

<div class="row">
	<div class="col-md-6">
		<h2>データ追加</h2>
		<form action="insert.php" method="post">
			<div class="form-group">
				<label for="products">商品名</label>
				<select id="products" name="products" class="form-control">
					<?php foreach($productsList as $products):?>
					<option
						value="<?=$products['id']?>">
						<?=$products['name']?>
					</option>
					<?php endforeach;?>
				</select>
			</div>
			<div class="form-group">
				<label for="supplier">仕入れ先</label>
				<select id="supplier" name="supplier" class="form-control">
					<?php foreach($suppliersList as $supplier):?>
					<option
						value="<?=$supplier['id']?>">
						<?=$supplier['name']?>
					</option>
					<?php endforeach;?>
				</select>
			</div>
			<div class="form-group">
				<label for="quantity">入荷数</label>
				<input id="quantity" name="quantity" class="form-control" type="number" required></p>
			</div>
			<button class="btn btn-primary" type="submit">追加</button>
		</form>
	</div>

	<div class="col-md-6">
		<h2>データ検索</h2>

		<h3>指定された時刻から一時間以内のデータ</h3>
		<form class="form-row mb-3" method="get" action="search.php">
			<div class="form-group col-md-6">
				<?php // $datetime_from が設定されているか確認してデフォルト値をセット
						if(!empty($datetime_from)):?>
				<input type="text" class="form-control datepicker" placeholder="入力日" name="date-from"
					value="<?= $datetime_from->format('Y/m/d')?>" />
				<?php else: ?>
				<input type="text" class="form-control datepicker" placeholder="入力日" name="date-from"
					value="<?= $now->format('Y/m/d') ?>" />
				<?php endif; ?>
			</div>

			<div class="form-group col-md-6">
				<select name="radio-time" id="radio-time" class="form-control">
					<?php for($i = 0; $i < count($times); $i++):?>
					<?php // selected を付与して検索結果で選択された数値を維持する
							if(!empty($datetime_from) && $values[$i] === $datetime_from->format('H:i')){
								$selected = 'selected';
							} else {
								$selected = '';
							}
							?>

					<option value="<?=$times[$i]?>" <?=$selected?>><?=$times[$i]?> ～</option>
					<?php endfor;?>
				</select>
			</div>

			<button class="btn btn-primary d-block" type="submit">検索</button>
		</form>

		<h3>手動範囲選択</h3>
		<form class="mb-3" action="search.php" method="get">
			<label for="no-select-datetime">
				<input type="checkbox" name="no-select-datetime" id="no-select-datetime">
				日時を指定しない
			</label>
			<div class="form-row">
				<div class="form-group col-md-3">
					<?php // $datetime_from が設定されているか確認してデフォルト値をセット
							if(!empty($datetime_from)):?>
					<input type="text" class="form-control datepicker" placeholder="入力日" name="date-from"
						value="<?= $datetime_from->format('Y/m/d')?>" />
					<?php else: ?>
					<input type="text" class="form-control datepicker" placeholder="入力日" name="date-from"
						value="<?= $now->format('Y/m/d');?>" />
					<?php endif; ?>
				</div>

				<?php // $form_group にセットした情報をもとに foreach で回す
						foreach($form_group as $form): ?>
				<div class="form-group col-md-2">
					<select
						name="<?= $form['unit'] ?>-from"
						class="form-control">
						<option value="00">00<?= $form['unit_ja'] ?>
						</option>
						<?php foreach(range(1,$form['range_to']) as $value): ?>

						<?php // selected を付与して選択された数値を維持する
								if(!empty($datetime_from) && str_pad($value,2,0,STR_PAD_LEFT) == $datetime_from->format($form['format'])){
									$selected = 'selected';
								} else {
									$selected = '';
								}
								?>

						<option
							value="<?=str_pad($value,2,0,STR_PAD_LEFT)?>"
							<?= $selected?>><?=str_pad($value,2,0,STR_PAD_LEFT)?><?= $form['unit_ja']?>
						</option>
						<?php endforeach; ?>
					</select>
				</div>
				<?php endforeach;?>
				<div class="col-md-3">から</div>
			</div><!-- /.form-row -->


			<div class="form-row">
				<div class="form-group col-md-3">
					<?php // $datetime_from が設定されているか確認してデフォルト値をセット
							if(!empty($datetime_to)):?>
					<input type="text" class="form-control datepicker" placeholder="入力日" name="date-to"
						value="<?= $datetime_to->format('Y/m/d')?>" />
					<?php else: ?>
					<input type="text" class="form-control datepicker" placeholder="入力日" name="date-to"
						value="<?= $now->format('Y/m/d')?>" />
					<?php endif; ?>
				</div>

				<?php // $form_group にセットした情報をもとに foreach で回す
						foreach($form_group as $form): ?>
				<div class="form-group col-md-2">
					<select
						name="<?= $form['unit'] ?>-to"
						class="form-control">
						<option value="00">00<?= $form['unit_ja'] ?>
						</option>
						<?php foreach(range(1,$form['range_to']) as $value): ?>

						<?php // selected を付与して検索結果で選択された数値を維持する
								if(!empty($datetime_to) && str_pad($value,2,0,STR_PAD_LEFT) == $datetime_to->format($form['format'])){
									$selected = 'selected';
								} else {
									$selected = '';
								}
								?>

						<option
							value="<?=str_pad($value,2,0,STR_PAD_LEFT)?>"
							<?= $selected?>><?=str_pad($value,2,0,STR_PAD_LEFT)?><?= $form['unit_ja']?>
						</option>
						<?php endforeach; ?>
					</select>
				</div>
				<?php endforeach;?>
				<div class="col-md-3">まで</div>
			</div><!-- /.form-row -->

			<div class="form-group">
				<label for="products">商品名</label>
				<select id="products" name="products" class="form-control">
					<option value="">--</option>
					<?php foreach($productsList as $products):?>
					<?php // selected を付与して検索結果で選択された数値を維持する
					if(!empty($_GET['products']) && $_GET['products'] == $products['id']){
						$selected = 'selected';
					} else {
						$selected = '';
					}
					?>
					<option
						value="<?=$products['id']?>"
						<?=$selected?>>
						<?=$products['name']?>
					</option>
					<?php endforeach;?>
				</select>
			</div>


			<div class="form-group">
				<label for="supplier">仕入れ先</label>
				<select id="supplier" name="supplier" class="form-control">
					<option value="">--</option>
					<?php foreach($suppliersList as $supplier):?>
					<?php // selected を付与して検索結果で選択された数値を維持する
					if(!empty($_GET['supplier']) && $_GET['supplier'] == $supplier['id']){
						$selected = 'selected';
					} else {
						$selected = '';
					}
					?>
					<option
						value="<?=$supplier['id']?>"
						<?=$selected?>>
						<?=$supplier['name']?>
					</option>
					<?php endforeach;?>
				</select>
			</div>
			<button class="btn btn-primary" type="submit">検索</button>
		</form>


	</div>
</div><!-- .row -->

<div class="my-3">
	<h2>データ一覧</h2>
	<p>全<?=$total?>件のデータ</p>
	<a class="btn btn-primary mb-2" href="./">すべてのデータを表示</a>
	<a class="btn btn-primary mb-2"
		href="./download/csv.php<?=getAllParams(false);?>">結果をダウンロード</a>

	<?php if(!empty($ordersList)): ?>
	<table class="table">
		<tbody>
			<tr>
				<th scope="col">No.</th>
				<th scope="col">商品</th>
				<th scope="col">仕入れ先</th>
				<th scope="col">入荷日</th>
				<th scope="col">入荷数</th>
			</tr>

			<?php // 配列からデータを取得、出力しながらforeachで回す
					foreach($ordersList as $data): ?>
			<tr>
				<th scope="row"><?= $data['id'] ?>
				</th>
				<td><?= $data['products'] ?>
				</td>
				<td><?= $data['supplier'] ?>
				</td>
				<td><?=  date("Y年m月d日 H時i分s秒", strtotime($data['created_at'])); ?>
				</td>
				<td><?= $data['quantity'] ?>
				</td>
			</tr>
			<?php endforeach; ?>

		</tbody>
	</table>


	<?php // ページャー
		if($total > $limit):?>
	<nav aria-label="pager">
		<ul class="pagination">
			<li class="page-item <?= $is_prev ?>">
				<?php if($is_prev === 'disabled'): // 前へボタンが非活性なら ?>
				<span class="page-link">前へ</span>
				<?php else:?>
				<a class="page-link"
					href="<?= '?limit=' .$limit. '&offset=' .$prev_offset .getAllParams(false, '&');?>">前へ</a>
				<?php endif;?>
			</li>

			<?php for($i = 1; $i <= $pages; $i++):?>
			<?php if($is_current === $i): // 現在のページなら active ?>
			<li class="page-item active">
				<a class="page-link"
					href="<?= '?limit=' .$limit. '&offset=' .$limit * ($i -1). getAllParams(false,'&'); ?>"><?= $i; ?> <span class="sr-only">(current)</span></a>
			</li>
			<?php else:?>
			<li class="page-item">
				<a class="page-link"
					href="<?= '?limit=' .$limit. '&offset=' .$limit * ($i -1). getAllParams(false,'&'); ?>"><?= $i; ?></a>
			</li>
			<?php endif;?>
			<?endfor;?>

			<li class="page-item <?= $is_next; ?>">
				<?php if($is_next === 'disabled'):?>
				<span class="page-link">次へ</span>
				<?php else:?>
				<a class="page-link"
					href="<?= '?limit=' .$limit. '&offset=' .$next_offset .getAllParams(false, '&'); ?>">次へ</a>
				<?php endif;?>
			</li>
		</ul>
	</nav>
	<?php endif;?>

	<?php else: ?>
	<p>データが存在しません。</p>
	<?php endif;?>
</div>