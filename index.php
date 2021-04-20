<?php
require_once dirname(__FILE__) .'/define.php';

session_start();
if (!isLogin()) {
    header('Location: ./login.php');
    exit();
}

// 様々な設定ファイルを読み込み
require_once dirname(__FILE__) . '/table/OrdersDao.php';
require_once dirname(__FILE__) . '/table/Dto.php';
require_once dirname(__FILE__) . '/table/ProductsDao.php';
require_once dirname(__FILE__) . '/table/SuppliersDao.php';

$dto = new Dto();
$dao = new OrdersDao();

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
$datetime_from = $dto->between['from'];
$datetime_to = $dto->between['to'];


$dto = new Dto();
$dto->columnList = ['id','name'];

// プルダウンメニュー用のデータをマスタテーブルから取得
require_once dirname(__FILE__) . '/table/ProductsDao.php';
$dao = new ProductsDao();
$productsList = $dao->select($dto);

require_once dirname(__FILE__) . '/table/SuppliersDao.php';
$dao = new SuppliersDao();
$suppliersList = $dao->select($dto);

// 画面を表示
require_once dirname(__FILE__) . '/parts/header.php';
require_once dirname(__FILE__) . '/parts/contents.php';
require_once dirname(__FILE__) . '/parts/footer.php';
