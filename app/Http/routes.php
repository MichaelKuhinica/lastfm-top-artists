<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Stub api endpoints
Route::group(['namespace' => 'Api\V1', 'prefix' => 'api/v1'], function()
{
  // JSON api endpoint to list the top artists in a specified country
  Route::get('/artists/top/{country}', 'ArtistController@topByCountry');

  // JSON api endpoint to list the top tracks of a specified artist
  Route::get('/tracks/top/{artist}', 'TrackController@topByArtist');
});

Route::any('{path?}', function () {
    return view('welcome');
})->where('path', '.+');
