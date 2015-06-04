<?php

use Modus\Config\Config;
use Aura\Di;

class SampleConfig extends Di\Config {
    public function define(Di\Container $di) {
    }
}

class ConfigTest extends PHPUnit_Framework_TestCase {

    public function testConstructorBuildsProperConfigObject() {
        $configdir = realpath(__DIR__ . '/../../assets/config');
        $containerBuilder = Mockery::mock('Aura\Di\ContainerBuilder');
        $containerBuilder = new Di\ContainerBuilder();
        $configuration = new Config(Config::ENV_TESTING, $configdir, $containerBuilder);

        $config = $configuration->getConfig();
        $expected = [
            'a' => 1,
            'config_classes' => ['SampleConfig'],
            'b' => 'abc',
            'c' => 123,
            'd' => 'test.php',
        ];

        $this->assertEquals($expected, $config);
        $container = $configuration->getContainer();
        $this->assertInstanceOf('Aura\Di\Container', $container);
        $this->assertInstanceOf('Aura\Di\ContainerBuilder', $configuration->getContainerBuilder());
    }

    /**
     * @expectedException Modus\Config\Exception\InvalidEnvironment
     * @expectedMessage is an invalid environment
     */
    public function testInvalidEnvironmentRaisesException() {
        $configdir = realpath(__DIR__ . '/../assets/config');
        $containerBuilder = Mockery::mock('Aura\Di\ContainerBuilder');
        $config = new Config('invalid', $configdir, $containerBuilder);
    }

}