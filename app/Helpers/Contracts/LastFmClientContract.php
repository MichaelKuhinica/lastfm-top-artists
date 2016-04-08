<?php

namespace App\Helpers\Contracts;

Interface LastFmClientContract
{
  public function topArtistsByCountry($country, $page = 1);
  public function topTracksByArtist($artist_mbid, $page = 1);
}
