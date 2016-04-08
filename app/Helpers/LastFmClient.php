<?php

namespace app\Helpers;

use App\Helpers\Contracts\LastFmClientContract;

class LastFmClient implements LastFmClientContract
{

  protected $config;
  protected $client;

  public function __construct($config, \GuzzleHttp\Client $client) {
    $this->config = $config;
    $this->client = $client;
  }

  private function buildBaseQuery($parameters) {
    return array_merge(
      array(
        'limit' => 5,
        'api_key' => $this->config['api_key']
      ),
      $parameters
    );
  }

  public function topArtistsByCountry($country, $page = 1) {
    $response = $this->client->request('GET', '', [
      'query' => $this->buildBaseQuery([
        'method' => 'geo.gettopartists',
        'country' => $country,
        'page' => $page || 1,
      ])
    ]);
    $artists = new \SimpleXMLElement($response->getBody()->getContents());
    return $artists;
  }

  public function topTracksByArtist($artist, $page = 1) {
  }

}
