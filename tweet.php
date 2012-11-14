<?php
/**
 * --------------------------------------------------------------
 * TweetJukebox
 * --------------------------------------------------------------
 * Read from twitter and write in a SQLite database
 * 
 * @author  Catodo (http://www.catodo.net)
 * @license Creative Commons Attribution-NonCommercial 3.0
 *          http://creativecommons.org/licenses/by-nc/3.0/
 */

$fileConfig = dirname(__FILE__) . '/config.php';

if (!file_exists($fileConfig)) {
    die ("Missing configuration file.\n");
}

$config = @include("config.php");
$config['sqlite'] = dirname(__FILE__) . '/' . $config['sqlite'];
if (!isset($config['hashtag']) || empty($config['hashtag'])) {
    die ("You need to specify the hashtag in config.php\n");
}
$hash = $config['hashtag'];

if (!file_exists($config['sqlite'])) {
    $db = new PDO("sqlite:{$config['sqlite']}");
    $db->exec("CREATE TABLE tweet (id INTEGER PRIMARY KEY, msg TEXT, created_at TEXT, from_user TEXT, from_user_id INTEGER, title TEXT, video_url TEXT, mp3 TEXT, status INTEGER)");
    $db->exec("CREATE TABLE twitter (id INTEGER PRIMARY KEY, last_id INTEGER)");
} else {
    $db = new PDO("sqlite:{$config['sqlite']}");
}

$url_twitter = 'http://search.twitter.com/search.json?q=%23' . urlencode($hash);

$lastId = $db->query('SELECT last_id FROM twitter WHERE id=1;')->fetch();

if (isset($lastId['last_id'])) {
    $url_twitter .= '&since_id='. $lastId['last_id'];
}

$twitter = json_decode(file_get_contents($url_twitter));

if (empty($twitter->results)) {
    exit();
}

$db->exec("INSERT OR REPLACE INTO twitter (id,last_id) VALUES (1,{$twitter->max_id})");

foreach ($twitter->results as $tweet) {
    $secs = time() - strtotime($tweet->created_at);
    if ($secs <= $config['limit']) { 
        $text = trim(str_replace('#'.$hash, '', $tweet->text));
        $db->exec("INSERT INTO tweet (id,msg,created_at,from_user,from_user_id, video_url, mp3, status) VALUES ('{$tweet->id}','$text', '{$tweet->created_at}', '{$tweet->from_user}', {$tweet->from_user_id}, '', '', 0)");
        echo "writing in the tweet queue {$tweet->id}...\n";
        pclose(popen("php download.php {$tweet->id} \"$text\" &","r"));
    }    
} 
