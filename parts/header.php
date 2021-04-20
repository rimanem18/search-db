<!DOCTYPE html>
<html lang="ja">

<head>
	<meta name="robots" content="noindex,nofollow" />
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="css/style.css">
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
		integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
	</script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
		integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
	</script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
		integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
	</script>
	<!-- bootstrap datepicker -->
	<link rel="stylesheet" type="text/css" href="libs/bootstrap-datepicker-1.9.0-dist/css/bootstrap-datepicker.min.css">
	<script type="text/javascript" src="libs/bootstrap-datepicker-1.9.0-dist/js/bootstrap-datepicker.min.js"></script>
	<script type="text/javascript" src="libs/bootstrap-datepicker-1.9.0-dist/locales/bootstrap-datepicker.ja.min.js">
	</script>
	<title><?= APP_NAME; ?>
	</title>
</head>

<body>
	<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom box-shadow">
		<h5 class="my-0 mr-md-auto font-weight-normal">
			<a class="text-dark" href="./"><?=APP_NAME?></a>
		</h5>
		<?php // ログインしている場合
        if (isLogin()):?>
		<nav class="my-2 my-md-0 mr-md-3">
			<a class="p-2 text-dark" href="./">トップ</a>
			<a class="p-2 text-dark" href="signup.php">新しいユーザを追加</a>
		</nav>
		<a class="btn btn-outline-primary" href="logout.php">ログアウト</a>
		<?php // していない場合
      else: ?>
		<nav class="my-2 my-md-0 mr-md-3">
			<a class="p-2 text-dark" href="./">トップ</a>
		</nav>
		<a class="btn btn-outline-primary" href="login.php">ログイン</a>
		<?php endif; ?>
	</div>
	<div class="container">