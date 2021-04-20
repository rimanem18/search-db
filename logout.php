<?php
require_once dirname(__FILE__) . '/define.php';
session_start();
$_SESSION = array();//セッションの中身をすべて削除
session_destroy();//セッションを破壊

header('Location: login.php');
