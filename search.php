<?php
require_once dirname(__FILE__) . '/define.php';
session_start();
if (!isLogin()) {
    // ログインしていない場合はログインさせる
    header('Location: ./login.php');
}

// モデルを宣言
require_once dirname(__FILE__) .'/models/SearchModel.php';
$model = new SearchModel();

// Dto 宣言
require_once dirname(__FILE__) . '/table/Dto.php';
$dto = new Dto();


// データベース接続
require_once dirname(__FILE__) . '/table/OrdersDao.php';
$dao = new OrdersDao();
$ordersList = array();


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

// 日時指定しない にチェックが入っていない場合は日付時刻をセット
if ($noSelectDatetime === 'off') {
    if ($model->existsDatetimeParams()) {
        // 開始日時と終了日時が両方入力されている場合
        $model->setDatetimeParams($dto);
    } elseif ($model->existsRadiotimeParams()) {
        // 開始日とプルダウン時刻が設定されている場合
        $model->setRadiotimeParams($dto);
    }
}

$dto->limit = ['limit' => $limit, 'offset' => $offset];
$dto->orderBy = ['columnName' => 'orders.id', 'sort' => 'ASC'];

$dto->columnList = [
    'orders.id AS id',
    'products.name AS products',
    'suppliers.name AS supplier',
    'orders.created_at AS created_at',
    'orders.quantity AS quantity'
];

// 必要なデータをデータベースから取得
$ordersList = $dao->selectJoin($dto);

// 総件数を取得
$dto->columnList = ['orders.id'];
$total = $dao->selectJoinCount($dto);

// contents で使用するため取得しておく
$datetime_from =  new Datetime($dto->between['from']);
$datetime_to = new Datetime($dto->between['to']);

$dto = new Dto();
$dto->columnList = ['id','name'];

// プルダウンメニュー用のデータをマスタテーブルから取得
require_once dirname(__FILE__) . '/table/ProductsDao.php';
$dao = new ProductsDao();
$productsList = $dao->select($dto);

require_once dirname(__FILE__) . '/table/SuppliersDao.php';
$dao = new SuppliersDao();
$suppliersList = $dao->select($dto);

//画面を表示
require_once dirname(__FILE__) . '/parts/header.php';
require_once dirname(__FILE__) . '/parts/contents.php';
require_once dirname(__FILE__) . '/parts/footer.php';
