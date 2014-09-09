<?php

require_once('Abstract.php');

/**
 * 自分自身の情報モジュール
 *
 * @class Modules_Me
 * @extends Modules_Abstract
 */
class Modules_Me extends Modules_Abstract
{

    /**
     * チャットワークAPIエンドポイントパス
     *
     * @property ENDPOINT
     * @type String
     * @default '/me'
     */
    const ENDPOINT = '/me';

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
     * 自分自身の情報を取得
     *
     * @public
     * @method get
     * @return {Array} 自分自身の情報
     */
    public function get()
    {
        return $this->_get($this->_apiUrl, null);
    }
}
