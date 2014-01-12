<?php

namespace joshmoody\Twitter;

class Rss
{
	public static function convert(&$feed, $config)
	{
		// Set some defaults.
		$feed_title = 'Tweets from @username';
		$feed_url = 'http://feeds.example.com/twitter';
		$feed_description = "Searching for the Answer to the Ultimate Question of Life, the Universe, and Everything.";

		extract($config, EXTR_IF_EXISTS);
				
		$now = date('r');

		$output[] = '<?xml version="1.0" encoding="UTF-8"?>';
		$output[] = '<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">';
		$output[] = '<channel>';
		$output[] = sprintf('<title>%s</title>', utf8_encode($feed_title));
		$output[] = sprintf('<link>%s</link>', $feed_url);
		$output[] = sprintf('<description>%s</description>', utf8_encode($feed_description));
		$output[] = sprintf('<pubDate>%s</pubDate>', $now);
		$output[] = sprintf('<lastBuildDate>%s</lastBuildDate>', $now);
	
		foreach ($feed as $item) {
			$screen_name = htmlentities($item->user->screen_name);
			$text_linked = Utils::linkify(utf8_encode($item->text));
			
			$output[] = '<item>';
			$output[] = sprintf('<title>%s</title>', utf8_encode($item->text));
			$output[] = sprintf(
							'<guid>%s</guid>',
							htmlentities('https://twitter.com/'.$item->user->screen_name.'/statuses/'.$item->id_str)
			);

			$output[] = sprintf(
							'<link>%s</link>',
							htmlentities('https://twitter.com/'.$item->user->screen_name.'/statuses/'.$item->id_str)
			);
			$output[] = sprintf('<description><![CDATA[<p>%s</p>]]></description>', $text_linked);


			$photo = Utils::getPhoto($item->entities);
			
			if ($photo) {
				$output[] = sprintf('<enclosure url="%s" length="%d" type="%s" />', $photo->url, $photo->length, $photo->type);
			}
			
			$output[] = sprintf('<dc:creator>%s</dc:creator>', $screen_name);
			$output[] = sprintf('<pubDate>%s</pubDate>', date('r', strtotime($item->created_at)));
			$output[] = '</item>';
		}

		$output[] = '</channel>';
		$output[] = '</rss>';
		return join("\n", $output);
	}
}
