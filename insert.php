<?php
require_once dirname(__FILE__) . '/define.php';

// 直接アクセスされたら403エラーにしてはじく
if ($_SERVER ['REQUEST_METHOD'] !== 'POST') {
    serverError(403);
}
// 一つでも空ならはじく
if (empty($_POST['products']) || empty($_POST['supplier']) || empty($_POST['quantity'])) {
    header('Location: ./');
    exit();
}

$products = $_POST['products'];
$supplier = $_POST['supplier'];
$quantity = $_POST['quantity'];

// Dao読み込み
require_once dirname(__FILE__) . '/table/OrdersDao.php';
$dao = new OrdersDao();

// POSTされたデータを配列化してSQL発行
$insertData = array(
    'products_id' => $products,
    'suppliers_id' => $supplier,
    'quantity' => $quantity
);
$dao->insert($insertData);


// トップページに戻る
header('Location: ./');
