<?php

namespace Modus\Request;

class Http implements RequestInterface {

    protected $_data = array();
    protected $_serverData = array();

    public function __construct() {
        $this->_setGet($_GET);
        $this->_setPost($_POST);
        $this->_setServer($_SERVER); 
    }

    public function _setGet(array $getData) {
        $this->_data = array_merge($this->_data, $getData);
    }

    public function _setPost(array $postData) {
        $this->_data = array_merge($this->_data, $postData);
    }

    public function _setServer(array $serverData)    
    {
        $this->_serverData = $serverData;
    }

    public function get($key, $default = '') {
        if(isset($this->_data[$key])) {
            return $this->_data[$key];
        }
        
        return $default;
    }

    public function getServer($key, $default = '') {
        if(isset($this->_serverData[$key])) {
            return $this->_serverData[$key];
        }
        
        return $default;
    }
}
