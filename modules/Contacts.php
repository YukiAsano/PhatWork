<?php

require_once('Abstract.php');

class Modules_Contacts extends Modules_Abstract
{

    const ENDPOINT = '/contacts';

    protected $_apiUrl;

    protected function _initSelf()
    {
        $this->_apiUrl = self::BASE_URL.self::ENDPOINT;
    }

    public function get()
    {
        return $this->_get($this->_apiUrl, null);
    }
}
