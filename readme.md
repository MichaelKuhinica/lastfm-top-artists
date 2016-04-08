# Last.fm top artists

Lists the most popular artists on Last.fm by country in response to user searches.

- The user should be able to enter a keyword (country name), which is then used to search Last.fm via their REST API.
- The search results should be paginated and displayed as five results per page, and the user should be able to navigate to other pages.
- Each result should be displayed as the name of the band and a thumbnail of the band; clicking on the thumbnail should open a new page which shows the Artist Top Tracks.

## Setup Instructions

This application is build using [Laravel](http://laravel.com), The PHP Framework For Web Artisans.

Laravel uses [Composer](https://getcomposer.org) for dependency management, you must install it to build the project.

### Install Composer (OSX with Homebrew)

Install the package with `brew install homebrew/php/composer`.

Add Composer's executables path `~/.composer/vendor/bin` to your environment `PATH`.

### Install dependencies

Run `composer install` from the project root directory to install dependencies.

### Configure Application

- Copy the file `.env.example` to `.env`
- Run `php artisan key:generate` to set a random string on `APP_KEY`
- Fill `LAST_FM_API_KEY` with your valid LastFm key

## Testing

Laravel uses phpunit as a test suit. Ensure you have the `phpunit` binary in your environment path and run it from the project root directory to run the test cases.


