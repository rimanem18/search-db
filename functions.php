<?php

/**
 * ログイン状態を確認する
 *
 * @return boolean ログインしていれば true を返却する
 */
function isLogin() :bool
{
    if (isset($_SESSION['id'])) {
        // セッションを確認してログインしていればtrue
        return true;
    }

    // ログインしていない場合はfalse
    return false;
}


/**
 * ステータスコードに応じたエラーを表示して処理を終了する
 *
 * @param int ステータスコード
 */
function serverError(int $code) :void
{
    switch ($code) {
        case 500:
            header('Content-Type: text/plain; charset=UTF-8', true, 500);
            exit('500 Internal Server Error');
            break;
    
        case 403:
            header('Content-Type: text/plain; charset=UTF-8', true, 403);
            exit('403 Forbidden:このページへのアクセスは禁止されています。');
            break;
        
        default:
            header('Content-Type: text/plain; charset=UTF-8', true, 500);
            exit('500 Internal Server Error:想定外のエラーです。');
            break;
    }
}


/**
 * すべてのGETパラメータを取得して返却する
 *
 * @param boolean $bool
 * 	limit と offset をふくむか
 * 	デフォルトはtrue（ふくむ）
 * @param String $first 最初の文字を何にするか デフォルトは「?」
 * @return String GETパラメータ
 */
function getAllParams($bool = true, $first = '?') :String
{
    $params = '';
    foreach ($_GET as $key => $value) {
        // もし false が渡されていたら limit と offset は取得しない
        if (!$bool && ($key == 'limit' || $key == 'offset')) {
            continue;
        }
        // パラメータが空欄のときはスルー
        if ($_GET[$key] !== '') {
            $params .= $key . '=' . $value . '&';
        }
    }
    $params = substr($params, 0, -1);

    // パラメータが空ではなければ
    if ($params != '') {
        $params = $first . $params;
    }

    return $params;
}

