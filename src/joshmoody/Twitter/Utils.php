<?php

namespace joshmoody\Twitter;

class Utils
{
	/**
	 * Convert urls, twitter screen names, and hash tags to links.
	 */
	public static function linkify($text)
	{
	    // URLs
	    $text = preg_replace('/(https?:\/\/\S+)/', '<a href="\1">\1</a>', $text);
	    
	    // Users
	    $text = preg_replace('/(^|\s)@(\w+)/', '\1<a href="https://twitter.com/\2">@\2</a>', $text);
	    
	    // Hash Tags
	    $text = preg_replace('/(^|\s)#(\w+)/', '\1<a href="https://twitter.com/search?q=%23\2">#\2</a>', $text);
	    
	    return $text;
	}

    /**
     * Twitter's rest API shortens all URLS in the tweet by default. This method restores the original URLs.
     */
    public static function expandUrls($json)
    {
        $feed = json_decode($json);
        
        if (array_key_exists('statuses', $feed)) {
            $feed = $feed->statuses;
        }
        
        foreach($feed as $item) {

            if (is_array($item->entities->urls)) {

                foreach($item->entities->urls as $url) {
                    $json = str_replace(addcslashes($url->url, '/'), addcslashes($url->expanded_url, '/'), $json);
                }
                
            }
        }

        return $json;
    }

    /**
     * Parse out a photo from a tweet. Used by RSS converter to create enclosure.
     */
    public static function getPhoto($entities)
    {

        if (isset($entities->media) && is_array($entities->media) && $entities->media[0]->type == 'photo') {
			return self::getUrlInfo($entities->media[0]->media_url);
        } else {
            return false;
        }
    }
    
    public static function getUrlInfo($url)
    {
		$curl = curl_init($url);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_exec($curl);

		$content_length = curl_getinfo($curl, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
		$content_type = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);
		curl_close($curl);
        
        $result = (object) array('url'      => $url,
                                 'type'     => $content_type,
                                 'length'   => $content_length);
        return $result;	    
    }
}