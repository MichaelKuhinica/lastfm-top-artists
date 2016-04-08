<?php

use App\Helpers\LastFmClient;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;

class LastFmClientTest extends TestCase
{
    /**
     * Test top artists by country with success, it should build
     * the query_string correctly when navigating to another page
     *
     * @return void
     */
    public function testTopArtistsByCountriesPageTwoWithSuccess()
    {
      $mockedStack = [
        new Response(200, [], $this->sample_response_success)
      ];
      $client = $this->mockGuzzleClient($mockedStack);
      $lastFmClient = new LastFmClient($this->mockedGuzzleConfig(), $client);
      $lastFmClient->topArtistsByCountry('brazil', 2);
      $this->assertEquals(count($this->request_history), 1);
      $transaction = $this->request_history[0];
      $request = $transaction['request'];

      $this->assertEquals($request->getUri()->getQuery(), 'limit=5&api_key=mocked&method=geo.gettopartists&country=brazil&page=2');
    }

    /**
     * Test top artists by country with success, it should build
     * the query_string correctly
     *
     * @return void
     */
    public function testTopArtistsByCountriesWithSuccess()
    {
      $mockedStack = [
        new Response(200, [], $this->sample_response_success)
      ];
      $client = $this->mockGuzzleClient($mockedStack);
      $lastFmClient = new LastFmClient($this->mockedGuzzleConfig(), $client);
      $lastFmClient->topArtistsByCountry('brazil', 1);
      $this->assertEquals(count($this->request_history), 1);
      $transaction = $this->request_history[0];
      $request = $transaction['request'];

      $this->assertEquals($request->getUri()->getQuery(), 'limit=5&api_key=mocked&method=geo.gettopartists&country=brazil&page=1');
    }

    /**
     * Test top artists by country with 400 bad request,
     * it should raise an exception
     *
     * @return void
     */
    public function testTopArtistsByCountriesWithBadRequest()
    {
      $mockedStack = [
        new Response(400, [], $this->sample_response_error)
      ];
      $client = $this->mockGuzzleClient($mockedStack);
      $lastFmClient = new LastFmClient($this->mockedGuzzleConfig(), $client);
      $this->setExpectedException(RequestException::class);
      $lastFmClient->topArtistsByCountry('lalala');
    }

    /**
     * Test top tracks by artist with success, it should build
     * the query_string correctly when navigating to page 2
     *
     * @return void
     */
    public function testTopTracksByArtistOnPageTwoWithSuccess() {
      $mockedStack = [
        new Response(200, [], $this->sample_response_success)
      ];
      $client = $this->mockGuzzleClient($mockedStack);
      $lastFmClient = new LastFmClient($this->mockedGuzzleConfig(), $client);
      $lastFmClient->topTracksByArtist('db36a76f-4cdf-43ac-8cd0-5e48092d2bae', 2);
      $this->assertEquals(count($this->request_history), 1);
      $transaction = $this->request_history[0];
      $request = $transaction['request'];

      $this->assertEquals($request->getUri()->getQuery(), 'limit=5&api_key=mocked&method=artist.getTopTracks&mbid=db36a76f-4cdf-43ac-8cd0-5e48092d2bae&page=2');
    }



    /**
     * Test top tracks by artist with success, it should build
     * the query_string correctly
     *
     * @return void
     */
    public function testTopTracksByArtistWithSuccess() {
      $mockedStack = [
        new Response(200, [], $this->sample_response_success)
      ];
      $client = $this->mockGuzzleClient($mockedStack);
      $lastFmClient = new LastFmClient($this->mockedGuzzleConfig(), $client);
      $lastFmClient->topTracksByArtist('db36a76f-4cdf-43ac-8cd0-5e48092d2bae');
      $this->assertEquals(count($this->request_history), 1);
      $transaction = $this->request_history[0];
      $request = $transaction['request'];

      $this->assertEquals($request->getUri()->getQuery(), 'limit=5&api_key=mocked&method=artist.getTopTracks&mbid=db36a76f-4cdf-43ac-8cd0-5e48092d2bae&page=1');
    }

    /**
     * Test top tracks by artist with 400 bad request,
     * it should raise an exception
     *
     * @return void
     */
    public function testTopTracksByArtistWithBadRequest()
    {
      $mockedStack = [
        new Response(400, [], $this->sample_response_error)
      ];
      $client = $this->mockGuzzleClient($mockedStack);
      $lastFmClient = new LastFmClient($this->mockedGuzzleConfig(), $client);
      $this->setExpectedException(RequestException::class);
      $lastFmClient->topTracksByArtist('banana');
    }

    private $sample_response_error = <<<XML
<?xml version="1.0" encoding="UTF-8" ?>
<lfm status="failed"><error code="6">country param invalid</error>
</lfm>
XML;

