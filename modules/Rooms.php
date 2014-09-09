<?php

require_once('Abstract.php');

/**
 * チャットルームモジュール
 *
 * @class Modules_Rooms
 * @extends Modules_Abstract
 */
class Modules_Rooms extends Modules_Abstract
{

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
     * API URL
     *
     * @property $_apiUrl
     * @type String
     */
    protected $_apiUrl;

    /**
     * チャットルームID
     *
     * @property $_roomId
     * @type String
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
     * @param {String} $roomId チャットルームID
     * @return {Modules_Rooms} this
     */
    public function setId($roomId)
    {
        $this->_roomId = $roomId;
        return $this;
    }

    /**
     * 自分のチャット一覧の取得
     *
     * @public
     * @method getList
     * @return {Array} チャットルームリスト
     */
    public function getList()
    {
        return $this->_get($this->_apiUrl, null);
    }

    /**
     * チャットの名前、アイコン、種類(my/direct/group)を取得
     *
     * @public
     * @method get
     * @param {String} $roomId チャットルームID
     * @return {Array} チャットルーム情報、またはfalse
     */
    public function get($roomId = null)
    {
        if (is_null($roomId)) {
            $roomId = $this->_roomId;
            if (is_null($roomId)) {
                return false;
            }
        }

        $url = $this->_apiUrl . '/' . $roomId;
        return $this->_get($url, null);
    }

    /**
     * グループチャットを新規作成
     *
     * @public
     * @method create
     * @param {Array} $params
     * <pre><code>array(
     *     'description' => '[チャット概要]',
     *     'icon_preset' => '[アイコン種類]',
     *     'members_admin_ids' => '[管理者権限のユーザー（必須、カンマ区切り複数指定）]',
     *     'members_member_ids' => '[メンバー権限のユーザー（カンマ区切り複数指定）]',
     *     'members_readonly_ids' => '[閲覧のみ権限のユーザー（カンマ区切り複数指定）]',
     *     'name' => '[グループチャット名（必須）]',
     * )
     * </code></pre>
     * @return {Integer} チャットルームID、またはfalse
     */
    public function create($params)
    {
        $ret = $this->_post($this->_apiUrl, $params);
        return isset($ret['room_id']) ? $ret['room_id'] : false;
    }

    /**
     * チャットの名前、アイコンをアップデート
     *
     * @public
     * @method set
     * @param {Array} $params
     * <pre><code>array(
     *     'room_id' => '[チャットルームID]',
     *     'description' => '[チャット概要]',
     *     'icon_preset' => '[アイコン種類]',
     *     'name' => '[グループチャット名]',
     * )
     * </code></pre>
     * @return {Integer} チャットルームID、またはfalse
     */
    public function set($params)
    {
        $roomId = null;
        if (!isset($params['room_id'])) {
            $roomId = $this->_roomId;
            if (is_null($roomId)) {
                return false;
            }
        } else {
            $roomId = $params['room_id'];
            unset($params['room_id']);
        }

        $url = $this->_apiUrl . '/' . $roomId;
        $ret = $this->_put($url, $params);
        return isset($ret['room_id']);
    }

    /**
     * グループチャットを退席/削除する
     *
     * @public
     * @method remove
     * @param {Array} $params
     * <pre><code>array(
     *     'room_id' => '[チャットルームID]',
     *     'action_type' => '[退席するか、削除するか]',
     * )
     * </code></pre>
     * @return {Boolean} true
     */
    public function remove($params)
    {
        $roomId = null;
        if (!isset($params['room_id'])) {
            $roomId = $this->_roomId;
            if (is_null($roomId)) {
                return false;
            }
        } else {
            $roomId = $params['room_id'];
            unset($params['room_id']);
        }

        $url = $this->_apiUrl . '/' . $roomId;
        $this->_delete($url, $params);

        // 戻り値なし
        return true;
    }
}
