<?php

/**
 * チャットルームモジュール
 *
 * @module Modules_Rooms_Room
 */
class Modules_Rooms_Room extends Modules_Abstract
{
    /**
     * チャットルームクラス
     *
     * @class Modules_Rooms_Room
     * @extends Modules_Abstract
     * @constructor
     */

    /**
     * チャットワークAPIエンドポイントパス
     *
     * @property ENDPOINT
     * @type String
     * @default '/rooms'
     */
    const ENDPOINT = '/rooms';

    /**
     * メンバー関連追加URL
     *
     * @property MEMBERS_URL
     * @type String
     * @default '/members'
     */
    const MEMBERS_URL = '/members';

    /**
     * メッセージ関連追加URL
     *
     * @property MESSAGES_URL
     * @type String
     * @default '/messages'
     */
    const MESSAGES_URL = '/messages';

    /**
     * タスク関連追加URL
     *
     * @property TASKS_URL
     * @type String
     * @default '/tasks'
     */
    const TASKS_URL = '/tasks';

    /**
     * ファイル関連追加URL
     *
     * @property FILES_URL
     * @type String
     * @default '/files'
     */
    const FILES_URL = '/files';

    /**
     * チャットルームID
     *
     * @property $_roomId
     * @type Integer
     */
    protected $_roomId;

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
     * チャットルームID設定メソッド
     *
     * @public
     * @chainable
     * @method setId
     * @param {Integer} $roomId チャットルームID
     * @return {Modules_Rooms_Room} this
     */
    public function setId($roomId)
    {
        if (!$this->_roomId) {
            $this->_roomId = $roomId;
            $this->_apiUrl .= '/' . $roomId;
        }
        return $this;
    }

    /**
     * チャットの名前、アイコン、種類(my/direct/group)を取得
     *
     * @public
     * @method get
     * @return {Array} チャットルーム情報、またはfalse
     */
    public function get()
    {
        return $this->_get($this->_apiUrl, null);
    }

    /**
     * チャットの名前、アイコンをアップデート
     *
     * @public
     * @chainable
     * @method set
     * @param {Array} $params パラメータ配列
     * <pre><code>array(
     *     'description' => '[チャット概要]',
     *     'icon_preset' => '[アイコン種類]',
     *     'name' => '[グループチャット名]',
     * )
     * </code></pre>
     * @return {Integer} チャットルームID、またはfalse
     */
    public function set($params)
    {
        $ret = $this->_put($this->_apiUrl, $params);
        return isset($ret['room_id']) ? $ret['room_id'] : false;
    }

    /**
     * チャット概要をアップデート
     *
     * @public
     * @chainable
     * @method setDescription
     * @param {String} $description チャット概要
     * @return {Modules_Rooms_Room} this
     */
    public function setDescription($description)
    {
        $ret = $this->set(array(
            'description' => $description
        ));
        return isset($ret['room_id']) ? $this : false;
    }

    /**
     * アイコンをアップデート
     *
     * @public
     * @chainable
     * @method setIcon
     * @param {String} $icon アイコン種類
     * @return {Modules_Rooms_Room} this
     */
    public function setIcon($icon)
    {
        $ret = $this->set(array(
            'icon_preset' => $icon
        ));
        return isset($ret['room_id']) ? $this : false;
    }

    /**
     * チャットの名前をアップデート
     *
     * @public
     * @chainable
     * @method setName
     * @param {String} $name グループチャット名
     * @return {Modules_Rooms_Room} this
     */
    public function setName($name)
    {
        $ret = $this->set(array(
            'name' => $name
        ));
        return isset($ret['room_id']) ? $this : false;
    }

    /**
     * グループチャットを退席/削除する
     *
     * @public
     * @method remove
     * @param {Array} $params パラメータ配列
     * <pre><code>array(
     *     'action_type' => '[退席するか、削除するか]',
     * )
     * </code></pre>
     * @return {Boolean} true
     */
    public function remove($params)
    {
        $this->_delete($this->_apiUrl, $params);

        // 戻り値なし
        return true;
    }

