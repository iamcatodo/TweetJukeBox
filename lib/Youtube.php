<?php
/**
 * --------------------------------------------------------------
 * Youtube class
 * --------------------------------------------------------------
 * Provide a couple of methods to search videos using Youtube's
 * API and to extract the MP3 data using Youtube-mp3.org's API.
 *  
 * @author  Catodo (http://www.catodo.net)
 * @license Creative Commons Attribution-NonCommercial 3.0
 *          http://creativecommons.org/licenses/by-nc/3.0/
 */
class Youtube {
    
    const URL_SEARCH  = 'https://gdata.youtube.com/feeds/api/videos?q=%s&alt=json&v=2';
    const URL_CONVERT = 'http://www.youtube-mp3.org/api/pushItem/?xy=yx&bf=false&item=';
    const URL_GETMP3  = 'http://www.youtube-mp3.org/get?video_id=%s&h=%s';
    const URL_INFOMP3 = 'http://www.youtube-mp3.org/api/itemInfo/?video_id=';
    
    /**
     * Search a video in Youtube
     * 
     * @param  string $search
     * @return boolean|array
     */
    public function search($search)
    {
        if (empty($search)) {
            return false;
        }
        $result = @file_get_contents(sprintf(self::URL_SEARCH, urlencode($search)));
        if (false === $result) {
            return false;
        }
        return json_decode($result, true);
    }
    /**
     * Convert a YouTube video in mp3
     * returns the converted video Id
     * 
     * @param  type $url 
     * @return boolean|string
     */
    public function convert($url)
    {
        if (empty($url)) {
            return false;
        }
        $result = @file_get_contents(self::URL_CONVERT . urlencode($url));
        if (false === $result) {
            return false;
        }
        return $result;
    }
    /**
     * Get the hash code of the mp3 converted file
     * 
     * @param  string $id
     * @return boolean|string 
     */
    public function hashMp3($id)
    {
        if (empty($id)) {
            return false;
        }
        $result = @file_get_contents(self::URL_INFOMP3 . urlencode($id));
        if (false === $result) {
            return false;
        }
        $mp3Info = json_decode(substr($result, 7, strlen($result)-8));
        if (empty($mp3Info)) {
            return false;
        }
        return $mp3Info->h;
    }
    /**
     * Get the mp3 
     * 
     * @param  string $id
     * @param  string $hash
     * @return boolean|string
     */
    public function getMp3($id, $hash)
    {
        if (empty($id) || empty($hash)) {
            return false;
        }
        $result = @file_get_contents(sprintf(self::URL_GETMP3, $id, $hash));
        if (false === $result) {
            return false;
        }
        return $result;
    }
}
