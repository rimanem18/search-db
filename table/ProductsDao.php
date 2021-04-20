<?php
require_once  dirname(__FILE__).'/Dao.php';
/**
 * productsテーブルに接続するためのDAOクラス
 *
 * @access public
 * @author Rimane
 */
class ProductsDao extends Dao
{
    // テーブル名
    protected $table = 'public.products';

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
