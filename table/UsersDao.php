<?php
require_once  dirname(__FILE__).'/Dao.php';
/**
 * usersテーブルに接続するためのDAOクラス
 *
 * @access public
 * @author Rimane
 */
class UsersDao extends Dao
{
    // テーブル名
    protected $table = 'public.users';

    /**
     * コンストラクタ
     * インスタンスが new されたときに呼ばれる
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * デストラクタ
     * インスタンスが破棄されるときに呼ばれる
     *
     * @access public
     */
    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * 新規ユーザ登録処理
     * 登録可否を真偽値で返却
     * パスワードは関数内でハッシュ化する
     *
     * @access public
     * @param String $mail メールアドレス
     * @param String $password ハッシュ化する前のパスワード
     * @return boolean 登録に成功したらtrue / すでに登録済みならfalse
     */
    public function register(String $mail, String $pass)
    {

        // 受け取ったパスワードをハッシュ化
        $pass = password_hash($pass, PASSWORD_BCRYPT);


        //フォームに入力されたmailがすでに登録されていないかチェック
        $sql = "SELECT * FROM {$this->table} WHERE mail = :mail";
        $stmt = ($this->dbh->prepare($sql));
        $stmt->bindValue(':mail', $mail);
        $stmt->execute();
        $users = $stmt->fetch();
        if ($users['mail'] === $mail) {
            // すでに登録されていたら挿入せずfalse返却
            $bool = false;
        } else {
            try {
                // トランザクション開始
                $this->dbh->beginTransaction();
                // 登録されていなければ挿入
                $sql = "INSERT INTO {$this->table}(mail,password) VALUES (:mail, :pass)";
                $stmt = ($this->dbh->prepare($sql));
                $stmt->bindValue(':mail', $mail);
                $stmt->bindValue(':pass', $pass);
                $stmt->execute();
                $bool = true;
                // トランザクションコミット
                $this->dbh->commit();
            } catch (PDOException $e) {
                $this->dbh->rollBack();
                exit('トランザクションに失敗しました。');
            }
        }

        return $bool;
    }


    /**
     * ログイン処理
     * 処理の成功可否を真偽値で返却する
     *
     * @access public
     * @param String $mail フォームから送信されたメールアドレス
     * @param String $password フォームから送信されたパスワード
     * @return boolean $bool ログイン処理が成功したらtrue 失敗ならfalseを返却
     */
    public function login(String $mail, String $pass)
    {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE mail = :mail";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':mail', $mail);
            $stmt->execute();
            $user = $stmt->fetch();
            //指定したハッシュがパスワードにマッチしているかチェック
            if (password_verify($pass, $user['password'])) {
                //DBのユーザー情報をセッションに保存
                $_SESSION['id'] = $user['id'];
                $bool = true;
            } else {
                $bool = false;
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            serverError(500);
        }

        return $bool;
    }
}
