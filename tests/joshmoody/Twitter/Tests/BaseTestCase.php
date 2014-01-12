<?php

namespace joshmoody\Twitter\Tests;

use joshmoody\Twitter\Consumer;
use joshmoody\Twitter\Response;
use joshmoody\Twitter\Utils;
use joshmoody\Twitter\Rss;

/**
 * Base testcase class
 */
abstract class BaseTestCase extends \PHPUnit_Framework_TestCase
{
	public $key = NULL;
	public $secret = NULL;
	public $consumer;

	public function __construct()
	{
		$this->key = getenv('test_key');
		$this->secret = getenv('test_secret');
	}
	
	public function setUp()
	{
		$this->consumer = new \joshmoody\Twitter\Consumer($this->key, $this->secret);
	}

	protected function getMockResponse($file = NULL)
	{
		if (is_null($file)) {
			$file = 'tests/data/timeline.json';
		}
		
		$json = file_get_contents($file);
		
		return new \joshmoody\Twitter\Response($json);
	}
}
