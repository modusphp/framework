<?php

class Database_Mysql extends Database_Base {
    
    protected function _connect() {
        $dbconfig = $this->dbconfig;
        $dsn = 'mysql:host=' . $dbconfig['host'] . ';dbname=' . $dbconfig['name'];
        $this->db = new PDO($dsn, $dbconfig['user'], $dbconfig['pass']);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    public function prepare($sql, array $options = array()) {
        $stmt = $this->db->prepare($sql, $options);
        return new Database_Statement_Mysql($this, $stmt);
    }
    
    public function lastInsertId() {
        return $this->db->lastInsertId();
    }
}