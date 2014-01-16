<?php

class Model_Base {
    
    protected $db;
    protected $last_query;
    
    public function __construct(Database_Base $db) {
        $this->db = $db;
    }
    
    protected function fetchOne($sql, array $args = array()) {
        $stmt = $this->_do_prepare_and_query($sql, $args);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    protected function fetchAll($sql, array $args = array()) {
        $stmt = $this->_do_prepare_and_query($sql, $args);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    protected function insert($sql, array $args = array()) {
        $result = $this->_do_insert_or_delete($sql, $args);
        $this->last_insert_id = $this->db->lastInsertId();
        return $result;
    }

    protected function delete($sql, array $args = array()) {
        return $this->_do_insert_or_delete($sql, $args);
    }

    protected function rowCount() {
        if($this->last_query instanceof Database_Statement_Interface) {
            return $this->last_query->rowCount();
        }
        return 0;
    }

    protected function lastInsertId() {
        return $this->last_insert_id;
    }

    protected function _do_prepare_and_query($sql, array $args = array()) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($args);
        $this->last_query = $stmt;
        return $stmt;
    }

    protected function _do_insert_or_delete($sql, array $args = array()) {
        $stmt = $this->db->prepare($sql);
        $this->last_query = $stmt;
        return $stmt->execute($args);
    }
    
}