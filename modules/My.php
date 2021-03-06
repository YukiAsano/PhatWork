<?php

require_once('Abstract.php');

/**
 * 自分が持つデータへのアクセスモジュール
 *
 * @module Modules_My
 */
class Modules_My extends Modules_Abstract
{
    /**
     * 自分が持つデータへのアクセスクラス
     *
     * @class Modules_My
     * @extends Modules_Abstract
     * @constructor
     */

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

    /**
     * 自分の未完了タスク一覧を取得
     *
     * @public
     * @method getOpenTasks
     * @param {Integer} [$assignedByAccountId=null] タスクの依頼者のアカウントID
     * @return {Array} 未完了タスク一覧
     */
    public function getOpenTasks($assignedByAccountId = null)
    {
        $params = array(
            'status' => 'open'
        );
        if (!is_null($assignedByAccountId)) {
            $params['assigned_by_account_id'] = $assignedByAccountId;
        }
        return $this->getTasks($params);
    }

    /**
     * 自分の完了タスク一覧を取得
     *
     * @public
     * @method getDoneTasks
     * @param {Integer} [$assignedByAccountId=null] タスクの依頼者のアカウントID
     * @return {Array} 完了なタスク一覧
     */
    public function getDoneTasks($assignedByAccountId = null)
    {
        $params = array(
            'status' => 'done'
        );
        if (!is_null($assignedByAccountId)) {
            $params['assigned_by_account_id'] = $assignedByAccountId;
        }
        return $this->getTasks($params);
    }
}
