<?php

require_once '../vendor/autoload.php';

class AuthTestController extends Modus\Controller\RequiresAuth {
    public function testfunc($a, $b, $c) {
        return [$a, $b, $c];
    }

    protected function authValid($action) {
        $this->response->setContent('invalid');
        return false;
    }
}

class ControllerAuthRequiredTest extends PHPUnit_Framework_TestCase {

    public function testAuthRequiredReturnsImmediately() {

        $obj = new AuthTestController(
            Mockery::mock('Aura\View\TwoStep'),
            Mockery::mock('Modus\Session\Aura'),
            Mockery::mock('Aura\Web\Context'),
            new Aura\Web\Response,
            Mockery::mock('Modus\Common\Model\Factory'),
            new Monolog\Logger('test1', [new Monolog\Handler\NullHandler]),
            new Monolog\Logger('test2', [new Monolog\Handler\NullHandler])
        );

        $result = $obj->exec('testfunc', ['a' => 1, 'b' => 2, 'c' => 3]);
        $this->assertInstanceOf('Aura\Web\Response', $result);
        $this->assertEquals($result->getContent(), 'invalid');
    }

}