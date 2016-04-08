<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Contracts\LastFmClientContract;

class ArtistController extends Controller
{
    /**
     * Returns a list of the top artists on the specified country
     *
     * @param  Request $request
     * @param  string $country
     * @return Response
     */
    public function topByCountry(Request $request, LastFmClientContract $lastFm, $country) {
      $page = $request->input('page') ?: 1;
      $response = $lastFm->topArtistsByCountry($country, $page);
      return response()->json($response);
    }

}

