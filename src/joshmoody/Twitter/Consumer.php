<?php

namespace joshmoody\Twitter;

class Consumer
{
	public $request_info = null;

	protected $token = null;
	protected $key = null;
	protected $secret = null;
	
	public function __construct($key = null, $secret = null)
	{
		$this->key = $key;
		$this->secret = $secret;
	}

	/**
	 * Make an authenticated api request to twitter.
	 * 
	 * @access public
	 * @param mixed $url
	 * @param mixed $expand_urls (Should we auto expand shortened URLs? default: false)
	 * @return string JSON response
	 */
	public function request($url)
	{

		if (is_null($this->token)) {

			try {
				$this->token = $this->getToken();
			} catch (Exception $e) {
				return json_encode(array('success' => false, 'error' => $e->getMessage()));
			}
		}
		
		$browser = new \Buzz\Browser(new \Buzz\Client\Curl());
		
		$headers = array();
		$headers['Authorization'] = 'Bearer ' . $this->token;
		$headers['User-Agent'] = 'joshmoody\Twitter\Consumer: PHP ' . phpversion();
		
		$response = $browser->get('https://api.twitter.com/1.1/' . $url, $headers);

		if (!$response->isOk()) {
			throw new \Exception($response->getReasonPhrase());
		} else {
			return new \joshmoody\Twitter\Response($response->getContent());
		}
	}

	/**
	 * Use basic auth with a key comprised of our consumer key/consumer token
	 * to request a bearer token.
	 */
	public function getToken()
	{

		if (!is_null($this->token)) {
			return $this->token;
		}
		
		$auth_key = $this->getAuthKey();
		
		$browser = new \Buzz\Browser(new \Buzz\Client\Curl());
		
		$headers = array();
		$headers['Authorization'] = 'Basic ' . $auth_key;
		$headers['User-Agent'] = phpversion();
		$response = $browser->post('https://api.twitter.com/oauth2/token', $headers, 'grant_type=client_credentials');

		if (!$response->isOk()) {
			throw new \Exception($response->getReasonPhrase());
		} else {
			return json_decode($response->getContent())->access_token;
		}
	}
	
	/**
	 * Sets the token, can be used with get_token to store the token bewtween calls
	 */
	public function setToken($token)
	{
		$this->token=$token;
	}
	
	/**
	 * Creates a basic auth key used to get a bearer token.
	 */
	public function getAuthKey()
	{
		return base64_encode(urlencode($this->key) . ':' . urlencode($this->secret));
	}
}
