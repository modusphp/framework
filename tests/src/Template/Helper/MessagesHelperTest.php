<?php

class MessagesHelperTest extends PHPUnit_Framework_TestCase {

    public function testMessagesProcessCorrectly() {

        $segment = Mockery::mock('Aura\Session\Segment');
        $segment->shouldReceive('getFlash')->withArgs(['success'])->andReturn('abc');
        $segment->shouldReceive('getFlash')->withArgs(['failure'])->andReturn('def');

        $m = Mockery::mock('Aura\Session\Manager');
        $m->shouldReceive('newSegment')->once()->andReturn($segment);
        $session = new Modus\Session\Session($m);

        $messages = new Modus\Template\Helper\Messages($session);
        $values = $messages();

        $shouldGet = '<div class="failure">def</div><div class="success">abc</div>';

        $this->assertEquals($values, $shouldGet);
    }

    public function testNoMessagesIsNull() {

        $segment = Mockery::mock('Aura\Session\Segment');
        $segment->shouldReceive('getFlash')->withArgs(['success'])->andReturn('');
        $segment->shouldReceive('getFlash')->withArgs(['failure'])->andReturn('');

        $m = Mockery::mock('Aura\Session\Manager');
        $m->shouldReceive('newSegment')->once()->andReturn($segment);
        $session = new Modus\Session\Session($m);

        $messages = new Modus\Template\Helper\Messages($session);
        $values = $messages();

        $shouldGet = null;

        $this->assertEquals($values, $shouldGet);
    }

}