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

  /**
   * Query LastFm api to return a list with the top 5 artists in the supplied
   * country with pagination
   *
   * @param string $country A country name, as defined by the ISO 3166-1
   * country names standard
   * @param int $page the page you wish to require, defaults to 1
   * @return array list of artists and pagination information
   * @throws GuzzleHttp\Exception\RequestException in case of connectivity error
   * @throws GuzzleHttp\Exception\ClientException in case of request error
   */
  public function topArtistsByCountry($country, $page = 1) {
    $response = $this->client->request('GET', '', [
      'query' => $this->buildBaseQuery([
        'method' => 'geo.gettopartists',
        'country' => $country,
        'page' => $page,
      ])
    ]);
    $artists = new \SimpleXMLElement($response->getBody()->getContents());
    return $this->normalizeResults($artists, 'topartists', 'artist');
  }

  /**
   * Query LastFm api to return a list with the top tracks for the specified
   * artist id
   *
   * @param string $artist_mbid The musicbrainz id for the artist
   * @param int $page the page you wish to require, defaults to 1
   * @return array list of tracks and pagination information
   * @throws GuzzleHttp\Exception\RequestException in case of connectivity error
   * @throws GuzzleHttp\Exception\ClientException in case of request error
   */
  public function topTracksByArtist($artist_mbid, $page = 1) {
    $response = $this->client->request('GET', '', [
      'query' => $this->buildBaseQuery([
        'method' => 'artist.getTopTracks',
        'mbid' => $artist_mbid,
        'page' => $page,
        'limit' => 10
      ])
    ]);
    $tracks = new \SimpleXMLElement($response->getBody()->getContents());
    return $tracks;
  }

  private function normalizeResults($col, $col_root, $col_attr) {
    if($col->$col_root && $col->$col_root->$col_attr) {
      $number_of_results = count($col->$col_root->$col_attr);
      if($number_of_results > 5) {
        $col_array = $this->_xml2array($col);
        $col_array[$col_root][$col_attr] = array_slice($col_array[$col_root][$col_attr], 5, 5);
        return $col_array;
      }
    }
    return $this->_xml2array($col);
  }

  private function _xml2array ( $xmlObject, $out = array () ){
      foreach ( (array) $xmlObject as $index => $node )
          $out[$index] = ( is_object ( $node ) ) ? $this->_xml2array ( $node ) : $node;

      return $out;
  }

}
