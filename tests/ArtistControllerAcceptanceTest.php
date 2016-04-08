<?php


class ArtistControllerAcceptanceTest extends TestCase
{
  /**
   * Navigate to top artists api and check resulting JSON in case of success
   *
   * @return void
   */
  public function testTopArtistsByCountriesWithSuccess() {
    $this->json('GET', '/api/v1/artists/top/brazil/?page=2')
      ->seeJson([
        "status" => "ok",
        "page" => "2"
      ]);
  }

  /**
   * Navigate to top artists api and check resulting JSON in case of error
   *
   * @return void
   */
   public function testTopArtistsByCountriesWithRequestError() {
    $this->json('GET', '/api/v1/artists/top/lalala/?page=2')
      ->seeJson([
        "status" => "failed",
        "error" => "country param invalid"
      ]);
  }

}


