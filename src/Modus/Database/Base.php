<?php

abstract class Database_Base {
    
    protected $db; 
    protected $dbconfig = array();   
    
    public function __construct(array $dbconfig = array()) {
        $this->dbconfig = $dbconfig;
        $this->_connect();
    }
    
    abstract public function prepare($sql, array $options = array());
    
    abstract protected function _connect();
    
}