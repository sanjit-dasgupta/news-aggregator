<?php
require_once 'feed.php';
require_once 'sourcefeeds.php';
require_once 'buildXML.php';
Feed::$cacheDir = getcwd()."/feed";
Feed::$cacheExpire = '20 minutes';
$oldestPost = '-2 days';
$time = strtotime($oldestPost);
$xml = new buildXML();
$rss_data = array();
foreach($sources as $source){
	$rss = Feed::loadRss($source->get_link());
	foreach($rss->item as $item){
		$item->cat = $source->get_cat();
		if((int)$item->timestamp >= (int)$time) $rss_data[] = $item;
	}
}
function sortRSS($a, $b){
    return (int)$a->timestamp < (int)$b->timestamp;
}
usort($rss_data, "sortRSS");

foreach($rss_data as $item){
	$description = '';
	if (isset($item->{'content:encoded'})){
		$description = $item->{'content:encoded'};
	}else $description = strip_tags($item->description);
	$xml->appendItem($item->title, $item->link, $description, $item->img, $item->cat, $item->timestamp);
}
$xml->appendPrevItems($time);
$xml->saveFile();
?>