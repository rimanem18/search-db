<?php
require_once dirname(__FILE__) . '/define.php';
session_start();
if (isLogin()) {
    // ログイン済みならトップページへ
    header('Location: ./');
}

if ($_SERVER ['REQUEST_METHOD'] === 'POST') {
    // 一つでも空ならはじく
    if (empty($_POST['mail']) || empty($_POST['pass'])) {
        echo 'メールアドレス、またはパスワードが入力されていません。';
        exit();
    }

    //フォームからの値をそれぞれ変数に代入
    $mail = $_POST['mail'];
    $pass = $_POST['pass'];

    // データベース接続
    require_once dirname(__FILE__) . '/table/UsersDao.php';
    $dao = new UsersDao();

    // POSTされたデータをもとにSQL発行
    $bool = $dao->login($mail, $pass);


    if ($bool) {
        // ログイン成功ならトップページへ飛ばす
        header('Location: ./');
    }
} else {
    // 直接アクセスならログインフォーム
    require_once dirname(__FILE__) . '/parts/header.php';
    require_once dirname(__FILE__) . '/parts/loginForm.php';
    require_once dirname(__FILE__) . '/parts/footer.php';
    exit();
}
?>

<?php require_once dirname(__FILE__) . '/parts/header.php'; ?>
<h2>ログイン失敗</h2>
<p>メールアドレス、またはパスワードが間違っています。</p>
<a href="login.php">ログイン</a>
<?php require_once dirname(__FILE__) . '/parts/footer.php';
