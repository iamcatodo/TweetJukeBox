<?php
/**
 * --------------------------------------------------------------
 * TweetJukebox
 * --------------------------------------------------------------
 * Play a mp3 file from the jukebox's queue
 * 
 * @author  Catodo (http://www.catodo.net)
 * @license Creative Commons Attribution-NonCommercial 3.0
 *          http://creativecommons.org/licenses/by-nc/3.0/
 */

$flag = dirname(__FILE__) . '/playing';
if (file_exists($flag)) {
    die();
}

$fileConfig = dirname(__FILE__) . '/config.php';
if (!file_exists($fileConfig)) {
    die ("Missing configuration file.\n");
}
$config = @include($fileConfig);

$db = new PDO("sqlite:{$config['sqlite']}");

$mp3 = $db->query("SELECT * FROM tweet WHERE status=0 AND mp3<>'' ORDER BY id LIMIT 1")->fetch();

if (!isset($mp3['mp3'])) {
    exit(2);
}

if (!file_exists($mp3['mp3'])) {
    echo "Error: the mp3 file doesn\'t exist";
    exit(2);
}

file_put_contents($flag, '');
$db->exec("UPDATE tweet SET status=1 WHERE id=". $mp3['id']);

echo "Playing: {$mp3['title']}\n";
exec("mplayer {$mp3['mp3']} > /dev/null");

unlink($flag);
$db->exec("UPDATE tweet SET status=2 WHERE id=". $mp3['id']);
