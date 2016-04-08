<?php


class TrackControllerAcceptanceTest extends TestCase
{
  /**
   * Navigate to top tracks api and check resulting JSON in case of success
   *
   * @return void
   */
  public function testTopArtistsByCountriesWithSuccess() {
    $this->json('GET', '/api/v1/tracks/top/db36a76f-4cdf-43ac-8cd0-5e48092d2bae/?page=2')
      ->seeJson([
        "status" => "ok",
        "page" => "2"
      ]);
  }

  /**
   * Navigate to top tracks api and check resulting JSON in case of error
   *
   * @return void
   */
   public function testTopArtistsByCountriesWithRequestError() {
    $this->json('GET', '/api/v1/tracks/top/banana/?page=2')
      ->seeJson([
        "status" => "failed",
        "error" => "The artist you supplied could not be found"
      ]);
  }

}