    private $sample_response_success = <<<XML
<?xml version="1.0" encoding="UTF-8" ?>
<lfm status="ok"><topartists country="Brazil" page="1" perPage="5" totalPages="5063118" total="25315589"><artist><name>Rihanna</name>
<listeners>4194627</listeners>
<mbid>db36a76f-4cdf-43ac-8cd0-5e48092d2bae</mbid>
<url>http://www.last.fm/music/Rihanna</url>
<streamable>0</streamable>
<image size="small">http://img2-ak.lst.fm/i/u/34s/d5db38e344da1e3405d9dba5cebb6171.png</image>
<image size="medium">http://img2-ak.lst.fm/i/u/64s/d5db38e344da1e3405d9dba5cebb6171.png</image>
<image size="large">http://img2-ak.lst.fm/i/u/174s/d5db38e344da1e3405d9dba5cebb6171.png</image>
<image size="extralarge">http://img2-ak.lst.fm/i/u/300x300/d5db38e344da1e3405d9dba5cebb6171.png</image>
<image size="mega">http://img2-ak.lst.fm/i/u/d5db38e344da1e3405d9dba5cebb6171.png</image>
</artist>
<artist><name>Coldplay</name>
<listeners>5052716</listeners>
<mbid>cc197bad-dc9c-440d-a5b5-d52ba2e14234</mbid>
<url>http://www.last.fm/music/Coldplay</url>
<streamable>0</streamable>
<image size="small">http://img2-ak.lst.fm/i/u/34s/67c69244b3d4cc207171f51c9feec477.png</image>
<image size="medium">http://img2-ak.lst.fm/i/u/64s/67c69244b3d4cc207171f51c9feec477.png</image>
<image size="large">http://img2-ak.lst.fm/i/u/174s/67c69244b3d4cc207171f51c9feec477.png</image>
<image size="extralarge">http://img2-ak.lst.fm/i/u/300x300/67c69244b3d4cc207171f51c9feec477.png</image>
<image size="mega">http://img2-ak.lst.fm/i/u/67c69244b3d4cc207171f51c9feec477.png</image>
</artist>
<artist><name>Arctic Monkeys</name>
<listeners>3199881</listeners>
<mbid>ada7a83c-e3e1-40f1-93f9-3e73dbc9298a</mbid>
<url>http://www.last.fm/music/Arctic+Monkeys</url>
<streamable>0</streamable>
<image size="small">http://img2-ak.lst.fm/i/u/34s/376466bb9e2a430bbea0c8cd5f086ee2.png</image>
<image size="medium">http://img2-ak.lst.fm/i/u/64s/376466bb9e2a430bbea0c8cd5f086ee2.png</image>
<image size="large">http://img2-ak.lst.fm/i/u/174s/376466bb9e2a430bbea0c8cd5f086ee2.png</image>
<image size="extralarge">http://img2-ak.lst.fm/i/u/300x300/376466bb9e2a430bbea0c8cd5f086ee2.png</image>
<image size="mega">http://img2-ak.lst.fm/i/u/376466bb9e2a430bbea0c8cd5f086ee2.png</image>
</artist>
<artist><name>Adele</name>
<listeners>2589092</listeners>
<mbid>cc2c9c3c-b7bc-4b8b-84d8-4fbd8779e493</mbid>
<url>http://www.last.fm/music/Adele</url>
<streamable>0</streamable>
<image size="small">http://img2-ak.lst.fm/i/u/34s/40eb2347b4c9149f38ebbcff70c648bb.png</image>
<image size="medium">http://img2-ak.lst.fm/i/u/64s/40eb2347b4c9149f38ebbcff70c648bb.png</image>
<image size="large">http://img2-ak.lst.fm/i/u/174s/40eb2347b4c9149f38ebbcff70c648bb.png</image>
<image size="extralarge">http://img2-ak.lst.fm/i/u/300x300/40eb2347b4c9149f38ebbcff70c648bb.png</image>
<image size="mega">http://img2-ak.lst.fm/i/u/40eb2347b4c9149f38ebbcff70c648bb.png</image>
</artist>
<artist><name>Lana Del Rey</name>
<listeners>1570459</listeners>
<mbid>b7539c32-53e7-4908-bda3-81449c367da6</mbid>
<url>http://www.last.fm/music/Lana+Del+Rey</url>
<streamable>0</streamable>
<image size="small">http://img2-ak.lst.fm/i/u/34s/0b710b7f6d854895c8cfc48cb6036504.png</image>
<image size="medium">http://img2-ak.lst.fm/i/u/64s/0b710b7f6d854895c8cfc48cb6036504.png</image>
<image size="large">http://img2-ak.lst.fm/i/u/174s/0b710b7f6d854895c8cfc48cb6036504.png</image>
<image size="extralarge">http://img2-ak.lst.fm/i/u/300x300/0b710b7f6d854895c8cfc48cb6036504.png</image>
<image size="mega">http://img2-ak.lst.fm/i/u/0b710b7f6d854895c8cfc48cb6036504.png</image>
</artist>
</topartists>
</lfm>
XML;

}

