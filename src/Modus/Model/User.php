<?php

class Model_User extends Model_Base {
    
    public function createUser(array $params = array()) {
        $sql = 'INSERT INTO user (username, email, password) VALUES (?, ?, ?)';
        return $stmt = $this->insert($sql, $params);
    }
    
    public function checkUsername($username) {
        $check_sql = 'SELECT * FROM user WHERE username = ?';
        $check_stmt = $this->fetchOne($check_sql, array($username));
        return $this->rowCount();
    }
    
    public function authenticateUser($username, $password) {
        $password = md5($username . $password); // THIS IS NOT SECURE. DO NOT USE IN PRODUCTION.
        $sql = 'SELECT * FROM user WHERE username = ? AND password = ? LIMIT 1';
        $user = $this->fetchOne($sql, array($username, $password));
        return array('authenticated' => $this->rowCount(), 'user' => $user);
    }
    
    public function getUserData($username) {
        $dsql = 'SELECT * FROM user WHERE username = ?';
        return $this->fetchOne($dsql, array($username));    
    }
    
    public function changeUserPassword($username, $password) {
        $sql = 'UPDATE user SET password = ? WHERE username = ?';
        $params = array(
           md5($username . $password), // THIS IS NOT SECURE. 
           $username,
        );
        return $this->insert($sql, $params);     
    }
}