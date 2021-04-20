<?php
// ディスプレイ上へのエラーを出力を許可する
// リリース時はコメントアウトする
ini_set('display_errors', 'On');
ini_set('error_reporting', E_ALL);

// エラーログの出力
ini_set('error_log', 'php_error.log');


// 定数定義
const APP_NAME = 'SQL検索機能';
const APP_DIR = '/';

// 関数読み込み
require_once dirname(__FILE__). '/functions.php';

// データベース接続情報を読み込み
require_once dirname(__FILE__). '/dbconfig.php';

// GETデータが送信されている場合のみ代入
$limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
$offset = isset($_GET['offset']) ? $_GET['offset'] : 0;
