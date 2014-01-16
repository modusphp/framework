<?php

class Database_Statement_Mysql implements Database_Statement_Interface {
    
    protected $db;
    protected $stmt;
    
    public function __construct(Database_Mysql $db, PDOStatement $stmt) {
        $this->db = $db;
        $this->stmt = $stmt;
    }
    
    public function fetch($fetch_type = PDO::FETCH_ASSOC) {
        return $this->stmt->fetch($fetch_type);
    }
    
    public function fetchAll($fetch_type = PDO::FETCH_ASSOC) {
        return $this->stmt->fetchAll($fetch_type);
    }
    
    public function execute(array $params = array()) {
        return $this->stmt->execute($params);
    }
    
    public function rowCount() {
        return $this->stmt->rowCount();
    }
    
}