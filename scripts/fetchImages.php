<?php
class fetchImages{
	public static $xml_temp_file_name = 'news_temp.xml';
	public static $xml_file_name = 'news.xml';
	public function startProcessing($start_time){
		$count = 0;
		$dom = new DOMDocument();
		if(file_exists(self::$xml_temp_file_name) && $dom->load(self::$xml_temp_file_name, LIBXML_NOWARNING)){
			$root = $dom->documentElement;
			$items = $root->getElementsByTagName('item');
			foreach($items as $item){
				$image = $item->getElementsByTagName('image');
				$cat = $item->getElementsByTagName('category');
				$link = $item->getElementsByTagName('link');
				if($image->length == 0){
					$image = self::getOgImageFromUrl($link->item(0)->textContent);
					if($image == null || $image == NULL || $image == ''){
					    $count++;
					    continue;
					}
					$imageD = $dom->createElement('image');
					$imageURL = $dom->createElement('url');
					$imageURL->appendChild($dom->createCDATASection($image));
					$imageD->appendChild($imageURL);
					$item->appendChild($imageD);
				}
				$count++;
				$end_time = microtime(true);
				if($end_time - $start_time > 90) break;
			}
			if($count != $items->length) $dom->save(self::$xml_temp_file_name);
			else{
				$dom->save(self::$xml_file_name);
				unlink(self::$xml_temp_file_name);
				/*$myfile = fopen("newfile.txt", "a");
                fwrite($myfile, strtotime('now').'\n');
                fclose($myfile);*/
			}
			echo $count;
		}
	}
	private static function getOgImageFromUrl($url){
		$html = trim(self::httpRequest($url));
		$doc = new DOMDocument();
		@$doc->loadHTML($html);
		/*$nodes = $doc->getElementsByTagName('title');
		$title = $nodes->item(0)->nodeValue;*/
		$metas = $doc->getElementsByTagName('meta');
		for ($i = 0; $i < $metas->length; $i++){
			$meta = $metas->item($i);
			if($meta->getAttribute('property') == 'og:image'){
				$img = $meta->getAttribute('content');
				return $img;
			}
		}
	}
	private static function httpRequest($url)
	{
		if (extension_loaded('curl')) {
			$curl = curl_init();
			//curl_setopt($curl, CURLOPT_PROXY, '127.0.0.1:8888');
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_TIMEOUT, 20);
			curl_setopt($curl, CURLOPT_ENCODING, '');
			curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl,CURLOPT_FOLLOWLOCATION , true);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			if (!ini_get('open_basedir')) {
				curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
			}
			$result = curl_exec($curl);
			return curl_errno($curl) === 0 && curl_getinfo($curl, CURLINFO_HTTP_CODE) === 200
				? $result
				: false;

		} else{
			return file_get_contents($url);
		}
	}
}
?>