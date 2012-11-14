#!/bin/bash
# -------------------------------------------------------
# TweetJukeBox - script to start/stop/restart the service
# -------------------------------------------------------
# Creative Commons Attribution-NonCommercial 3.0
# http://creativecommons.org/licenses/by-nc/3.0/
#
# (C) Copyright 2012 by Catodo (http://www.catodo.net)

RETVAL=0

start() {
rm ./playing 2>/dev/null
if [ -d "./tmp" ]; then
    echo "ERROR: to start a new TweetJukeBox you must stop the old one"
    exit 1
fi
mkdir tmp
echo "Starting Twitter service..."
cat > ./tmp/twitter.sh <<EOF
#!/bin/bash
while :
do
	php tweet.php
	sleep 20
done
EOF
chmod +x ./tmp/twitter.sh
./tmp/twitter.sh > ./log/tweet.log &
echo $! > ./tmp/tweetjukebox_service.pid
echo "Starting Jukebox service..."
cat > ./tmp/jukebox.sh <<EOF
#!/bin/bash
while :
do
	php play.php
	sleep 5
done
EOF
chmod +x ./tmp/jukebox.sh
./tmp/jukebox.sh > ./log/jukebox.log &
echo $! > ./tmp/tweetjukebox_player.pid
}

stop() {
echo "Stopping twitter.sh"
kill `cat ./tmp/tweetjukebox_service.pid`
echo "Stopping jukebox.sh"
kill `cat ./tmp/tweetjukebox_player.pid`
rm ./playing 2>/dev/null
rm -rf tmp
}

restart() {
stop
start
}

case "$1" in
start)
  start
;;
stop)
  stop
;;
restart)
  restart
;;
*)

echo "Usage:  {start|stop|restart}"
exit 1
esac

exit $RETVAL
