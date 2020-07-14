<?php
if(!isset($_GET['cron']) || $_GET['cron'] != '56bb78c4c8e84e0043783de938f26adf') die("Not valid");
$start_time = microtime(true);
ignore_user_abort(true);
include 'scripts/fetchImages.php';
if(!file_exists('news_temp.xml')){
	include_once 'scripts/crawlfeeds.php';
}
$initiate = new fetchImages();
$initiate->startProcessing($start_time);
?>
