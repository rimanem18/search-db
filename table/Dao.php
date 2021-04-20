<?php
/**
 * すべてのDAOクラスに継承する親クラス
 * データベース接続 / 切断処理やどのDAOでも通用するSQL文を記述する
 *
 * @author Rimane
 */
class Dao
{
    protected $dbh = null;

    /**
     * コンストラクタ
     * インスタンスが new されたときに呼ばれる
     *
     * @access public
     */
    public function __construct()
    {
        // データベース情報を取得してPDOをインスタンス化
        try {
            $this->dbh = new PDO(
                DB['dsn'],
                DB['user'],
                DB['pass'],
                array(
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                )
            );
        } catch (PDOException $e) {
            // エラーが発生した場合は「500 Internal Server Error」でテキストとして表示して終了する
            error_log($e->getMessage());
            header('Content-Type: text/plain; charset=UTF-8', true, 500);
            exit('データベースへの接続が失敗しました。');
        }
    }

    /**
     * デストラクタ
     * インスタンスが破棄されるときに呼ばれる
     *
     * @access public
     */
    public function __destruct()
    {
    }


    /**
     * すべてのレコードを取得
     *
     * @access public
     * @return array $result すべてのレコードを配列で返却
     */
    public function selectAll() :array
    {
        // データ取得

        try {
            $sql = "SELECT * FROM {$this->table}";
            $stmt = ($this->dbh->prepare($sql));
            $stmt->execute();
            $result = $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            serverError(500);
        }

        return $result;
    }


    /**
     * テーブルに保管されているレコードの総件数を取得
     *
     * @access public
     * @return int $total レコードの総件数を返却
     */
    public function selectCount() :int
    {
        try {
            $sql = "SELECT count((id)) FROM {$this->table}";
            $stmt = ($this->dbh->prepare($sql));
            $stmt->execute();
            $total = $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            serverError(500);
        }

        return $total;
    }


    /**
     * 配列で渡されたデータを挿入
     *
     * @access public
     * @param array $insertData
     * 		挿入したいデータを連想配列で渡す
     * 		キーがカラム名として扱われます
     */
    public function insert(array $args) :void
    {
        // キーを取得して文字列化
        $column = '(';
        foreach (array_keys($args) as $key) {
            $column .= $key . ',';
        }
        $column = substr($column, 0, -1);
        $column .= ')';

        // 値を取得して文字列化
        $values = '(';
        for ($i = 0; $i < count($args); $i++) {
            $values .= '?,';
        }
        $values = substr($values, 0, -1);
        $values .= ')';

        try {
            // 文字列化したデータをSQL文に含める
            $sql = "INSERT INTO {$this->table} {$column} VALUES {$values}";
            $stmt = ($this->dbh->prepare($sql));
            // 普通の配列にしてexecute
            $stmt->execute(array_values($args));
        } catch (PDOException $e) {
            error_log($e->getMessage());
            serverError(500);
        }
    }


    /**
     * Dto にセットされている情報をもとに
     * レコードを取得する
     *
     * @access public
     * @param Dto $dto
     * @return array $result レコードを配列で返却
     */
    public function select(Dto $dto) :array
    {
        // SQL用文字列を生成
        $column = $this->generateColumn($dto);
        $where = $this->generateWhere($dto);
        $between = $this->generateBetween($dto, $where);
        $limit = $this->generateLimit($dto);
        $orderBy = $this->generateOrderBy($dto);
         

        // データ取得
        try {
            $sql = "SELECT {$column}
			 FROM {$this->table}
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
     * 渡された Dto をもとに 連結されたカラム名を生成する
     *
     * @param Dto $dto
     * @return String 連結されたカラム名
     */
    protected function generateColumn(Dto $dto) :String
    {
        $column = '';
        if ($dto->columnList !== null) {
            foreach ($dto->columnList as $value) {
                $column .= $value . ',';
            }
            // $column 最後の , は無駄になるので消す
            $column = substr($column, 0, -1);
        } else {
            $column = ' * ';
        }

        return $column;
    }
    
    /**
     * 渡された Dto をもとに WHERE 条件を生成する
     *
     * @param Dto $dto
     * @return String WHERE 条件文字列
     */
    protected function generateWhere(Dto $dto) :String
    {
        if ($dto->where !== null) {
            $where = 'WHERE ';

            // ループで連結していく
            foreach ($dto->where as $key => $value) {
                $where .= $key . ' = ' . ':' .$key . ' AND ';
            }
            // $where最後の AND は無駄になるので消す
            $where = substr($where, 0, -4);
        } else {
            $where = '';
        }

        return $where;
    }

    /**
     * 渡された Dto をもとに BETWEEN 条件を生成する
     *
     * @param Dto $dto
     * @return String BETWEEN 条件文字列
     */
    protected function generateBetween(Dto $dto, String $where) :String
    {
        if ($dto->between !== null) {
            if ($where !== '') {
                // $where が存在していたらANDを先頭に追加
                $between = 'AND ';
            } else {
                // $where が存在してなかったらWHEREを先頭に追加
                $between = 'WHERE ';
            }
            $between = " {$between} {$dto->between['columnName']}
                BETWEEN :from
                AND :to";
        } else {
            $between = '';
        }

        return $between;
    }


    /**
     * 渡された Dto をもとに LIMIT 条件を生成する
     *
     * @param Dto $dto
     * @return String LIMIT 条件文字列
     */
    protected function generateLimit(Dto $dto) :String
    {
        if ($dto->limit !== null) {
            $limit = "LIMIT :limit OFFSET :offset";
        } else {
            $limit = '';
        }

        return $limit;
    }
    
    /**
     * 渡された Dto をもとに ORDER BY 条件を生成する
     *
     * @param Dto $dto
     * @return String ORDER BY 条件文字列
     */
    protected function generateOrderBy(Dto $dto) :String
    {
        if ($dto->orderBy !== null) {
            $orderBy = "ORDER BY {$dto->orderBy['columnName']} {$dto->orderBy['sort']}";
        } else {
            $orderBy = '';
        }

        return $orderBy;
    }

    /**
     * WHERE 条件を bind
     *
     * @param PDOStatement $stmt
     * @param Dto $dto
     * @param String $where
     * @return void
     */
    protected function bindWhere(PDOStatement $stmt, Dto $dto, String $where) :void
    {
        if ($where !== '') {
            foreach ($dto->where as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
        }
    }

    /**
     * BETWEEN 条件を bind
     *
     * @param PDOStatement $stmt
     * @param Dto $dto
     * @param String $between
     * @return void
     */
    protected function bindBetween(PDOStatement $stmt, Dto $dto, String $between) :void
    {
        if ($between !== '') {
            $stmt->bindValue(':from', $dto->between['from']);
            $stmt->bindValue(':to', $dto->between['to']);
        }
    }
    
    /**
     * LIMIT 条件を bind
     *
     * @param PDOStatement $stmt
     * @param Dto $dto
     * @param String $limit
     * @return void
     */
    protected function bindLimit(PDOStatement $stmt, Dto $dto, String $limit) :void
    {
        if ($limit !== '') {
            $stmt->bindValue(':limit', $dto->limit['limit']);
            $stmt->bindValue(':offset', $dto->limit['offset']);
        }
    }
}
