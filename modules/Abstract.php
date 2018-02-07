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
     * @default 'https://api.chatwork.com/v2'
     */
    const BASE_URL = 'https://api.chatwork.com/v2';

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

        try {

            $result =  file_get_contents($url, false, stream_context_create($context));

        } catch (ErrorException $e) {

            // TODO: エラー処理どうしよう
            $trace = $e->getTrace();

            $logDir = realpath(__DIR__.DS.'../').DS.'logs';

            $err = array();
            $err[] = date("Y-m-d H:i:s");
            $err[] = $trace[0]['args'][1] . $trace[0]['args'][2].'line: '.$trace[0]['args'][3];
            $err[] = json_encode($trace[0]['args'][4], true);
            $err[] = '--';
            $err[] = '';

            error_log(implode("\n", $err), 3, $logDir.DS.'error.log');

            /*
            $status = $trace[0]['args'][4]['http_response_header'][0];
            switch ($status) {
                case 'HTTP/1.1 401 Unauthorized':
                    $ret = array(
                        'error' => 1,
                        'status' => 401,
                        'message' => '認証に失敗しました。APIキーをご確認ください。'
                    );
                    break;
                case 'HTTP/1.1 501 Not Implemented':
                    $ret = array(
                        'error' => 1,
                        'status' => 501,
                        'message' => '未実装のAPIです。'
                    );
                    break;
                case 'HTTP/1.1 400 Bad Request':
                    $ret = array(
                        'error' => 1,
                        'status' => 501,
                        'message' => 'パラメータが不足しています。'
                    );
                    break;
                default:
                    $ret = array(
                        'error' => 1,
                        'status' => 418,
                        'message' => 'I\'m a teapot'
                    );
            }
            return $ret;
             */
            return false;
        }
        return json_decode($result, true);
    }

}

class PWErrorException extends ErrorException
{
    protected $severities = array(
        E_ERROR             => 'ERROR',
        E_WARNING           => 'WARNING',
        E_PARSE             => 'PARSING ERROR',
        E_NOTICE            => 'NOTICE',
        E_CORE_ERROR        => 'CORE ERROR',
        E_CORE_WARNING      => 'CORE WARNING',
        E_COMPILE_ERROR     => 'COMPILE ERROR',
        E_COMPILE_WARNING   => 'COMPILE WARNING',
        E_USER_ERROR        => 'USER ERROR',
        E_USER_WARNING      => 'USER WARNING',
        E_USER_NOTICE       => 'USER NOTICE',
        E_STRICT            => 'STRICT NOTICE',
        E_RECOVERABLE_ERROR => 'RECOVERABLE ERROR',
        E_DEPRECATED        => 'DEPRECATED',
        E_USER_DEPRECATED   => 'USER DEPRECATED',
    );

    public function getFullMessage()
    {
        return sprintf(
            '%s: %s in %s on line %u',
            $this->getSeverityString(),
            $this->getMessage(),
            $this->getFile(),
            $this->getLine()
        );
    }

    public function getSeverityString()
    {
        $severity = $this->getSeverity();
        $ret = 'UNKNOWN ERROR('.$severity.')';
        if (isset($this->severities[$severity]))
        {
            $ret = $this->severities[$severity];
        }
        return $ret;
    }
}

set_error_handler(function ($errno, $errstr, $errfile, $errline ) {
    $errorException = new PWErrorException($errstr, 0, $errno, $errfile, $errline);
    echo $errorException->getFullMessage(), PHP_EOL;
    throw $errorException;
});
