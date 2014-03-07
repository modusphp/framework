<?php
namespace Modus\Common\Model;

class Factory {

    public function __construct(array $map = []) {

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