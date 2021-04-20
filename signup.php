<?php
require_once dirname(__FILE__) . '/define.php';
session_start();
if( !isLogin() ){
    // ログインしていない場合はログインさせる
    header('Location: ./login.php');
}
require_once dirname(__FILE__) . '/parts/header.php';
?>

<form class="form-signin" action="register.php" method="post">
    <h2 class="mb-3">ユーザを追加</h2>
    <div class="form-group">
        <label for="mail" class="sr-only">メールアドレス：</label>
        <input id="mail" class="form-control" placeholder="Email address" type="email" name="mail" required>
        <label for="pass" class="sr-only">パスワード：</label>
        <input id="pass" class="form-control mb-0" placeholder="Password" type="password" name="pass" required>
        <a class="mt-2" href="javascript:()" id="js-show-password">パスワード表示</a>
        <p class="mt-2">パスワードは8文字以上、かつ半角数字と大文字と小文字のアルファベット、記号を含めたものにしてください。</p>
        <p id="js-pass-strength" class="alert alert-info">パスワードを入力してください。</p>
    </div>
    <button id="signup-btn" class="btn btn-lg btn-primary btn-block" type="submit" disabled>新規登録</button>
</form>

<?php require_once dirname(__FILE__) . '/parts/footer.php'; ?>