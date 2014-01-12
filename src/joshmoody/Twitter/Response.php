<?php

namespace joshmoody\Twitter;

class Response
{
	protected $response;
	protected $json;
	
	public function __construct($response)
	{
		$this->response = json_decode($response);
		$this->json = $response;
	}
	
	/**
	 * Google is killing reader, and Twitter has killed it's RSS feed. 
	 * But just in case you want to expose your tweets as RSS...
	 */
	public function rss($opts = array())
	{
		return Rss::convert($this->response, $opts);
	}
	
	public function json()
	{
		return $this->json;
	}

	
	public function result()
	{
		return $this->response;
	}
}
