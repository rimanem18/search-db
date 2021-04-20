<?php
require_once  dirname(__FILE__).'/Dao.php';
/**
 * ordersテーブルに接続するためのDAOクラス
 *
 * @access public
 * @author Rimane
 */
class OrdersDao extends Dao
{
    // テーブル名
    protected $table = 'public.orders';

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
     * 商品マスタと仕入れ先マスタを結合した結果レコードを
     * Dto にセットされている情報をもとに取得する
     *
     * @access public
     * @param Dto $dto
     * @return array $result レコードを配列で返却
     */
    public function selectJoin(Dto $dto) :array
    {
        // SQL用文字列を生成
        $column = $this->generateColumn($dto);
        $where = $this->generateWhere($dto);
        $between = $this->generateBetween($dto, $where);
        $limit = $this->generateLimit($dto);
        $orderBy = $this->generateOrderBy($dto);


        try {
            // データ取得
            $sql = "SELECT
             {$column}
			 FROM {$this->table}
			 JOIN products
			 ON {$this->table}.products_id = products.id
			 RIGHT JOIN suppliers
			 ON {$this->table}.suppliers_id = suppliers.id
			 {$where}
			 {$between}
			 {$orderBy}
             {$limit}";

            $stmt = ($this->dbh->prepare($sql));

            $this->bindWhere($stmt, $dto, $where);
            $this->bindBetween($stmt, $dto, $between);
            $this->bindLimit($stmt, $dto, $limit);

            $stmt->execute();
            $result = $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            serverError(500);
        }
        
        return $result;
    }


    /**
     * Dto にセットされている配列をもとに
     * 検索結果のレコード総件数を返却する
     *
     * @access public
     * @param Dto $dto
     * @return int $total 条件に合う結果のレコード数を返却
     */
    public function selectJoinCount(Dto $dto) :int
    {
        // SQL用文字列を生成
        $column = $this->generateColumn($dto);
        $where = $this->generateWhere($dto);
        $between = $this->generateBetween($dto, $where);

        try {
            // データ取得
            $sql = "SELECT
             count(({$column}))
			 FROM {$this->table}
			 JOIN products
			 ON {$this->table}.products_id = products.id
			 RIGHT JOIN suppliers
			 ON {$this->table}.suppliers_id = suppliers.id
			 {$where}
			 {$between}";


            $stmt = ($this->dbh->prepare($sql));

            $this->bindWhere($stmt, $dto, $where);
            $this->bindBetween($stmt, $dto, $between);

            $stmt->execute();
            $total = $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            serverError(500);
        }
        
        return $total;
    }
}
