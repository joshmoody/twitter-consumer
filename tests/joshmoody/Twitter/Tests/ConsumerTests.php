<?php

namespace joshmoody\Twitter\Tests;

class ConsumerTests extends \joshmoody\Twitter\Tests\BaseTestCase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function testGetToken()
	{
		if ($this->key && $this->secret) {
			$token = $this->consumer->getToken();
			$this->assertInternalType('string', $token);
		} else {
			print __CLASS__ . '\\' . __METHOD__ . "\n";
			print "Set test_key and test_param environment variables to your keys in phpunit.xml to run this test.\n";
		}
	}

	public function testUserTimeline()
	{
		if ($this->key && $this->secret) {
			$response = $this->consumer->request('statuses/user_timeline.json?screen_name=joshmoody');
			$this->assertInstanceOf('joshmoody\Twitter\Response', $response);
		} else {
			print __CLASS__ . '\\' . __METHOD__ . "\n";
			print "Set test_key and test_param environment variables to your keys in phpunit.xml to run this test.\n";
		}
	}
	
	public function testGetKey()
	{
		$key = $this->consumer->getAuthKey();
		$test = base64_encode(urlencode($this->key) . ':' . urlencode($this->secret));
		$this->assertEquals($key, $test);
	}

	public function testResponseResultReturnsExpectedValue()
	{
		$response = $this->getMockResponse();
		$result = $response->result();
		
		$this->assertEquals($result[0]->id, 347928349328408576);
	}

	public function testResponseCanReturnJson()
	{
		$response = $this->getMockResponse();
		
		$decoded = json_decode($response->json());
		
		$this->assertEquals($decoded[0]->id, 347928349328408576);
	}

	public function testResponseCanConvertRss()
	{
		$response = $this->getMockResponse('tests/data/timeline_lcdonline.json');
		$rss = $response->rss();
		
		$this->assertRegExp('/tweets from @username/i', $rss);
	}
	
	public function testRssIsValid()
	{
		$response = $this->getMockResponse('tests/data/timeline_lcdonline.json');

		$rss = $response->rss();
		
		@$xml = simplexml_load_string($rss);

		$this->assertInstanceOf('simplexmlelement', $xml->channel->title);
	}
}