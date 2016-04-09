<?php

use App\Helpers\LastFmClient;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;

class LastFmClientPaginationTest extends TestCase
{

  /**
   * Test if resulted array is of the right type independent on the page
   *
   * @return void
   */
  public function testTopArtistsByCountriesReturnTheRightType() {
      $mockedStack = [
        new Response(200, [], $this->sample_response_success),
        new Response(200, [], $this->response_one_page)
      ];
      $client = $this->mockGuzzleClient($mockedStack);
      $lastFmClient = new LastFmClient($this->mockedGuzzleConfig(), $client);
      $results = $lastFmClient->topArtistsByCountry('brazil', 2);
      $resultsOnePage = $lastFmClient->topArtistsByCountry('brazil', 1);
      $this->assertEquals('array', gettype($results));
      $this->assertEquals('array', gettype($resultsOnePage));
  }

    /**
     * Test top artists by country with success, it should build
     * the query_string correctly when navigating to another page
     * and have the right amount of results
     *
     * @return void
     */
    public function testTopArtistsByCountriesPageTwoWithSuccessAndRightNumberOfResults()
    {
      $mockedStack = [
        new Response(200, [], $this->sample_response_success)
      ];
      $client = $this->mockGuzzleClient($mockedStack);
      $lastFmClient = new LastFmClient($this->mockedGuzzleConfig(), $client);
      $results = $lastFmClient->topArtistsByCountry('brazil', 2);
      $this->assertEquals(5, count($results['topartists']['artist']));
      $this->assertEquals(count($this->request_history), 1);
      $transaction = $this->request_history[0];
      $request = $transaction['request'];
      $this->assertEquals($request->getUri()->getQuery(), 'limit=5&api_key=mocked&method=geo.gettopartists&country=brazil&page=2');
    }

    /**
     * Test top artists by country with success, it should build
     * the query_string correctly on the firts page
     * and have the right amount of results
     *
     * @return void
     */
    public function testTopArtistsByCountriesPageOneWithSuccessAndRightNumberOfResults()
    {
      $mockedStack = [
        new Response(200, [], $this->response_one_page)
      ];
      $client = $this->mockGuzzleClient($mockedStack);
      $lastFmClient = new LastFmClient($this->mockedGuzzleConfig(), $client);
      $results = $lastFmClient->topArtistsByCountry('brazil', 1);
      $this->assertEquals(5, count($results['topartists']['artist']));
      $this->assertEquals(count($this->request_history), 1);
      $transaction = $this->request_history[0];
      $request = $transaction['request'];
      $this->assertEquals($request->getUri()->getQuery(), 'limit=5&api_key=mocked&method=geo.gettopartists&country=brazil&page=1');
    }

