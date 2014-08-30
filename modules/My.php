<?php

require_once('Abstract.php');

class Modules_My extends Modules_Abstract
{

    const ENDPOINT = '/my';
    const STATUS_URL = '/status';
    const TASKS_URL = '/tasks';

    protected $_apiUrl;

    protected function _initSelf()
    {
        $this->_apiUrl = self::BASE_URL.self::ENDPOINT;
    }

    public function getStatus()
    {
        return $this->_get($this->_apiUrl.self::STATUS_URL, null);
    }
    public function getTasks()
    {
        return $this->_get($this->_apiUrl.self::TASKS_URL, null);
    }
}
