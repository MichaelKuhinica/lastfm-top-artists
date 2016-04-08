# Last.fm top artists

Lists the most popular artists on Last.fm by country in response to user searches.

- The user should be able to enter a keyword (country name), which is then used to search Last.fm via their REST API.
- The search results should be paginated and displayed as five results per page, and the user should be able to navigate to other pages.
- Each result should be displayed as the name of the band and a thumbnail of the band; clicking on the thumbnail should open a new page which shows the Artist Top Tracks.

## Setup Instructions

This application is build using [Laravel](http://laravel.com), The PHP Framework For Web Artisans.

Laravel uses [Composer](https://getcomposer.org) for dependency management and [node.js](https://nodejs.org/en/) with [Gulp](http://gulpjs.com/) for assets compilation, you must install them to build the project.

### Install Composer (OSX with Homebrew)

Install the package with:
```
brew install homebrew/php/composer
```

Add Composer's executables path `~/.composer/vendor/bin` to your environment `PATH`.

### Install node (OSX)

Download and install package from [their website](https://nodejs.org/en/). Node comes bundeled with npm, used for managing frontend dependencies.

### Install Gulp

Gulp is used to automate frontend tasks, such as assets compilation and minification. 

You can install it by running:
```
npm install --global gulp
```

### Install dependencies

To install Laravel dependencies, run from the project root directory:
```
composer install
```

You also need to install frontend dependencies by running `npm`:
```
npm install
```

### Configure Application

Copy the file `.env.example` to `.env`
Set a secure random string on `APP_KEY`
```
php artisan key:generate
```

Fill `LAST_FM_API_KEY` with your valid LastFm key

## Running
Compile frontend assets:
```
gulp
```

## Developing
You can tell gulp to keep watching your assets directory and recompile on any change, making the development process fast and easy:

```
gulp watch
```

## Testing

Laravel uses phpunit as a test suit. Ensure you have the `phpunit` binary in your environment path and execute it from the project root directory to run the test cases.
