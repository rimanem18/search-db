<?php

/**
 * データベースへデータの受け渡しをするためのクラス
 */
class Dto
{
    /**
     * データ取得に使用するカラム
     *
     * @var String[] カラム名,カラム名,カラム名,....
     */
    private $columnList;

    /**
     * 条件を指定する
     *
     * @var String[] カラム名 => 条件
     */
    private $where;

    /**
     * 取得開始日と取得終了日をどうするか
     *
     * @var String[] columnName => カラム名 from => 開始日 to => 終了日
     */
    private $between;

    /**
     * limit と offset を指定する
     *
     * @var int[] limit => 限度値 offset => 取得開始値
     */
    private $limit;

    /**
     * データベースから取得したデータの並び順をどうするか
     *
     * @var String[] columnName => カラム名 sort => ASC or DESC
     */
    private $orderBy;

    /**
     * Setter
     *
     * @param String $name
     * @param any $value
     */
    public function __set(String $name, $value)
    {
        $this->$name = $value;
    }

    /**
     * Getter
     *
     * @param String $name
     * @return void
     */
    public function __get(String $name)
    {
        return $this->$name;
    }
}
