<?php

require_once '../vendor/autoload.php';

class ModelFactoryTest extends PHPUnit_Framework_TestCase {

    public function testModelFactoryExecutedCorrectly() {

        $closure = function() {
            return 'abc';
        };

        $obj = new Modus\Common\Model\Factory(['testclosure' => $closure]);
        $result = $obj->newInstance('testclosure');

        $this->assertEquals($result, 'abc');
    }

    /**
     * @expectedException Modus\Common\Model\Exceptions\NotFound
     */
    public function testModelFactoryExceptionWhenModelDoesntExist() {
        $obj = new Modus\Common\Model\Factory();
        $obj->newInstance('doesntexist');
    }

}