<?php
require_once dirname(__FILE__) . '/../define.php';
session_start();
if (!isLogin()) {
    // ログインしていない場合はログインさせる
    header('Location: ../login.php');
}

// モデルを宣言
require_once dirname(__FILE__) .'/../models/SearchModel.php';
$model = new SearchModel();

// Dto 宣言
require_once dirname(__FILE__) . '/../table/Dto.php';
$dto = new Dto();

$ordersList = array();
// データベース接続
require_once dirname(__FILE__) . '/../table/OrdersDao.php';
$dao = new OrdersDao();


// パラメータがある場合、パラメータに則ったデータを取得
if ($model->exitstsParam('products')) {
    $products_id = $_GET['products'];
    $where['products_id'] = $products_id;
}
if ($model->exitstsParam('supplier')) {
    $suppliers_id = $_GET['supplier'];
    $where['suppliers_id'] = $suppliers_id;
}
if (!empty($where)) {
    $dto->where = $where;
}

// 日時指定しない にチェックが入っているかどうか
$noSelectDatetime = 'off';
if (isset($_GET['no-select-datetime'])) {
    $noSelectDatetime = $_GET['no-select-datetime'];
}


// パラメータがある場合、パラメータに則ったデータを取得
if ($noSelectDatetime === 'off') {
    if ($model->existsDatetimeParams()) {
        $model->setDatetimeParams($dto);
    } elseif ($model->existsRadiotimeParams()) {
        $model->setRadiotimeParams($dto);
    }
}

$dto->orderBy = ['columnName' => 'orders.id' , 'sort' => 'ASC'];

$dto->columnList = [
    'orders.id AS id',
    'products.name AS products',
    'suppliers.name AS supplier',
    'orders.created_at AS created_at',
    'orders.quantity AS quantity'
];

// 必要なデータをデータベースから取得
$ordersList = $dao->selectJoin($dto);

// ファイル作成
$fileName = 'result.csv';
$fp = fopen($fileName, 'w');

// UTF-8 -> SJIS に文字コード変換
stream_filter_prepend($fp, 'convert.iconv.utf-8/cp932');

foreach ($ordersList as $line) {

    // 普通の配列にして書き込み
    fputcsv($fp, array_values($line));
}

fclose($fp);

// HTTPヘッダを設定
header('Content-Type: application/octet-stream');
header('Content-Length: '.filesize($fileName));
header('Content-Disposition: attachment; filename='.$fileName);



// ファイル出力後、削除
readfile($fileName);
unlink($fileName);
exit();
