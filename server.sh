#!/bin/bash
#/usr/local/php/bin/php /home/www/newteam/qukuailiangou/server.php start -d
read number < data.txt
if [ "$number" = "1" ]; then
  echo "0" > data.txt
  /usr/local/php/bin/php /home/www/newteam/qukuailiangou/server.php restart -d
else
  echo "error"
fi
