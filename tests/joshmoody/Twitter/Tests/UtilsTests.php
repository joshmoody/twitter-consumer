<?php

namespace joshmoody\Twitter\Tests;

class UtilsTests extends \joshmoody\Twitter\Tests\BaseTestCase
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function testCanGetPhotoInfo()
	{
		// Image with known size.
		$result = \joshmoody\Twitter\Utils::getUrlInfo('http://farm9.staticflickr.com/8489/8212103151_b3ef973992_s_d.jpg');

		$this->assertEquals('image/jpeg', $result->type);
		$this->assertEquals('5146', $result->length);
	}

	public function testCanLinkify()
	{
	
	}
	
	public function testCanExpandUrls()
	{
		
	}
}