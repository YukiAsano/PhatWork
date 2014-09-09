<?php

require_once('Abstract.php');

class Modules_Rooms extends Modules_Abstract
{

    const ENDPOINT = '/rooms';

    protected $_apiUrl;

    protected $_roomId;

    protected function _initSelf()
    {
        $this->_apiUrl = self::BASE_URL.self::ENDPOINT;
    }

    public function setId($roomId)
    {
        $this->_roomId = $roomId;
        return $this;
    }

    public function getList()
    {
        return $this->_get($this->_apiUrl, null);
    }

    public function get($roomId = null)
    {
        if (is_null($roomId)) {
            $roomId = $this->_roomId;
            if (is_null($roomId)) {
                return false;
            }
        }

        $url = $this->_apiUrl . '/' . $roomId;
        return $this->_get($url, null);
    }

    public function set($params)
    {
        $roomId = null;
        if (!isset($params['room_id'])) {
            $roomId = $this->_roomId;
            if (is_null($roomId)) {
                return false;
            }
        } else {
            $roomId = $params['room_id'];
            unset($params['room_id']);
        }

        $url = $this->_apiUrl . '/' . $roomId;
        $ret = $this->_put($url, $params);
        return isset($ret['room_id']);
    }

    public function remove($params)
    {
        $roomId = null;
        if (!isset($params['room_id'])) {
            $roomId = $this->_roomId;
            if (is_null($roomId)) {
                return false;
            }
        } else {
            $roomId = $params['room_id'];
            unset($params['room_id']);
        }

        $url = $this->_apiUrl . '/' . $roomId;
        $this->_delete($url, $params);

        // 戻り値なし
        return true;
    }

}
