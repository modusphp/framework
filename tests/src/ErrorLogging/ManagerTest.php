<?php

use Modus\ErrorLogging\Manager;

class ManagerTest extends PHPUnit_Framework_TestCase {

    public function testRegisterAndDeregister() {
        $runner = Mockery::mock('League\BooBoo\Runner');
        $runner->shouldReceive('register')->once()->andReturn(true);
        $runner->shouldReceive('deregister')->once()->andReturn(true);

        $accept = Mockery::mock('Aura\Accept\Accept');

        $manager = new Manager($runner, $accept, []);
        $manager->registerErrorHandler(true);
        $manager->registerErrorHandler(false);
    }

    public function testGetErrorHandler() {
        // This function is somewhat useless to test, since we're just evaluating that we got a mock back.
        $runner = Mockery::mock('League\BooBoo\Runner');
        $accept = Mockery::mock('Aura\Accept\Accept');

        $manager = new Manager($runner, $accept, []);
        $this->assertTrue(($runner === $manager->getErrorHandler()));
    }

    public function testGetLoggerArrayAndByName() {
        $loggers = [
            'a' => new Monolog\Logger('abc'),
            'b' => new Monolog\Logger('def')
        ];


        $runner = Mockery::mock('League\BooBoo\Runner');
        $accept = Mockery::mock('Aura\Accept\Accept');

        $manager = new Manager($runner, $accept, $loggers);
        $all = $manager->getLogger();
        $this->assertInternalType('array', $all);
        $this->assertEquals($loggers, $all);

        $this->assertTrue(($loggers['a'] == $manager->getLogger('a')));
        $this->assertTrue(($loggers['b'] == $manager->getLogger('b')));
    }

    /**
     * @expectedException Modus\ErrorLogging\Exception\LoggerNotRegistered
     * @expectedMessage 'not registered'
     */
    public function testUnregisteredLoggerThrowsException() {
        $runner = Mockery::mock('League\BooBoo\Runner');
        $accept = Mockery::mock('Aura\Accept\Accept');

        $manager = new Manager($runner, $accept, []);
        $manager->getLogger('abc');
    }

}