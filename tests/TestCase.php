<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';
    protected $request_history = [];

    /**
     * Creates a mocked GuzzleHttp\Client with specified stack
     *
     * @param array MockHandler stack to be injected on the client
     * @return GuzzleHttp\Client client with mocked stack
     */
    protected function mockGuzzleClient(array $stack) {

      $history = Middleware::history($this->request_history);
      $mock = new MockHandler($stack);
      $handler = HandlerStack::create($mock);
      $handler->push($history);
      $client = new Client(['handler' => $handler]);
      return $client;
    }

    protected function mockedGuzzleConfig() {
      return array(
        'api_key' => 'mocked',
        'base_uri' => 'http://mocked.com/'
      );
    }


    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }
}
