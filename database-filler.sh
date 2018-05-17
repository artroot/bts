#!/bin/sh

/usr/bin/mysqld_safe > /dev/null 2>&1 &

sleep 15

mysql -uroot -e "CREATE DATABASE koala;"
PASS=`pwgen 15 1`
mysql -uroot -e "CREATE USER 'koala'@'localhost' IDENTIFIED BY '$PASS';"
echo "CREATE USER 'koala'@'localhost' IDENTIFIED BY '$PASS';"
mysql -uroot -e "GRANT ALL PRIVILEGES ON koala . * TO 'koala'@'localhost';"
echo "GRANT ALL PRIVILEGES ON koala . * TO 'koala'@'localhost';"
mysql -uroot -e "FLUSH PRIVILEGES;"


echo "<?php \r\n\r\n return [ \r\n 'class' => 'yii\db\Connection', \r\n 'dsn' => 'mysql:host=localhost;dbname=koala', \r\n 'username' => 'koala', \r\n 'password' => '$PASS', \r\n 'charset' => 'utf8', \r\n ];" > /var/www/koala/config/db.php

cd /var/www/koala && git pull

/usr/bin/php /var/www/koala/yii migrate --interactive=0

mysqladmin -uroot shutdown
