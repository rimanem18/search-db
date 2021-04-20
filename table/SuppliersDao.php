<?php
require_once  dirname(__FILE__).'/Dao.php';
/**
 * suppliersテーブルに接続するためのDAOクラス
 *
 * @access public
 * @author Rimane
 */
class SuppliersDao extends Dao
{
    // テーブル名
    protected $table = 'public.suppliers';

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
}
