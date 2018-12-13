<?php
namespace Modus\Payload;

use PHPUnit\Framework\TestCase;

class PayloadTest extends TestCase
{
    public function test()
    {
        $payload = new Payload();

        $payload
            ->setStatus($payload::SUCCESS)
            ->setInput('input')
            ->setOutput('output')
            ->setMessages('messages')
            ->setExtras('extras');

        $this->assertSame($payload::SUCCESS, $payload->getStatus());
        $this->assertSame('input', $payload->getInput());
        $this->assertSame('output', $payload->getOutput());
        $this->assertSame('messages', $payload->getMessages());
        $this->assertSame('extras', $payload->getExtras());
    }
}
