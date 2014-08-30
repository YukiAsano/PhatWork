<?php

class PW
{

    const BASE_URL = 'https://api.chatwork.com/v1';

    protected static $_instance;

    protected $_apiKey;
    protected $_roomId;
    protected $_title = null;

    public function __construct($apiKey)
    {
        $this->_apiKey = $apiKey;
    }

    public static function getInstance($apiKey)
    {
        if (!self::$_instance) {
            self::$_instance = new self($apiKey);
        }
        return self::$_instance;
    }

    /**
     * 値の取得
     */
    public function __get($key)
    {
        $key = ucfirst(strtolower($key));
        $fileName = "modules/{$key}.php";
        if (!file_exists($fileName)) {
            return null;
        }
        require_once($fileName);
        $className = 'Modules_'.$key;
        $class = new $className($this->_apiKey);
        return $class;
    }

}

