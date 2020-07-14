<?php
class Sources{
	private $link;
	private $category;
	function __construct($l, $c) {
        $this->link = $l;
		$this->category = $c;
    }
	public function get_link(){
		return $this->link;
	}
	public function get_cat(){
		return $this->category;
	}
}
$sources = array();
$sources[] = new Sources("http://feeds.reuters.com/reuters/INtopNews", "Headlines");
$sources[] = new Sources("http://feeds.feedburner.com/ndtvnews-latest", "Headlines");
$sources[] = new Sources("https://economictimes.indiatimes.com/rssfeedstopstories.cms", "Top News");
$sources[] = new Sources("https://sports.ndtv.com/rss/all", "Sports");
$sources[] = new Sources("https://timesofindia.indiatimes.com/rssfeeds/4719148.cms", "Sports");
$sources[] = new Sources("http://feeds.feedburner.com/RenewableEnergyNewsRssFeed", "Power");
$sources[] = new Sources("http://feeds.feedburner.com/gadgets360-latest", "Technology");
$sources[] = new Sources("https://www.hindustantimes.com/rss/tech/rssfeed.xml", "Technology");
?>