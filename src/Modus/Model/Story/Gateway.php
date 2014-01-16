<?php

class Model_Story_Gateway {
    
    protected $data_layer;
    
    public function __construct(Model_Base $data_layer) {
        $this->data_layer = $data_layer;
    }
    
    public function storyList() {
        $stories_to_return = array();
        $stories = $this->data_layer->getListOfStories();
        foreach($stories as $story) {
            $comments = $this->data_layer->getStoryComments($story['id']);
            $storyObj = new Model_Story_Object();
            $storyObj->initData($story['id'], 
                        $story['headline'], 
                        $story['created_by'], 
                        $story['url'], 
                        $story['created_on'],
                        $comments);
            $stories_to_return[] = $storyObj;
        }
        
        return $stories_to_return;
    }
    
    public function getStory($story_id) {
        $story_id = (int)$story_id;
        if(empty($story_id)) {
            throw new Model_Story_Exception('invalid ID');
        }
        
        $story = $this->data_layer->getStory($story_id);
        if(empty($story)) {
            return false;
        }
        
        $comments = $this->data_layer->getStoryComments($story['id']);
        $storyObj = new Model_Story_Object();
        $storyObj->initData($story['id'], 
                    $story['headline'], 
                    $story['created_by'], 
                    $story['url'], 
                    $story['created_on'],
                    $comments);
        
        return $storyObj;
    }
    
    public function saveStory(array $params = array()) {
        
        $expected_keys = array('username', 'headline', 'url');
        foreach($expected_keys as $expected_key) {
            if(!isset($params[$expected_key])) {
                throw new Model_Story_Exception('The parameters you passed in were not valid.');
            }
        }
        
        if(!filter_var($params['url'], FILTER_VALIDATE_URL)) {
            throw new Model_Story_Exception('The URL provided was not valid.');
        }
        
        return $this->data_layer->createStory(array_values($params));         
    }
    
    public function addComment($story_id, $created_by, $comment) {
        
        $story_id = (int)$story_id;
        
        if(empty($story_id) || empty($created_by) || empty($comment)) {
            throw new Model_Story_Exception('The comment parameters provided were invalid');
        }
        
        $params = array(
            $created_by,
            $story_id,
            filter_var($comment, FILTER_SANITIZE_FULL_SPECIAL_CHARS)
        );
        
        return $this->data_layer->createComment($params);
        
    }
    
}