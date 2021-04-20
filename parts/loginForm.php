    <form class="form-signin" action="login.php" method="post">
        <h2 class="mb-3">ログイン</h2>
        <div class="form-group">
            <label for="mail" class="sr-only">メールアドレス：</label>
            <input class="form-control" placeholder="Email address" type="email" id="mail" name="mail" required autofocus>
            <label for="pass" class="sr-only">パスワード：</label>
            <input class="mb-0 form-control" placeholder="Password" type="password" id="pass" name="pass" required>
            <a class="mt-2" href="javascript:()" id="js-show-password">パスワード表示</a>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">ログイン</button>
    </form>