    /**
     * グループチャットを退席する
     *
     * @public
     * @chainable
     * @method leave
     * @return {Modules_Rooms_Room} this
     */
    public function leave()
    {
        $ret = $this->remove(array(
            'action_type' => 'leave'
        ));
        return $this;
    }

    /**
     * グループチャットを削除する
     *
     * @public
     * @chainable
     * @method delete
     * @return {Modules_Rooms_Room} this
     */
    public function delete()
    {
        $ret = $this->remove(array(
            'action_type' => 'delete'
        ));
        return $this;
    }

    /**
     * チャットのメンバー一覧を取得
     *
     * @public
     * @method getMembers
     * @return {Array} メンバー一覧
     */
    public function getMembers()
    {
        $url = $this->_apiUrl . self::MEMBERS_URL;
        return $this->_get($url, null);
    }

    /**
     * チャットのメンバーを一括変更
     *
     * @public
     * @chainable
     * @method setMembers
     * @param {Array} $params パラメータ配列
     * <pre><code>array(
     *     'members_admin_ids' => '[管理者権限のユーザー（必須、カンマ区切り複数指定）]',
     *     'members_member_ids' => '[メンバー権限のユーザー（カンマ区切り複数指定）]',
     *     'members_readonly_ids' => '[閲覧のみ権限のユーザー（カンマ区切り複数指定）]',
     * )
     * </code></pre>
     * @param {Boolean} [$bReturnInstance=true] インスタンス返却フラグ
     * @return {mixed} 現在のメンバーリスト、チャットルームインスタンス、またはfalse
     */
    public function setMembers($params, $bReturnInstance = true)
    {
        $url = $this->_apiUrl . self::MEMBERS_URL;
        $ret = $this->_put($url, $params);

        $return = null;
        if (isset($ret['members_admin_ids'])) {
            if ($bReturnInstance !== true) {
                $return = $ret;
            }
        } else {
            $return = false;
        }
        return is_null($return) ? $this : $return;
    }

    /**
     * チャットに新しいメッセージを追加
     *
     * @public
     * @chainable
     * @method setMessages
     * @param {String} $message メッセージ本体
     * @param {Boolean} [$bReturnInstance=true] インスタンス返却フラグ
     * @return {mixed} メッセージID、チャットルームインスタンス、またはfalse
     */
    public function setMessages($message, $bReturnInstance = true)
    {
        $url = $this->_apiUrl . self::MESSAGES_URL;
        $ret = $this->_post($url, array('body' => $message));

        $return = null;
        if (isset($ret['message_id'])) {
            if ($bReturnInstance !== true) {
                $return = $ret['message_id'];
            }
        } else {
            $return = false;
        }
        return is_null($return) ? $this : $return;
    }

    /**
     * チャットのメッセージ一覧を取得
     *
     * @public
     * @method getMessages
     * @param {Boolean} [$bForce=false] 最新100件を取得する場合はtrue
     * @return {Array} メッセージ一覧
     */
    public function getMessages($bForce = false)
    {
        $url = $this->_apiUrl . self::MESSAGES_URL.'?force='.((int)$bForce);
        return $this->_get($url, null);
    }

    /**
     * メッセージ情報を取得
     *
     * @public
     * @method getMessage
     * @param {Integer} $messageId メッセージID
     * @return {Array} メッセージ情報
     */
    public function getMessage($messageId)
    {
        $url = $this->_apiUrl . self::MESSAGES_URL . '/' . $messageId;
        return $this->_get($url, null);
    }

    /**
     * チャットのタスク一覧を取得<br />
     *  (※100件まで取得可能。今後、より多くのデータを取得する為のページネーションの仕組みを提供予定)
     *
     * @public
     * @method getTasks
     * @param {Array} [$params=null] パラメータ配列
     * <pre><code>array(
     *     'account_id' => '[タスクの担当者のアカウントID]',
     *     'assigned_by_account_id' => '[タスクの依頼者のアカウントID]',
     *     'status' => '[タスクのステータス]',
     * )
     * </code></pre>
     * @return {Array} タスク一覧
     */
    public function getTasks($params = null)
    {
        $url = $this->_apiUrl . self::TASKS_URL;
        return $this->_get($url, $params);
    }

