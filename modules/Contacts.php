<?php

require_once('Abstract.php');

/**
 * コンタクトユーザモジュール
 *
 * @class Modules_Contacts
 * @extends Modules_Abstract
 */
class Modules_Contacts extends Modules_Abstract
{

    /**
     * チャットワークAPIエンドポイントパス
     *
     * @property ENDPOINT
     * @type String
     * @default '/contacts'
     */
    const ENDPOINT = '/contacts';

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
     * 自分のコンタクト一覧を取得
     *
     * @public
     * @method get
     * @return {Array} 自分のコンタクトリスト
     */
    public function get()
    {
        return $this->_get($this->_apiUrl, null);
    }
}
