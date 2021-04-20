<?php
/**
 * 検索時に行われる処理をまとめたクラス
 */
class SearchModel
{
    
    /**
     * 渡された名前のパラメータが存在しているか確認
     *
     * @param String $param パラメータ名
     * @return boolean
     */
    public function exitstsParam(String $param) :bool
    {

        // パラメータ名が存在していて、かつ空白ではない場合はtrue
        if (isset($_GET[$param]) === true && $_GET[$param] !== '') {
            return true;
        }

        // そうでなければfalse
        return false;
    }


    
    /**
     * 日付がパラメータに存在しているかどうか
     *
     * @return boolean 存在していれば true を返却
     */
    public function existsDatetimeParams() :bool
    {
        if (isset($_GET['date-from']) && isset($_GET['hour-from']) && isset($_GET['min-from']) && isset($_GET['sec-from'])) {
            if (isset($_GET['date-to']) && isset($_GET['hour-to']) && isset($_GET['min-to']) && isset($_GET['sec-to'])) {
                return true;
            }
        }
        return false;
    }


    
    /**
     * ラジオの時間パラメータが存在しているかどうか
     *
     * @return boolean 存在していれば true を返却
     */
    public function existsRadiotimeParams() : bool
    {
        if (isset($_GET['date-from']) && isset($_GET['radio-time'])) {
            return true;
        }
        return false;
    }

    
    /**
     *
     * Dtoのメンバ変数に日時パラメータをセット
     *
     * @param Dto $Dto
     * @return void
     */
    public function setDatetimeParams(Dto $dto):void
    {
        // 手動選択
        $datetime_from = $this->ganerateDatetimeFrom();
        $datetime_to = $this->ganerateDatetimeTo();
    
        try {
            // 入力値をDatetime型にして代入
            $datetime_from = new datetime($datetime_from);
        } catch (Exception $e) {
            error_log($e->getMessage());
            serverError(500);
        }
        try {
            // 入力値をDatetime型にして代入
            $datetime_to = new datetime($datetime_to);
        } catch (Exception $e) {
            error_log($e->getMessage());
            serverError(500);
        }
    
        $dto->between = [
            'columnName' => 'orders.created_at',
            'from' => $datetime_from->format('Y-m-d H:i:s').'.000000',
            'to' => $datetime_to->format('Y-m-d H:i:s').'.999999'
        ];
    }


    /**
     * Dtoのメンバ変数にradiotimeパラメータをセット
     *
     * @param Dto $dto
     * @return void
     */
    public function setRadiotimeParams(Dto $dto) :void
    {
        // 選択された日付時刻から一時間以内のデータ
        $datetime_from = $_GET['date-from'] .' '. $_GET['radio-time'] .':00';

        try {
            // 入力値をDatetime型にして代入
            $datetime_to = new datetime($datetime_from);
            $datetime_to->modify('+1 hour');
            $datetime_from = new datetime($datetime_from);
        } catch (Exception $e) {
            error_log($e->getMessage());
            serverError(500);
        }
        
        $dto->between = [
            'columnName' => 'orders.created_at',
            'from' => $datetime_from->format('Y-m-d H:i:s').'.000000',
            'to' => $datetime_to->format('Y-m-d H:i:s').'.999999'
        ];
    }

    /**
     * GETパラメータをもとに開始日時を生成して返却する
     *
     * @return String
     */
    private function ganerateDatetimeFrom() :String
    {
        $datetime_from = $_GET['date-from'] .' '. $_GET['hour-from'] .':'. $_GET['min-from'] .':'. $_GET['sec-from'];
        return $datetime_from;
    }

    /**
     * GETパラメータをもとに終了日時を生成して返却する
     *
     * @return String
     */
    private function ganerateDatetimeTo() :String
    {
        $datetime_to = $_GET['date-to'] .' '. $_GET['hour-to'] .':'. $_GET['min-to'] .':'. $_GET['sec-to'];
        return $datetime_to;
    }
}