    private $sample_response_success = <<<XML
<?xml version="1.0" encoding="UTF-8" ?>
<lfm status="ok"><topartists country="Brazil" page="2" perPage="5" totalPages="5057692" total="25288457"><artist><name>Rihanna</name>
<listeners>4194627</listeners>
<mbid>db36a76f-4cdf-43ac-8cd0-5e48092d2bae</mbid>
<url>http://www.last.fm/music/Rihanna</url>
<streamable>0</streamable>
<image size="small">http://img2-ak.lst.fm/i/u/34s/1831bb1f7dce4265c298a6181271b811.png</image>
<image size="medium">http://img2-ak.lst.fm/i/u/64s/1831bb1f7dce4265c298a6181271b811.png</image>
<image size="large">http://img2-ak.lst.fm/i/u/174s/1831bb1f7dce4265c298a6181271b811.png</image>
<image size="extralarge">http://img2-ak.lst.fm/i/u/300x300/1831bb1f7dce4265c298a6181271b811.png</image>
<image size="mega">http://img2-ak.lst.fm/i/u/1831bb1f7dce4265c298a6181271b811.png</image>
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
<image size="small">http://img2-ak.lst.fm/i/u/34s/6f2bbbf00abd3d074a2ec41d152ac025.png</image>
<image size="medium">http://img2-ak.lst.fm/i/u/64s/6f2bbbf00abd3d074a2ec41d152ac025.png</image>
<image size="large">http://img2-ak.lst.fm/i/u/174s/6f2bbbf00abd3d074a2ec41d152ac025.png</image>
<image size="extralarge">http://img2-ak.lst.fm/i/u/300x300/6f2bbbf00abd3d074a2ec41d152ac025.png</image>
<image size="mega">http://img2-ak.lst.fm/i/u/6f2bbbf00abd3d074a2ec41d152ac025.png</image>
</artist>
<artist><name>Lana Del Rey</name>
<listeners>1570459</listeners>
<mbid>b7539c32-53e7-4908-bda3-81449c367da6</mbid>
<url>http://www.last.fm/music/Lana+Del+Rey</url>
<streamable>0</streamable>
<image size="small">http://img2-ak.lst.fm/i/u/34s/14d523c9bc3f40d2c0456107d89e57ce.png</image>
<image size="medium">http://img2-ak.lst.fm/i/u/64s/14d523c9bc3f40d2c0456107d89e57ce.png</image>
<image size="large">http://img2-ak.lst.fm/i/u/174s/14d523c9bc3f40d2c0456107d89e57ce.png</image>
<image size="extralarge">http://img2-ak.lst.fm/i/u/300x300/14d523c9bc3f40d2c0456107d89e57ce.png</image>
<image size="mega">http://img2-ak.lst.fm/i/u/14d523c9bc3f40d2c0456107d89e57ce.png</image>
</artist>
<artist><name>Beyoncé</name>
<listeners>3251866</listeners>
<mbid>859d0860-d480-4efd-970c-c05d5f1776b8</mbid>
<url>http://www.last.fm/music/Beyonc%C3%A9</url>
<streamable>0</streamable>
<image size="small">http://img2-ak.lst.fm/i/u/34s/25d10dcf7b2cbb7e10a7577412fce05f.png</image>
<image size="medium">http://img2-ak.lst.fm/i/u/64s/25d10dcf7b2cbb7e10a7577412fce05f.png</image>
<image size="large">http://img2-ak.lst.fm/i/u/174s/25d10dcf7b2cbb7e10a7577412fce05f.png</image>
<image size="extralarge">http://img2-ak.lst.fm/i/u/300x300/25d10dcf7b2cbb7e10a7577412fce05f.png</image>
<image size="mega">http://img2-ak.lst.fm/i/u/25d10dcf7b2cbb7e10a7577412fce05f.png</image>
</artist>
<artist><name>The Weeknd</name>
<listeners>845864</listeners>
<mbid>c8b03190-306c-4120-bb0b-6f2ebfc06ea9</mbid>
<url>http://www.last.fm/music/The+Weeknd</url>
<streamable>0</streamable>
<image size="small">http://img2-ak.lst.fm/i/u/34s/e672c40c53cb4b6e8cec3984cc54efbc.png</image>
<image size="medium">http://img2-ak.lst.fm/i/u/64s/e672c40c53cb4b6e8cec3984cc54efbc.png</image>
<image size="large">http://img2-ak.lst.fm/i/u/174s/e672c40c53cb4b6e8cec3984cc54efbc.png</image>
<image size="extralarge">http://img2-ak.lst.fm/i/u/300x300/e672c40c53cb4b6e8cec3984cc54efbc.png</image>
<image size="mega">http://img2-ak.lst.fm/i/u/e672c40c53cb4b6e8cec3984cc54efbc.png</image>
</artist>
<artist><name>Justin Bieber</name>
<listeners>1333519</listeners>
<mbid>e0140a67-e4d1-4f13-8a01-364355bee46e</mbid>
<url>http://www.last.fm/music/Justin+Bieber</url>
<streamable>0</streamable>
<image size="small">http://img2-ak.lst.fm/i/u/34s/c9ea288e77e5fc3a64b62676374a56fb.png</image>
<image size="medium">http://img2-ak.lst.fm/i/u/64s/c9ea288e77e5fc3a64b62676374a56fb.png</image>
<image size="large">http://img2-ak.lst.fm/i/u/174s/c9ea288e77e5fc3a64b62676374a56fb.png</image>
<image size="extralarge">http://img2-ak.lst.fm/i/u/300x300/c9ea288e77e5fc3a64b62676374a56fb.png</image>
<image size="mega">http://img2-ak.lst.fm/i/u/c9ea288e77e5fc3a64b62676374a56fb.png</image>
</artist>
<artist><name>Maroon 5</name>
<listeners>3179044</listeners>
<mbid>0ab49580-c84f-44d4-875f-d83760ea2cfe</mbid>
<url>http://www.last.fm/music/Maroon+5</url>
<streamable>0</streamable>
<image size="small">http://img2-ak.lst.fm/i/u/34s/0a734955efa645abc5120989ba2614da.png</image>
<image size="medium">http://img2-ak.lst.fm/i/u/64s/0a734955efa645abc5120989ba2614da.png</image>
<image size="large">http://img2-ak.lst.fm/i/u/174s/0a734955efa645abc5120989ba2614da.png</image>
<image size="extralarge">http://img2-ak.lst.fm/i/u/300x300/0a734955efa645abc5120989ba2614da.png</image>
<image size="mega">http://img2-ak.lst.fm/i/u/0a734955efa645abc5120989ba2614da.png</image>
</artist>
<artist><name>David Bowie</name>
<listeners>3065085</listeners>
<mbid>5441c29d-3602-4898-b1a1-b77fa23b8e50</mbid>
<url>http://www.last.fm/music/David+Bowie</url>
<streamable>0</streamable>
<image size="small">http://img2-ak.lst.fm/i/u/34s/ce93540127b945fa827d0a58a6dc1efb.png</image>
<image size="medium">http://img2-ak.lst.fm/i/u/64s/ce93540127b945fa827d0a58a6dc1efb.png</image>
<image size="large">http://img2-ak.lst.fm/i/u/174s/ce93540127b945fa827d0a58a6dc1efb.png</image>
<image size="extralarge">http://img2-ak.lst.fm/i/u/300x300/ce93540127b945fa827d0a58a6dc1efb.png</image>
<image size="mega">http://img2-ak.lst.fm/i/u/ce93540127b945fa827d0a58a6dc1efb.png</image>
</artist>
</topartists>
</lfm>
XML;
    private $response_one_page = <<<XML
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

