<?php

class Response_HttpRedirect implements
            Response_Interface {
    
    public function setUrl($url) {
        $this->_url = $url;
    }

    public function renderResponse() {
        header("Location: " . $this->_url);
    }
}
