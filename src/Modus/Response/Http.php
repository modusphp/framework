<?php

class Response_Http implements Response_Interface
{

    protected $_data = array();
    protected $_viewPath;
    protected $_layoutPath;
    
    public function set($key, $value) {
        $this->_data[$key] = $value;
    }
    
    public function setArgs(array $args) {
        $this->_data = array_merge($this->_data, $args);
    }

    public function setView($viewPath) {
        $this->_viewPath = $viewPath;
    }
    
    public function setLayout($layoutPath) {
        $this->_layoutPath = $layoutPath;
    }
    
    public function showView(array $args, $viewPath, $layoutPath) {
        $this->setArgs($args);
        $this->setView($viewPath);
        $this->setLayout($layoutPath);
        return $this->renderResponse();
    }

    public function renderResponse() {
        $data = $this->_data;
        ob_start();
        require_once $this->_viewPath;
        $content = ob_get_clean();
        
        ob_start();
        require_once $this->_layoutPath;
        return ob_get_clean();
    }
}
