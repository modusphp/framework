<?php

class Model_Story_Object {
    
    protected $id;
    protected $headline;
    protected $created_by;
    protected $url;
    protected $created_on;
    
    protected $comments = array();
    
    public function __call($name, $arguments) {
        
        $method = str_replace('set', '', strtolower($name));
        if(!isset($this->$method)) {
            return false;
        }
        $this->$method = $arguments[0];
    }
    
    public function initData($id, $headline, $created_by, $url, $created_on, array $comments = array()) {
        $this->id = $id;
        $this->headline = $headline;
        $this->created_by = $created_by;
        $this->url = $url;
        $this->comments = $comments;
        $this->created_on = $created_on;
        return $this;
    }
    
    public function getComments() {
        return $this->comments;
    }
    
    public function getCommentCount() {
        return count($this->comments);
    }
    
    public function getStory() {
        $story_array = array(
          'id' => $this->id,
          'headline' => $this->headline,
          'created_by' => $this->created_by,
          'url' => $this->url,
          'created_on' => $this->created_on,
        );
        
        return $story_array;
    }
    
}