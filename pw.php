<?php

/**
 * PhatWorkクラス
 */
class PW
{

    /**
     * インスタンス保持
     *
     * @protected
     * @static
     * @property $_instance
     * @type PW
     */
    protected static $_instance;

    /**
     * APIキー
     *
     * @protected
     * @property $_apiKey
     * @type String
     */
    protected $_apiKey;

    /**
     * クラス参照保持
     *
     * @protected
     * @property $_classes
     * @type Array
     */
    protected $_classes = array();

    /**
     * PhatWorkクラス
     *
     * @public
     * @class PW
     * @constructor
     * @param {String} $apiKey APIキー
     */
    public function __construct($apiKey)
    {
        $this->_apiKey = $apiKey;
    }

    /**
     * インスタンス取得メソッド
     *
     * @public
     * @static
     * @method getInstance
     * @param {String} $apiKey APIキー
     */
    public static function getInstance($apiKey)
    {
        if (!self::$_instance) {
            self::$_instance = new self($apiKey);
        }
        return self::$_instance;
    }

    /**
     * クラス取得オーバーロード
     *
     * @public
     * @method __get
     * @param {String} $key エンドポイントキー
     */
    public function __get($key)
    {
        $key = ucfirst(strtolower($key));
        $className = 'Modules_'.$key;

        if (!isset($this->_classes[$className])) {
            $fileName = "modules/{$key}.php";
            if (!file_exists($fileName)) {
                return null;
            }
            require_once($fileName);
            $class = new $className($this->_apiKey);
            $this->_classes[$className] = $class;
        } else {
            $class = $this->_classes[$className];
        }
        return $class;
    }
}
