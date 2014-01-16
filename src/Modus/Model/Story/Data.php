<?php

class Model_Story_Data extends Model_Base {
    
    public function getListOfStories() {
        $sql = 'SELECT * FROM story ORDER BY created_on DESC';
        $stories = $this->fetchAll($sql);
        return $stories;
    }
    
    public function getStoryComments($story_id) {
        $comment_sql = 'SELECT * FROM comment WHERE story_id = ?';
        $comments = $this->fetchAll($comment_sql, array($story_id));
        return $comments;
    }
    
    public function getStory($story_id) {
        $story_sql = 'SELECT * FROM story WHERE id = ?';
        $story = $this->fetchOne($story_sql, array($story_id));
        return $story;
    }
    
    public function createStory(array $params = array()) {
        $sql = 'INSERT INTO story (headline, url, created_by, created_on) VALUES (?, ?, ?, NOW())';
        $this->insert($sql, $params);
        $id = $this->lastInsertId();
        return $id;
    }
    
    public function createComment(array $params = array()) {
        $sql = 'INSERT INTO comment (created_by, created_on, story_id, comment) VALUES (?, NOW(), ?, ?)';
        return $this->insert($sql, $params);
    }
    
}