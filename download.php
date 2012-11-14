<?php
/**
 * --------------------------------------------------------------
 * TweetJukebox
 * --------------------------------------------------------------
 * Search a video in Youtube and extract the MP3 data 
 * 
 * @author  Catodo (http://www.catodo.net)
 * @license Creative Commons Attribution-NonCommercial 3.0
 *          http://creativecommons.org/licenses/by-nc/3.0/
 */

if ($argc<3) {
    echo "Usage: $ download.php id \"text\"\n";
    exit();
}

$id   = $argv[1];
$text = $argv[2];
$fileConfig = dirname(__FILE__) . '/config.php';

if (!file_exists($fileConfig)) {
    die ("Missing configuration file.\n");
}
$config = @include($fileConfig);

$db = new PDO("sqlite:{$config['sqlite']}");

require_once 'lib/Youtube.php';

$youtube = new Youtube();
$result = $youtube->search($text);

if (!isset($result['feed']['entry'])) {
    $db->exec("DELETE FROM tweet WHERE id=$id");
    die("No result found, sorry.\n");
} 

$video = $result['feed']['entry'][0];
$url   = $video['link'][0]['href'];
$title = $video['title']['$t'];

$filename = dirname(__FILE__) . '/audio/' . md5($url) . '.mp3';

if (!file_exists($filename)) {

    $youtube_id = $youtube->convert($url);
    if (false === $youtube_id) {
        die('ERROR id'); 
    }

    $hash = $youtube->hashMp3($youtube_id);
    if (false === $hash) {
        die('ERROR hash'); 
    }

    $mp3 = $youtube->getMp3($youtube_id, $hash);
    
    if (empty($mp3)) {
        die("ERROR mp3");
    }
    file_put_contents($filename, $mp3);
}

$db->exec("UPDATE tweet SET title='$title', video_url='$url', mp3='$filename' WHERE id=$id");
