# README

## Twitter Consumer

As of the 1.1 version of the Twitter REST API, all requests require OAuth.

On 11-Mar-2013, Twitter released Application-only authentication to allow requests on behalf of an APPLICATION, as opposed to on behalf of a specific USER.

This library implements the new authentication for public resources like user timelines.

See https://dev.twitter.com/docs/auth/application-only-auth for more info.

## Installation
This library is installable via composer.

    "require": {
        "joshmoody/twitter-consumer": "dev-master"
    },

## Usage

	// Get new instance of the twitter consumer.
	$consumer = new joshmoody\Twitter\Consumer('your-consumer-key', 'your-consumer-secret);
	
	// Fetch a joshmoody\Twitter\Response object
	$response = $consumer->request('statuses/user_timeline.json?screen_name=joshmoody');
	
	// Get the result as a stdclass object
	$timeline = $timeline->result();
	
	// ...or as RSS
	$rss = $timeline->rss(array('feed_title'=>'Tweets from @username', 'feed_url' => 'http://yourdomain.com/path/to/rss/feed', 'feed_description'=>'My Tweets'));
	
	// ...or as JSON encoded
	$json = $timeline->json();

## TODO 
* Add option to expand auto-shortened URLs
* Add optional feed caching

## Unit Testing.

[![Build Status](https://travis-ci.org/joshmoody/twitter-consumer.png?branch=master)](https://travis-ci.org/joshmoody/twitter-consumer)

This package uses PHPUnit for unit testing. To run the unit tests, you'll need to install the dependencies using Composer:

	php composer.phar install --dev.

Then run the tests with `vendor/bin/phpunit`