<?php

require_once('Abstract.php');

/**
 * 自分が持つデータへのアクセスモジュール
 *
 * @class Modules_My
 * @extends Modules_Abstract
 */
class Modules_My extends Modules_Abstract
{

    /**
     * チャットワークAPIエンドポイントパス
     *
     * @property ENDPOINT
     * @type String
     * @default '/my'
     */
    const ENDPOINT = '/my';

    /**
     * 未読未完了関連追加URL
     *
     * @property STATUS_URL
     * @type String
     * @default '/status'
     */
    const STATUS_URL = '/status';

    /**
     * タスク関連追加URL
     *
     * @property TASKS_URL
     * @type String
     * @default '/tasks'
     */
    const TASKS_URL = '/tasks';

    /**
     * API URL
     *
     * @property $_apiUrl
     * @type String
     */
    protected $_apiUrl;

    /**
     * サブクラス初期化メソッド
     *
     * @protected
     * @method _initSelf
     */
    protected function _initSelf()
    {
        $this->_apiUrl = self::BASE_URL.self::ENDPOINT;
    }

    /**
     * 自分の未読数、未読To数、未完了タスク数を返す
     *
     * @public
     * @method getStatus
     * @return {Array} 未読・未完了数リスト
     */
    public function getStatus()
    {
        return $this->_get($this->_apiUrl.self::STATUS_URL, null);
    }

    /**
     * 自分のタスク一覧を取得する。<br />
     * (※100件まで取得可能。今後、より多くのデータを取得する為のページネーションの仕組みを提供予定)
     *
     * @public
     * @method getTasks
     * @param {Array} $params パラメータ配列
     * <pre><code>array(
     *     'assigned_by_account_id' => '[タスクの依頼者のアカウントID]',
     *     'status' => '[タスクのステータス]',
     * )
     * </code></pre>
     * @return {Array} タスクリスト
     */
    public function getTasks($params)
    {
        return $this->_get($this->_apiUrl.self::TASKS_URL, $params);
    }
}
