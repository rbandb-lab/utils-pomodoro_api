<?php

declare(strict_types=1);

namespace PomodoroTests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeActionTest extends WebTestCase
{
    private $client = null;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();
    }

    public function testPingHome()
    {
        $this->client->request('GET', '/');
        $response = $this->client->getResponse();
        self::assertTrue($response->isSuccessful());
        self::assertSame('OK', $response->getContent());
    }
}
