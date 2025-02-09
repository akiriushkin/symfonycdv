<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AppTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        exec('php bin/console doctrine:database:drop --force --env=test');
        exec('php bin/console doctrine:database:create --env=test');
        exec('php bin/console doctrine:migrations:migrate --no-interaction --env=test');

        exec('php bin/console doctrine:fixtures:load --no-interaction --env=test');
    }

    public function testApplication()
    {
        $client = static::createClient();

        $this->assertSubTest('Login', function () use ($client) {
            $client->request('POST', '/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode(['username' => 'user', 'password' => 'password']));
            $this->assertResponseStatusCodeSame(200);
        });

        $this->assertSubTest('Test page', function () use ($client) {
            $client->request('GET', '/');
            $this->assertResponseStatusCodeSame(200);
        });

        $this->assertSubTest('Error page', function () use ($client) {
            $client->request('GET', '/non-existent-page');
            $this->assertResponseStatusCodeSame(404);
            $this->assertJson($client->getResponse()->getContent());
        });

        $this->assertSubTest('Create player', function () use ($client) {
            $client->request('POST', '/players', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode(['name' => 'Player1334']));
            $this->assertResponseStatusCodeSame(200);
            $this->assertJson($client->getResponse()->getContent());
        });

        $this->assertSubTest('Show list of players', function () use ($client) {
            $client->request('GET', '/players');
            $this->assertResponseStatusCodeSame(200);
            $this->assertJson($client->getResponse()->getContent());
        });

        $this->assertSubTest('Create match', function () use ($client) {
            $client->request('POST', '/matches', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode(['gameMode' => 'Mode 123', 'duration' => 33]));
            $this->assertResponseStatusCodeSame(200);
        });

        $this->assertSubTest('Show list of matches', function () use ($client) {
            $client->request('GET', '/matches');
            $this->assertResponseStatusCodeSame(200);
        });


        $this->assertSubTest('Delete match', function () use ($client) {
            $client->request('DELETE', '/matches', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode(['matchId' => 1]));
            $this->assertResponseStatusCodeSame(200);
        });

        $this->assertSubTest('Show list of match results', function () use ($client) {
            $client->request('GET', '/match-results');
            $this->assertResponseStatusCodeSame(200);
        });

        $this->assertSubTest('Logout', function () use ($client) {
            $client->request('GET', '/logout');
            $this->assertResponseStatusCodeSame(200);
        });
    }

    private function assertSubTest(string $description, callable $test): void
    {
        try {
            $test();
            echo "✅ " . $description . " — OK\n";
        } catch (\Throwable $e) {
            echo "❌ " . $description . " — FAILED: " . $e->getMessage() . "\n";
            throw $e;
        }
    }
}
