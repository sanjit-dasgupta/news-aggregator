<?php
class buildXML{
	public static $xml_temp_file_name = 'news_temp.xml';
	public static $xml_file_name = 'news.xml';
	private $dom, $channel, $lastBuildTime;
	function __construct(){
		$dom = new DOMDocument();
		$dom->encoding = 'utf-8';
		$dom->xmlVersion = '1.0';
		$dom->formatOutput = true;
		$root = $dom->createElement('rss');
		$root->setAttributeNode(new DOMAttr('version', '2.0'));
		$channel = $dom->createElement('channel');
		$title = $dom->createElement('title', 'RSS');
		$link = $dom->createElement('link', 'https://rssfeedbhel.000webhostapp.com');
		$desc = $dom->createElement('description', 'Combined RSS');
		$buildDate = $dom->createElement('lastBuildDate', strtotime('now'));
		$channel->appendChild($title);
		$channel->appendChild($link);
		$channel->appendChild($desc);
		$channel->appendChild($buildDate);
		$root->appendChild($channel);
		$dom->appendChild($root);
		$this->dom = $dom;
		$this->channel = $channel;
		$this->lastBuildTime = $this->getLastBuildDate();
	}
	private function getLastBuildDate(){
		$dom = new DOMDocument();
		if(file_exists(self::$xml_file_name) && $dom->load(self::$xml_file_name, LIBXML_NOWARNING)){
			$root = $dom->documentElement;
			$lastBuildTime = $root->getElementsByTagName('lastBuildDate');
			return $lastBuildTime->item(0)->textContent;
		}
		return 0;
	}
	public function appendPrevItems($time){
		$dom = new DOMDocument();
		if(file_exists(self::$xml_file_name) && $dom->load(self::$xml_file_name, LIBXML_NOWARNING)){
			$root = $dom->documentElement;
			$items = $root->getElementsByTagName('item');
			foreach($items as $item){
				$timestamp = $item->getElementsByTagName('pubDate')->item(0)->textContent;
				if((int)$timestamp >= (int)$time){
					$title = $item->getElementsByTagName('title')->item(0)->textContent;
					$link = $item->getElementsByTagName('link')->item(0)->textContent;
					$description = $item->getElementsByTagName('description')->item(0)->textContent;
					$image = $item->getElementsByTagName('url');
					if($image->length > 0){
						$image = $image->item(0)->textContent;
					}else{
						$image = "Not Found";
					}
					$category = $item->getElementsByTagName('category')->item(0)->textContent;
					$this->appendItem($title, $link, $description, $image, $category, $timestamp, true);
				}
			}
		}
	}
	public function appendItem($title, $link, $description, $image, $category, $timestamp, $bypass = false){
		if(!$bypass && (int)$timestamp <= (int)$this->lastBuildTime) return;
		$dom = $this->dom;
		$channel = $this->channel;
		$item = $dom->createElement('item');
		$titleD = $dom->createElement('title');
		$titleD->appendChild($dom->createCDATASection($title));
		$linkD = $dom->createElement('link');
		$linkD->appendChild($dom->createCDATASection($link));
		$descD = $dom->createElement('description');
		$descD->appendChild($dom->createCDATASection($description));
		$catD = $dom->createElement('category');
		$catD->appendChild($dom->createCDATASection($category));
		$timestampD = $dom->createElement('pubDate');
		$timestampD->appendChild($dom->createCDATASection($timestamp));
		$item->appendChild($titleD);
		$item->appendChild($linkD);
		$item->appendChild($descD);
		if($image != "Not Found"){
			$imageD = $dom->createElement('image');
			$imageURL = $dom->createElement('url');
			$imageURL->appendChild($dom->createCDATASection($image));
			$imageD->appendChild($imageURL);
			$item->appendChild($imageD);
		}
		$item->appendChild($catD);
		$item->appendChild($timestampD);
		$channel->appendChild($item);
	}
	public function saveFile(){
		$this->dom->save(self::$xml_temp_file_name);
	}
}
?>