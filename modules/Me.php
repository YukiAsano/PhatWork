<?php

require_once('Abstract.php');

/**
 * 自分自身の情報モジュール
 *
 * @module Modules_Me
 */
class Modules_Me extends Modules_Abstract
{
    /**
     * 自分自身の情報クラス
     *
     * @class Modules_Me
     * @extends Modules_Abstract
     * @constructor
     */

    /**
     * チャットワークAPIエンドポイントパス
     *
     * @property ENDPOINT
     * @type String
     * @default '/me'
     */
    const ENDPOINT = '/me';

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
