<?php

require_once '../vendor/autoload.php';

class TestController extends Modus\Controller\Base {
    public function testfunc($a, $b, $c) {
        return [$a, $b, $c];
    }
}

class ControllerBaseTest extends PHPUnit_Framework_TestCase {

    public function testExecWorks() {

        $obj = new TestController(
            Mockery::mock('Aura\View\TwoStep'),
            Mockery::mock('Modus\Session\Aura'),
            Mockery::mock('Aura\Web\Context'),
            Mockery::mock('Aura\Web\Response'),
            Mockery::mock('Modus\Common\Model\Factory'),
            new Monolog\Logger('test1', [new Monolog\Handler\NullHandler]),
            new Monolog\Logger('test2', [new Monolog\Handler\NullHandler])
        );

        $result = $obj->exec('testfunc', ['a' => 1, 'b' => 'a', 'c' => 3]);
        $this->assertEquals($result, [1, 'a', 3]);
    }
}