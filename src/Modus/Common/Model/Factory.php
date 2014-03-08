<?php
namespace Modus\Common\Model;

class Factory {

    protected $map = array();

    public function __construct(array $map = []) {
        $this->map = $map;
    }

    public function newInstance($modelName) {
        if(!isset($this->map[$modelName])) {
            throw new Exceptions\NotFound('Model ' . $modelName . ' was not in the map.');
        }

        $factory = $this->map[$modelName];
        $model = $factory();
        return $model;
    }
}