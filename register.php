<?php
require_once dirname(__FILE__) . '/define.php';
session_start();

// 直接アクセスされたら403エラーにしてはじく
if ($_SERVER ['REQUEST_METHOD'] !== 'POST') {
	header('Content-Type: text/plain; charset=UTF-8', true, 403);
	exit('403 Forbidden');
}
// 一つでも空ならはじく
if(empty($_POST['mail']) || empty($_POST['pass'])){
	echo 'メールアドレス、またはパスワードが入力されていません。';
	exit();
}

//フォームからの値をそれぞれ変数に代入
$mail = $_POST['mail'];
$pass = $_POST['pass'];

// Dao読み込み
require_once dirname(__FILE__) . '/table/UsersDao.php';
$dao = new UsersDao();

// POSTされたデータをもとにSQL発行
$bool = $dao->register($mail, $pass);


require_once dirname(__FILE__) . '/parts/header.php';
?>

<?php if($bool): ?>
<h2>ユーザ登録成功</h2>
<p>ユーザ登録に成功しました。</p>
<p>登録されたメールアドレス:<?=$mail?></p>
<?php else:?>
<h2>ユーザ登録失敗</h2>
<p>このメールアドレスはすでに登録されています。</p>
<?php endif;?>
<a href="signup.php">登録ページへ戻る</a>

<?php require_once dirname(__FILE__) . '/parts/footer.php' ?>