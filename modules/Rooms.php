<?php

require_once('Abstract.php');

class Modules_Rooms extends Modules_Abstract
{

    const ENDPOINT = '/rooms';

    protected $_apiUrl;

    protected function _initSelf()
    {
        $this->_apiUrl = self::BASE_URL.self::ENDPOINT;
    }

    public function getList()
    {
        return $this->_get($this->_apiUrl, null);
    }
}
