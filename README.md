## TweetJukeBox

TweetJukeBox is a "virtual" jukebox that uses Twitter to select a song, with a
special hashtag, and search for the song in the database of [YouTube](http://www.youtube.com). 
The song is added to a local queue and is played using [mplayer](http://www.mplayerhq.hu).

The TweetJukeBox has been developed by [Catodo](http://www.catodo.net) as art installation.
You can read more about the art project here: http://www.catodo.net/tweetjukebox/

### SYSTEM REQUIREMENTS

TweetJukeBox requires the GNU/Linux operating system with the following softwares:

- PHP 5.3 (or later);
- mplayer 1.x.

You need the PDO and SQlite extension enabled on your PHP environment (PDO and
the PDO_SQLITE driver is enabled by default as of PHP 5.1.0).

### INSTALLATION

You can download (or clone) TweetJukeBox from github.

To download as ZIP file [click here](https://github.com/iamcatodo/TweetJukeBox/zipball/master).

To clone it, run the following command:

    $ git clone https://github.com/iamcatodo/TweetJukeBox.git

After the download you need to enable the execution of the *tweetjukebox.sh* script
using the following command:

    $ chmod +x tweetjukebox.sh

Now you are ready to execute the TweetJukeBox!

### USAGE

Before start the TweetJukeBox you need to configure it. The configuration is
stored in the *config.php* file. You can use the default values. The *hashtag*
parameter can be customized to specify the hash to search in Twitter for the
selection of the songs.

To start the TweetJukeBox you can use the following command:

    $ ./tweetjukebox.sh start

To stop the TweetJukeBox you can use the following command:

    $ ./tweetjukebox.sh stop

You can also restart the TweetJukeBox using the following command:

    $ ./tweetjukebox.sh restart

### LICENSE

Creative Commons Attribution-NonCommercial 3.0
http://creativecommons.org/licenses/by-nc/3.0/

### COPYRIGHT

(C) Copyright 2012 by Catodo - http://www.catodo.net
