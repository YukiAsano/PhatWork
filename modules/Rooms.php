<?php

require_once('Abstract.php');
require_once('Rooms/Room.php');

/**
 * チャットルーム管理モジュール
 *
 * @module Modules_Rooms
 * @requires Modules_Rooms_Room
 */
class Modules_Rooms extends Modules_Abstract
{
    /**
     * チャットルーム管理クラス
     *
     * @class Modules_Rooms
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
     * チャットルーム保持
     *
     * @private
     * @property $_rooms
     * @type Array
     */
    private $_rooms = array();

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
     * チャットルームインスタンス取得
     *
     * @public
     * @method getRoom
     * @param {Integer} $roomId チャットルームID
     * @return {Modules_Rooms_Room} チャットルームインスタンス
     */
    public function getRoom($roomId)
    {
        if (!isset($this->_rooms[$roomId])) {
            $childClsName = get_class().'_Room';
            $roomInstance = new $childClsName($this->_apiKey);
            $this->_rooms[$roomId] = $roomInstance->setId($roomId);
        }
        return $this->_rooms[$roomId];
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
     * グループチャットを新規作成
     *
     * @public
     * @method create
     * @param {Array} $params パラメータ配列
     * <pre><code>array(
     *     'description' => '[チャット概要]',
     *     'icon_preset' => '[アイコン種類]',
     *     'members_admin_ids' => '[管理者権限のユーザー（必須、カンマ区切り複数指定）]',
     *     'members_member_ids' => '[メンバー権限のユーザー（カンマ区切り複数指定）]',
     *     'members_readonly_ids' => '[閲覧のみ権限のユーザー（カンマ区切り複数指定）]',
     *     'name' => '[グループチャット名（必須）]',
     * )
     * </code></pre>
     * @param {Boolean} [$bReturnInstance=true] インスタンス返却フラグ
     * @return {mixed} チャットルームID、チャットルームインスタンス、またはfalse
     */
    public function create($params, $bReturnInstance = true)
    {
        $ret = $this->_post($this->_apiUrl, $params);

        $return = null;
        if (isset($ret['room_id'])) {
            if ($bReturnInstance) {
                $return = $this->getRoom($ret['room_id']);
            }
        } else {
            $return = false;
        }
        return is_null($return) ? $ret['room_id'] : $return;
    }
}
