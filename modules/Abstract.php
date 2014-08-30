<?php

define('DS', DIRECTORY_SEPARATOR);

abstract class Modules_Abstract
{

    const BASE_URL = 'https://api.chatwork.com/v1';

    protected $_apiKey;

    final public function __construct($apiKey = null)
    {
        $this->init($apiKey);
    }

    public function init($apiKey = null)
    {
        $this->_apiKey = $apiKey;
        $this->_initSelf();
    }
    public function setApiKey($apiKey)
    {
        $this->_apiKey = $apiKey;
    }

    abstract protected function _initSelf();

    protected function _post($url, $body)
    {
        return $this->_exec($url, $body, 'POST');
    }
    protected function _get($url, $body)
    {
        return $this->_exec($url, $body, 'GET');
    }
    protected function _exec($url, $body, $method)
    {
        // 送信データ
        $data = array(
            'body' => $body,
        );
        $content = http_build_query($data, "", "&");

        // Header
        $header = array(
            'X-ChatWorkToken: ' . $this->_apiKey,
        );

        $context = array(
            'http' => array(
                'method'  => $method,
                'header'  => implode("¥r¥n", $header),
                'content' => $content,
            )
        );

        $result =  file_get_contents($url, false, stream_context_create($context));
        return json_decode($result, true);
    }

}
