<?php

require_once('Abstract.php');

/**
 * コンタクトユーザモジュール
 *
 * @module Modules_Contacts
 */
class Modules_Contacts extends Modules_Abstract
{
    /**
     * コンタクトユーザクラス
     *
     * @class Modules_Contacts
     * @extends Modules_Abstract
     * @constructor
     */

    /**
     * チャットワークAPIエンドポイントパス
     *
     * @property ENDPOINT
     * @type String
     * @default '/contacts'
     */
    const ENDPOINT = '/contacts';

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
     * 自分のコンタクト一覧を取得
     *
     * @public
     * @method get
     * @return {Array} 自分のコンタクトリスト、ない場合はnull
     */
    public function get()
    {
        return $this->_get($this->_apiUrl, null);
    }
}
