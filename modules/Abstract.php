<?php

define('DS', DIRECTORY_SEPARATOR);

/**
 * モジュール抽象クラス
 */
abstract class Modules_Abstract
{
    /**
     * チャットワークAPIエンドポイントベースURL
     *
     * @property BASE_URL
     * @type String
     * @default 'https://api.chatwork.com/v1'
     */
    const BASE_URL = 'https://api.chatwork.com/v1';

    /**
     * APIキー
     *
     * @protected
     * @property $_apiKey
     * @type String
     */
    protected $_apiKey;

    /**
     * API URL
     *
     * @protected
     * @property $_apiUrl
     * @type String
     */
    protected $_apiUrl;

    /**
     * モジュール抽象クラス
     *
     * @final
     * @public
     * @class Modules_Abstract
     * @constructor
     * @param {String} $apiKey APIキー
     */
    final public function __construct($apiKey)
    {
        $this->init($apiKey);
    }

    /**
     * 初期化メソッド
     *
     * @public
     * @method init
     * @param {String} [$apiKey=null] APIキー
     */
    public function init($apiKey = null)
    {
        $this->_apiKey = $apiKey;
        $this->_initSelf();
    }

    /**
     * APIキー設定メソッド
     *
     * @public
     * @method setApiKey
     * @param {String} $apiKey APIキー
     */
    public function setApiKey($apiKey)
    {
        $this->_apiKey = $apiKey;
    }

    /**
     * サブクラス初期化抽象メソッド
     *
     * @abstract
     * @protected
     * @method _initSelf
     */
    abstract protected function _initSelf();

    /**
     * POSTメソッド
     *
     * @protected
     * @method _post
     * @param {String} $url URL
     * @param {Array} $body リクエストボディ
     * @return {Array} デコードされたレスポンス
     */
    protected function _post($url, $body)
    {
        return $this->_exec($url, $body, 'POST');
    }

    /**
     * GETメソッド
     *
     * @protected
     * @method _get
     * @param {String} $url URL
     * @param {Array} $body リクエストボディ
     * @return {Array} デコードされたレスポンス
     */
    protected function _get($url, $body)
    {
        return $this->_exec($url, $body, 'GET');
    }

    /**
     * PUTメソッド
     *
     * @protected
     * @method _put
     * @param {String} $url URL
     * @param {Array} $body リクエストボディ
     * @return {Array} デコードされたレスポンス
     */
    protected function _put($url, $body)
    {
        return $this->_exec($url, $body, 'PUT');
    }

    /**
     * DELETEメソッド
     *
     * @protected
     * @method _delete
     * @param {String} $url URL
     * @param {Array} $body リクエストボディ
     * @return {Array} デコードされたレスポンス
     */
    protected function _delete($url, $body)
    {
        return $this->_exec($url, $body, 'DELETE');
    }

    /**
     * 送信実行メソッド
     *
     * @protected
     * @method _exec
     * @param {String} $url URL
     * @param {Array} $body リクエストボディ
     * @param {String} $method 送信メソッド
     * @return {Array} デコードされたレスポンス
     */
    protected function _exec($url, $body, $method)
    {
        $content = '';

        // 送信データ
        if (!is_null($body)) {
            $content = http_build_query($body, "", "&");
            if ($method === 'GET') {
                $url .= '?'.$content;
            }
        }

        // Header
        $header = array(
            'Content-Type: application/x-www-form-urlencoded',
            'Content-Length: '.strlen($content),
            'X-ChatWorkToken: ' . $this->_apiKey,
        );

        $context = array(
            'http' => array(
                'method'  => $method,
                'header'  => implode("\r\n", $header),
                'content' => $content,
            )
        );

        $result =  file_get_contents($url, false, stream_context_create($context));
        return json_decode($result, true);
    }

}
