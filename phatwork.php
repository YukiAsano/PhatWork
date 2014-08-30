<?php

define('DS', DIRECTORY_SEPARATOR);

class PhatWork
{

    const BASE_URL = 'https://api.chatwork.com/v1';

    protected $_apiKey;
    protected $_roomId;
    protected $_title = null;

    public function __construct()
    {
    }

    public function info($msg)
    {
        $msg = "[info]{$msg}[/info]";
        $this->_exec($msg);
    }

    public function post($msg)
    {
        $this->_exec($msg);
    }

    public function setApiKey($apiKey)
    {
        $this->_apiKey = $apiKey;
    }

    public function setRoomId($roomId)
    {
        $this->_roomId = $roomId;
    }

    public function setTitle($title)
    {
        $this->_title = $title;
    }

    protected function _exec($msg)
    {
        $url = self::BASE_URL . DS . 'rooms' . DS . $this->_roomId . DS . 'messages';

        // POSTデータ
        $data = array(
            'body' => $this->_addTitle($msg),
        );
        $data = http_build_query($data, "", "&");

        // Header
        $header = array(
            'X-ChatWorkToken: ' . $this->_apiKey,
        );

        $context = array(
            'http' => array(
                'method'  => 'POST',
                'header'  => implode("¥r¥n", $header),
                'content' => $data,
            )
        );

        $result =  file_get_contents($url, false, stream_context_create($context));
        return json_decode($result);
    }

    protected function _addTitle($msg)
    {
        if ($this->_title !== null) {
            $msg = "[title]{$this->_title}[/title]{$msg}";
        }
        return $msg;
    }

}