    /**
     * チャットの未完了タスク一覧を取得
     *
     * @public
     * @method getOpenTasks
     * @param {Integer} [$accountId=null] アカウントID
     * @return {Array} 未完了タスク一覧
     */
    public function getOpenTasks($accountId = null)
    {
        $params = array(
            'status' => 'open'
        );
        if (!is_null($accountId)) {
            $params['account_id'] = $accountId;
        }
        return $this->getTasks($params);
    }

    /**
     * チャットの完了タスク一覧を取得
     *
     * @public
     * @method getDoneTasks
     * @param {Integer} [$accountId=null] アカウントID
     * @return {Array} 完了なタスク一覧
     */
    public function getDoneTasks($accountId = null)
    {
        $params = array(
            'status' => 'done'
        );
        if (!is_null($accountId)) {
            $params['account_id'] = $accountId;
        }
        return $this->getTasks($params);
    }

    /**
     * チャットに新しいメッセージを追加
     *
     * @public
     * @chainable
     * @method setMessages
     * @param {Array} $params パラメータ配列
     * <pre><code>array(
     *     'body' => '[タスクの内容（必須）]',
     *     'limit' => '[タスクの期限 Unix time]',
     *     'to_ids' => '[担当者のアカウントID（必須、カンマ区切り複数指定）]',
     * )
     * </code></pre>
     * @param {Boolean} [$bReturnInstance=true] インスタンス返却フラグ
     * @return {mixed} タスクID、チャットルームインスタンス、またはfalse
     */
    public function setTasks($params, $bReturnInstance = true)
    {
        $url = $this->_apiUrl . self::TASKS_URL;
        $ret = $this->_post($url, $params);

        $return = null;
        if (isset($ret['task_ids'])) {
            if ($bReturnInstance !== true) {
                $return = $ret['task_ids'];
            }
        } else {
            $return = false;
        }
        return is_null($return) ? $this : $return;
    }

    /**
     * タスク情報を取得
     *
     * @public
     * @method getTask
     * @param {Integer} $taskId タスクID
     * @return {Array} タスク情報
     */
    public function getTask($taskId)
    {
        $url = $this->_apiUrl . self::TASKS_URL . '/' . $taskId;
        return $this->_get($url, null);
    }

    /**
     * チャットのファイル一覧を取得<br />
     *  (※100件まで取得可能。今後、より多くのデータを取得する為のページネーションの仕組みを提供予定)
     *
     * @public
     * @method getFiles
     * @param {Array} [$params=null] パラメータ配列
     * <pre><code>array(
     *     'account_id' => '[アップロードしたユーザーのアカウントID]',
     * )
     * </code></pre>
     * @return {Array} ファイル一覧
     */
    public function getFiles($params = null)
    {
        $url = $this->_apiUrl . self::FILES_URL;
        return $this->_get($url, $params);
    }

    /**
     * ユーザのファイル一覧を取得
     *
     * @public
     * @method getUserFiles
     * @param {Integer} $accountId アカウントID
     * @return {Array} ユーザのファイル一覧
     */
    public function getUserFiles($accountId)
    {
        return $this->getFiles(array(
            'account_id' => $accountId
        ));
    }

    /**
     * ファイル情報を取得
     *
     * @public
     * @method getFile
     * @param {Integer} $fileId ファイルID
     * @param {Boolean} [$bDLLink=false] ダウンロードする為のURLを生成するか<br />
     * 30秒間だけダウンロード可能なURLを生成します<br />
     * （配列のキーにdownload_urlが追加される）
     * @return {Array} ファイル情報
     */
    public function getFile($fileId, $bDLLink = false)
    {
        $params = array(
            'create_download_url' => $bDLLink
        );
        $url = $this->_apiUrl . self::FILES_URL . '/' . $fileId;
        return $this->_get($url, $params);
    }

    /**
     * ファイルのダウンロードリンクを取得
     *
     * @public
     * @method getUserFiles
     * @param {Integer} $accountId アカウントID
     * @return {String} ダウンロードリンク、なければnull
     */
    public function getDownLoadLink($fileId)
    {
        $ret = $this->getFile($fileId, true);
        return isset($ret['download_url']) ? $ret['download_url'] : null;
    }
}
